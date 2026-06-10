<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // 1. Menampilkan Halaman POS Kasir (Sudah dibuat sebelumnya)
    public function create()
    {
        $products = Product::with('category')->where('stock', '>', 0)->get();
        $categories = Category::all();
        return view('pos.index', compact('products', 'categories'));
    }

    // 2. Memproses Transaksi Belanja POS (Sudah dibuat sebelumnya)
    public function store(Request $request)
    {
        $cart = $request->input('cart');
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cart as $item) { $subtotal += $item['price'] * $item['qty']; }
            $tax = $subtotal * 0.11;
            $totalPrice = $subtotal + $tax;

            $transaction = Transaction::create([
                'user_id' => 1, 
                'transaction_code' => 'PRO-' . strtoupper(uniqid()),
                'total_price' => $totalPrice,
                'money_paid' => $totalPrice, 
                'money_returned' => 0,
            ]);

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if ($product->stock < $item['qty']) {
                    return response()->json(['success' => false, 'message' => 'Stok terbatas!'], 400);
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                $product->decrement('stock', $item['qty']);
            }

            DB::commit();
            return response()->json(['success' => true, 'invoice' => $transaction->transaction_code, 'total' => $totalPrice, 'items' => $cart]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==================== FITUR BARU: HALAMAN LAPORAN ====================

    // 3. Menampilkan Daftar Nota & Perhitungan Keuangan (Read Laporan)
    public function index(Request $request)
    {
        // Ambil semua parameter filter dari browser
        $search = $request->input('search');
        $sort = $request->input('sort', 'desc'); 
        $period = $request->input('period', 'all'); // Nilai: all, date, month, year
        
        // Parameter nilai spesifik dari filter kustom
        $dateVal = $request->input('date_val');   // Format: YYYY-MM-DD
        $monthVal = $request->input('month_val'); // Format: YYYY-MM
        $yearVal = $request->input('year_val');   // Format: YYYY

        $query = Transaction::query();

        // 1. FILTER PENCARIAN (Berdasarkan Kode Nota)
        if ($search) {
            $query->where('transaction_code', 'LIKE', '%' . $search . '%');
        }

        // 2. REVISI UTAMA: LOGIKA FILTER KUSTOM INTERAKTIF
        if ($period === 'date' && $dateVal) {
            // Filter tanggal spesifik harian
            $query->whereDate('created_at', $dateVal);
        } elseif ($period === 'month' && $monthVal) {
            // Membedah format YYYY-MM menjadi Tahun dan Bulan terpisah
            $parts = explode('-', $monthVal); // $parts[0] = Tahun, $parts[1] = Bulan
            $query->whereYear('created_at', $parts[0])
                  ->whereMonth('created_at', $parts[1]);
        } elseif ($period === 'year' && $yearVal) {
            // Filter tahun spesifik
            $query->whereYear('created_at', $yearVal);
        }

        // --- Hitung Statistik Keuangan Otomatis Berdasarkan Hasil Potongan Filter ---
        $totalTransactions = $query->count();
        $totalRevenue = $query->sum('total_price');
        $avgOrder = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // 3. SORTING URUTAN DATA
        if ($sort === 'asc') {
            $query->oldest(); 
        } else {
            $query->latest(); 
        }

        // Ambil data dengan paginasi (8 data per halaman)
        $transactions = $query->paginate(8)->withQueryString();

        return view('transactions.index', compact(
            'transactions', 
            'totalTransactions', 
            'totalRevenue', 
            'avgOrder',
            'search',
            'sort',
            'period',
            'dateVal',
            'monthVal',
            'yearVal'
        ));
    }

    // 4. Mengambil Detail Item di Dalam Nota via AJAX JSON
    public function show($id)
    {
        // Cari detail transaksi beserta nama produk di dalamnya
        $details = TransactionDetail::with('product')
                    ->where('transaction_id', $id)
                    ->get();

        return response()->json($details);
    }
}