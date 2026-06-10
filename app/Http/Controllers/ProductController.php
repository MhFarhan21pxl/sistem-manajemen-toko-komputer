<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

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

    // 4. Menampilkan Form Edit (Update - View)
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // 5. Memproses Pembaruan Data di Database (Update - Action)
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Jika user mengunggah foto baru
        if ($request->hasFile('image')) {
            // Hapus foto lama dari penyimpanan jika ada
            if ($product->image) {
                Storage::delete('public/products/' . $product->image);
            }
            // Simpan foto baru
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 6. Menghapus Data Produk (Delete)
    public function destroy(Product $product)
    {
        // Hapus file gambar dari penyimpanan sebelum menghapus data di database
        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}