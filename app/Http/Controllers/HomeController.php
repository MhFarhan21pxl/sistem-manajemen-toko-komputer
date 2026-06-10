<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil semua produk terbaru beserta relasi kategorinya
        $products = Product::with('category')->latest()->get();
        return view('public.index', compact('products'));
    }
}