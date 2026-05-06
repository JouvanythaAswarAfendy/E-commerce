<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::query()->with(['product' => function($q) {
            $q->with('sizes');
        }])->where('user_id', '=', Auth::id(), 'and')->get();
        return view('cart.index', compact('carts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'size' => 'nullable|string'
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        
        // Handle Stock Validation
        if ($request->filled('size')) {
            $productSize = \App\Models\ProductSize::query()
                ->where('product_id', '=', $request->product_id, 'and')
                ->where('size', '=', $request->size, 'and')
                ->first();

            if (!$productSize || $productSize->stock < $request->qty) {
                return back()->with('error', 'Stok untuk ukuran ' . $request->size . ' tidak mencukupi.');
            }
            $availableStock = $productSize->stock;
        } else {
            if ($product->stock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $availableStock = $product->stock;
        }

        $cart = Cart::query()
                    ->where('user_id', '=', Auth::id(), 'and')
                    ->where('product_id', '=', $request->product_id, 'and')
                    ->where('size', '=', $request->size, 'and')
                    ->first();

        if ($cart) {
            // Check if total qty exceeds stock
            if (($cart->qty + $request->qty) > $availableStock) {
                 return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }
            $cart->qty += $request->qty;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'size' => $request->size
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::query()->where('id', '=', $id, 'and')->where('user_id', '=', Auth::id(), 'and')->firstOrFail();
        
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        // Validasi Stok saat update
        if ($cart->size) {
            $productSize = \App\Models\ProductSize::query()
                ->where('product_id', '=', $cart->product_id, 'and')
                ->where('size', '=', $cart->size, 'and')
                ->first();
            
            if (!$productSize || $productSize->stock < $request->qty) {
                return back()->with('error', 'Stok untuk ukuran ' . $cart->size . ' tidak mencukupi. Stok tersedia: ' . ($productSize ? $productSize->stock : 0));
            }
        } else {
            $product = $cart->product;
            if ($product->stock < $request->qty) {
                return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
            }
        }

        $cart->update(['qty' => $request->qty]);

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function destroy($id)
    {
        Cart::query()->where('user_id', '=', Auth::id(), 'and')->delete($id);

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang');
    }
}
