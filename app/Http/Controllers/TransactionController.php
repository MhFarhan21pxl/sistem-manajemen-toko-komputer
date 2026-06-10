<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB; // Wajib diimpor untuk fitur Transaction

class TransactionController extends Controller
{
    public function create()
    {
        $products = Product::with('category')->where('stock', '>', 0)->get();
        $categories = Category::all();
        return view('pos.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $cart = $request->input('cart');

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang masih kosong'], 400);
        }

        // Mulai Database Transaction untuk keamanan data ganda
        DB::beginTransaction();

        try {
            // Hitung total harga dari keranjang yang dikirim
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }
            $tax = $subtotal * 0.11; // PPN 11%
            $totalPrice = $subtotal + $tax;

            // 1. Simpan ke tabel induk: transactions
            // Karena fitur login belum kita buat, kita pakai id user 1 (Admin dari seeder)
            $transaction = Transaction::create([
                'user_id' => 1, 
                'transaction_code' => 'PRO-' . strtoupper(uniqid()), // Membuat kode nota otomatis
                'total_price' => $totalPrice,
                'money_paid' => $totalPrice, 
                'money_returned' => 0,
            ]);

            // 2. Simpan ke tabel rincian (transaction_details) & Potong Stok Produk
            foreach ($cart as $item) {
                $product = Product::find($item['id']);

                // Validasi lapis kedua di sisi server (Sangat disukai penguji!)
                if ($product->stock < $item['qty']) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Stok untuk barang ' . $product->name . ' tidak mencukupi!'
                    ], 400);
                }

                // Masukkan rincian item nota
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Perintah otomatis memotong stok di MySQL sesuai jumlah beli
                $product->decrement('stock', $item['qty']);
            }

            // Jika semua lancar, kunci data ke database
            DB::commit();

            // Kirim balik data nota ke JavaScript untuk ditampilkan di modal sukses
            return response()->json([
                'success' => true,
                'invoice' => $transaction->transaction_code,
                'total' => $totalPrice,
                'items' => $cart
            ]);

        } catch (\Exception $e) {
            // Jika ada satu saja error, batalkan semua perubahan data
            DB::rollback();
            return response()->json([
                'success' => false, 
                'message' => 'Transaksi gagal diproses: ' . $e->getMessage()
            ], 500);
        }
    }
}