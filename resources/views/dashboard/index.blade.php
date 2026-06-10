@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
  .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
  .page-title h1 { font-weight: 700; font-size: 1.7rem; letter-spacing: -0.4px; color: #0f172a; }
  .page-title p { color: #64748b; font-size: 0.85rem; font-weight: 500; margin-top: 0.2rem; }
  
  /* Badge Tanggal Diperbarui */
  .date-badge { background: white; border: 1px solid #e2e8f0; padding: 0.5rem 1.2rem; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; color: #334155; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }

  .cards-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.4rem; margin-bottom: 2rem; }
  .stat-card { background: white; border-radius: 18px; padding: 1.3rem 1.4rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; display: flex; align-items: flex-start; justify-content: space-between; transition: transform 0.2s; }
  .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
  .stat-info h4 { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #64748b; margin-bottom: 0.5rem; }
  .stat-value { font-weight: 800; font-size: 1.7rem; color: #0f172a; letter-spacing: -0.5px; }
  
  .stat-icon { background: #f1f5f9; border-radius: 14px; padding: 0.7rem; font-size: 1.3rem; display: flex; align-items: center; justify-content: center; width: 45px; height: 45px;}
  .stat-icon.blue { background: #dbeafe; color: #2563eb; }
  .stat-icon.emerald { background: #d1fae5; color: #059669; }
  .stat-icon.amber { background: #fef3c7; color: #d97706; }

  /* Kartu Grafik & Fitur Tab Baru */
  .chart-card { background: white; border-radius: 20px; padding: 1.5rem 1.6rem; box-shadow: 0 5px 18px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; display: flex; flex-direction: column; margin-bottom: 2rem;}
  .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; }
  .chart-header h3 { font-weight: 700; font-size: 1.1rem; color: #0f172a; }
  
  /* CSS Tab Grafik dari Referensi */
  .chart-tabs { display: flex; gap: 0.3rem; background: #f1f5f9; border-radius: 10px; padding: 0.3rem; }
  .chart-tab { padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; color: #64748b; background: transparent; border: none; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif;}
  .chart-tab.active { background: white; color: #0f172a; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

  .chart-container { position: relative; height: 300px; width: 100%; }
</style>

<main class="main-content">
  <div class="top-bar">
    <div class="page-title">
      <h1>Dashboard ProTech</h1>
      <p>Ringkasan performa penjualan dan status gudang toko Anda</p>
    </div>
    <div class="date-badge">
      <i class="fas fa-calendar-alt" style="color: #2563eb;"></i> 
      {{ now()->translatedFormat('d F Y') }}
    </div>
  </div>

  <div class="cards-row">
    <div class="stat-card">
      <div class="stat-info">
        <h4>Total Omzet</h4>
        <div class="stat-value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
      </div>
      <div class="stat-icon blue"><i class="fas fa-wallet"></i></div>
    </div>

    <div class="stat-card">
      <div class="stat-info">
        <h4>Total Pesanan</h4>
        <div class="stat-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
      </div>
      <div class="stat-icon emerald"><i class="fas fa-shopping-cart"></i></div>
    </div>

    <div class="stat-card" style="{{ $lowStockCount > 0 ? 'border-color: #fef08a; background: #fffbeb;' : '' }}">
      <div class="stat-info">
        <h4>Stok Menipis</h4>
        <div class="stat-value" style="{{ $lowStockCount > 0 ? 'color: #b45309;' : '' }}">{{ $lowStockCount }} Item</div>
      </div>
      <div class="stat-icon amber"><i class="fas fa-exclamation-triangle"></i></div>
    </div>

    <div class="stat-card">
      <div class="stat-info">
        <h4>Rata-rata Belanja</h4>
        <div class="stat-value">Rp {{ number_format($avgOrder, 0, ',', '.') }}</div>
      </div>
      <div class="stat-icon" style="background: #e0e7ff; color: #4338ca;"><i class="fas fa-chart-pie"></i></div>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-header">
      <h3 id="chartTitle">Grafik Pendapatan (7 Hari Terakhir)</h3>
      <div class="chart-tabs">
        <button class="chart-tab active" onclick="updateChart('week', this, 'Grafik Pendapatan (7 Hari Terakhir)')">Minggu</button>
        <button class="chart-tab" onclick="updateChart('month', this, 'Grafik Pendapatan (Bulan Ini)')">Bulan</button>
        <button class="chart-tab" onclick="updateChart('year', this, 'Grafik Pendapatan (Tahun Ini)')">Tahun</button>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="salesChart"></canvas>
    </div>
  </div>
</main>

<script>
  // Mengambil 3 paket data dari Controller
  const chartDataSets = {
    week: { labels: @json($labelsWeek), data: @json($dataWeek) },
    month: { labels: @json($labelsMonth), data: @json($dataMonth) },
    year: { labels: @json($labelsYear), data: @json($dataYear) }
  };

  // Inisialisasi Chart.js (Default: Mingguan)
  const ctx = document.getElementById('salesChart').getContext('2d');
  let salesChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: chartDataSets.week.labels,
      datasets: [{
        label: 'Omzet (Rp)',
        data: chartDataSets.week.data,
        borderColor: '#2563eb',
        backgroundColor: 'rgba(37, 99, 235, 0.08)',
        borderWidth: 3,
        pointBackgroundColor: '#ffffff',
        pointBorderColor: '#2563eb',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        tension: 0.4,
        fill: true,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0f172a',
          titleColor: '#f1f5f9',
          bodyColor: '#e2e8f0',
          cornerRadius: 8,
          padding: 12,
          callbacks: {
            label: function(context) {
              return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: '#f1f5f9', drawBorder: false },
          ticks: {
            callback: function(value) {
              if (value === 0) return 'Rp 0';
              return 'Rp ' + (value / 1000) + 'rb';
            },
            color: '#64748b',
            font: { family: 'Inter', weight: '500' }
          }
        },
        x: {
          grid: { display: false },
          ticks: { color: '#64748b', font: { family: 'Inter', weight: '500' } }
        }
      },
      interaction: { intersect: false, mode: 'index' },
    }
  });

  // Fungsi JavaScript untuk mengganti data grafik saat tombol diklik
  function updateChart(period, btnElement, newTitle) {
    // 1. Ubah teks judul grafik
    document.getElementById('chartTitle').innerText = newTitle;
    
    // 2. Geser gaya CSS tombol (hapus class active dari semua, lalu tambahkan ke yang diklik)
    document.querySelectorAll('.chart-tab').forEach(tab => tab.classList.remove('active'));
    btnElement.classList.add('active');

    // 3. Timpa data lama di Chart.js dengan data yang baru dipilih
    salesChart.data.labels = chartDataSets[period].labels;
    salesChart.data.datasets[0].data = chartDataSets[period].data;
    
    // 4. Perintahkan Chart.js untuk menggambar ulang dengan animasi transisi yang mulus
    salesChart.update();
  }
</script>
@endsection 