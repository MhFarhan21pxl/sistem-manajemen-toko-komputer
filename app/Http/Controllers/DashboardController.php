<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Paksa Carbon menggunakan bahasa Indonesia
        Carbon::setLocale('id');

        // 1. Hitung 4 Kotak Ringkasan Atas
        $totalSales = Transaction::sum('total_price');
        $totalOrders = Transaction::count();
        $lowStockCount = Product::where('stock', '<=', 5)->count(); 
        $avgOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // 2a. DATA GRAFIK MINGGUAN (7 Hari Terakhir)
        $labelsWeek = []; 
        $dataWeek = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labelsWeek[] = $date->translatedFormat('D'); // Sen, Sel, Rab...
            $dataWeek[] = Transaction::whereDate('created_at', $date->toDateString())->sum('total_price');
        }

        // 2b. DATA GRAFIK BULANAN (Dikelompokkan per Minggu dalam Bulan Ini)
        $labelsMonth = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $dataMonth = [
            Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereDay('created_at', '<=', 7)->sum('total_price'),
            Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereDay('created_at', '>', 7)->whereDay('created_at', '<=', 14)->sum('total_price'),
            Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereDay('created_at', '>', 14)->whereDay('created_at', '<=', 21)->sum('total_price'),
            Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereDay('created_at', '>', 21)->sum('total_price'),
        ];

        // 2c. DATA GRAFIK TAHUNAN (12 Bulan di Tahun Ini)
        $labelsYear = []; 
        $dataYear = [];
        for ($i = 1; $i <= 12; $i++) {
            $labelsYear[] = Carbon::create()->month($i)->translatedFormat('M'); // Jan, Feb, Mar...
            $dataYear[] = Transaction::whereYear('created_at', now()->year)->whereMonth('created_at', $i)->sum('total_price');
        }

        return view('dashboard.index', compact(
            'totalSales', 'totalOrders', 'lowStockCount', 'avgOrder',
            'labelsWeek', 'dataWeek', 'labelsMonth', 'dataMonth', 'labelsYear', 'dataYear'
        ));
    }
}
