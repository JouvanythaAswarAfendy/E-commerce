<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 produk terbaru yang stoknya ada
        $featuredProducts = Product::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->limit(4)
            ->get();

        return view('welcome', compact('featuredProducts'));
    }
}
