<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSize;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::query()->with(['product.sizes'])->where('user_id', '=', Auth::id(), 'and')->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Validate stock for all items
        foreach ($carts as $cart) {
            if ($cart->size) {
                $sizeStock = $cart->product->sizes->where('size', $cart->size)->first();
                if (!$sizeStock || $sizeStock->stock < $cart->qty) {
                    return redirect()->route('cart.index')->with('error', "Stok untuk {$cart->product->name} ukuran {$cart->size} tidak mencukupi.");
                }
            } else {
                $stock = $cart->product->stock ?? 0;
                if ($stock < $cart->qty) {
                    return redirect()->route('cart.index')->with('error', "Stok untuk {$cart->product->name} tidak mencukupi.");
                }
            }
        }

        $totalPrice = $carts->sum('subtotal');

        return view('checkout.index', compact('carts', 'totalPrice'));
    }

    public function direct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'size' => 'nullable|string'
        ]);

        $product = \App\Models\Product::with('sizes')->findOrFail($request->product_id);
        
        // Validate stock
        if ($request->filled('size')) {
            $sizeStock = $product->sizes->where('size', $request->size)->first();
            if (!$sizeStock || $sizeStock->stock < $request->qty) {
                return back()->with('error', "Stok untuk ukuran {$request->size} tidak mencukupi.");
            }
            $price = ($sizeStock && $sizeStock->price) ? $sizeStock->price : $product->price;
        } else {
            $stock = $product->stock ?? 0;
            if ($stock < $request->qty) {
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $price = $product->price;
        }

        // Simulasikan cart data
        $carts = [
            (object)[
                'product_id' => $product->id,
                'qty' => $request->qty,
                'size' => $request->size,
                'product' => $product,
                'price' => $price,
                'subtotal' => $price * $request->qty
            ]
        ];

        $totalPrice = $price * $request->qty;

        return view('checkout.index', [
            'carts' => collect($carts),
            'totalPrice' => $totalPrice,
            'isDirect' => true,
            'product_id' => $product->id,
            'qty' => $request->qty,
            'size' => $request->size
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
        ]);

        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            if ($request->has('is_direct')) {
                $product = Product::with('sizes')->findOrFail($request->product_id);
                
                // Validate stock again before process
                if ($request->filled('size')) {
                    $sizeStock = $product->sizes->where('size', $request->size)->first();
                    if (!$sizeStock || $sizeStock->stock < $request->qty) {
                        throw new \Exception("Stok untuk ukuran {$request->size} tidak mencukupi.");
                    }
                    $price = ($sizeStock && $sizeStock->price) ? $sizeStock->price : $product->price;
                } else {
                    $stock = $product->stock ?? 0;
                    if ($stock < $request->qty) {
                        throw new \Exception("Stok produk tidak mencukupi.");
                    }
                    $price = $product->price;
                }

                $totalPrice = $price * $request->qty;
                
                $items = [
                    (object)[
                        'product_id' => $product->id,
                        'qty' => $request->qty,
                        'size' => $request->size,
                        'price' => $price,
                        'subtotal' => $price * $request->qty
                    ]
                ];
            } else {
                $carts = Cart::query()->with('product.sizes')->where('user_id', '=', $user->id, 'and')->get();
                if ($carts->isEmpty()) {
                    return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
                }

                // Validate stock for all items
                foreach ($carts as $cart) {
                    if ($cart->size) {
                        $sizeStock = $cart->product->sizes->where('size', $cart->size)->first();
                        if (!$sizeStock || $sizeStock->stock < $cart->qty) {
                            throw new \Exception("Stok untuk {$cart->product->name} ukuran {$cart->size} tidak mencukupi.");
                        }
                    } else {
                        $stock = $cart->product->stock ?? 0;
                        if ($stock < $cart->qty) {
                            throw new \Exception("Stok untuk {$cart->product->name} tidak mencukupi.");
                        }
                    }
                }

                $totalPrice = $carts->sum('subtotal');
                $items = $carts;
            }

            // Setup midtrans config
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $orderIdStr = 'ORD-' . time() . '-' . Str::random(5);

            $order = Order::create([
                'order_id' => $orderIdStr,
                'order_type' => 'online',
                'buyer' => $user->id,
                'created_by' => $user->id,
                'total_price' => $totalPrice,
                'shipping_address' => $request->shipping_address,
                'status' => 'pending',
                'stock_reduced' => false
            ]);

            foreach ($items as $item) {
                $itemQty = isset($item->qty) ? $item->qty : $item->quantity;
                $itemPrice = $item->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $itemQty,
                    'size' => $item->size,
                    'price' => $itemPrice,
                    'subtotal' => $itemQty * $itemPrice
                ]);
            }

            // Create payload for Midtrans
            $payload = [
                'transaction_details' => [
                    'order_id' => $orderIdStr,
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                'item_details' => collect($items)->map(function($item) {
                    $prod = isset($item->product) ? $item->product : Product::query()->find($item->product_id, ['*']);
                    $itemQty = isset($item->qty) ? $item->qty : $item->quantity;
                    $itemPrice = $item->price;
                    return [
                        'id' => $item->product_id,
                        'price' => $itemPrice,
                        'quantity' => $itemQty,
                        'name' => $prod->name . ($item->size ? " ({$item->size})" : "")
                    ];
                })->toArray()
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            
            $order->snap_token = $snapToken;
            $order->save();

            // Clear Cart if not direct
            if (!$request->has('is_direct')) {
                Cart::query()->where('user_id', '=', $user->id, 'and')->delete();
            }

            DB::commit();

            return view('checkout.pay', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
