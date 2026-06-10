<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // 1. Menampilkan Semua Produk (Read)
    public function index()
    {
        // Mengambil semua data produk beserta relasi kategorinya
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    // 2. Menampilkan Form Tambah Produk (Create)
    public function create()
    {
        // Mengambil data kategori untuk dipilih di dropdown form
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // 3. Memproses Penyimpanan Data ke Database
    public function store(Request $request)
    {
        // Validasi inputan form (Syarat Keamanan & Validasi)
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Logika Upload Gambar Produk jika ada file yang diunggah
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            // Simpan file asli ke folder storage/app/public/products
            $request->image->storeAs('public/products', $imageName);
            // Simpan nama filenya saja ke kolom database
            $data['image'] = $imageName;
        }

        // Simpan data produk baru menggunakan Eloquent ORM
        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }
}