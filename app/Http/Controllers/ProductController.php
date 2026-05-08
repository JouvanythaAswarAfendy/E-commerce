<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category')->where('stock', '>', 0, 'and');

        // Filter by category
        if ($request->filled('category')) {
            $categoryFilter = $request->category;
            
            // Find category IDs by ID or Name
            $baseCategoryIds = Category::query()
                ->where('id', '=', $categoryFilter, 'and')
                ->orWhere('name', 'like', $categoryFilter)
                ->pluck('id');
                
            // Get all subcategory IDs for those categories
            $allCategoryIds = Category::query()
                ->whereIn('id', $baseCategoryIds, 'and', false)
                ->orWhereIn('parent_id', $baseCategoryIds)
                ->pluck('id');
                
            $query->whereIn('category_id', $allCategoryIds);
        }

        // Filter by harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        match($sort) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::query()->with('children')->whereNull('parent_id')->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'sizes'])->findOrFail($id);

        // Produk terkait (kategori sama, bukan produk ini)
        $relatedProducts = Product::query()
            ->where('category_id', '=', $product->category_id, 'and')
            ->where('id', '!=', $product->id, 'and')
            ->where('stock', '>', 0, 'and')
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function searchApi(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json(['products' => []]);
        }

        $products = Product::query()
            ->where('name', 'like', '%' . $query . '%', 'and')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 0, ',', '.'),
                    'url' => route('products.show', $product->id),
                    'image' => asset('storage/' . (is_array($product->images) ? ($product->images[0] ?? 'images/placeholder.png') : $product->images)),
                ];
            });

        return response()->json(['products' => $products]);
    }
}
