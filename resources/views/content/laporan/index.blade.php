@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Laporan Penjualan</h1>

    <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="filter" class="form-control" onchange="this.form.submit()">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua Data</option>
                    <option value="weekly" {{ $filter === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $filter === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $filter === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Ranking Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Peringkat Produk Terlaris</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Terjual</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productRankings as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->total_quantity, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Pendapatan</div>
                <div class="card-body">
                    <h2>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    @if($filter !== 'all')
                    <p class="text-muted">
                        Tahun Lalu: Rp {{ number_format($previousTotalRevenue, 0, ',', '.') }}
                        ({{ $totalRevenue > $previousTotalRevenue ? '+' : '' }}{{ number_format(($totalRevenue - $previousTotalRevenue) / $previousTotalRevenue * 100, 2) }}%)
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Grafik Pendapatan Harian</div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Grafik Pendapatan Bulanan</div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Grafik Penjualan per Produk</div>
                <div class="card-body">
                    <canvas id="productSalesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Grafik Pendapatan Tahunan</div>
                <div class="card-body">
                    <canvas id="yearlyRevenueChart"></canvas>
                </div>
            </div>
        </div>
        @if($filter !== 'all')
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Grafik Pendapatan Tahun Lalu</div>
                <div class="card-body">
                    <canvas id="previousYearlyRevenueChart"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Daily Revenue Chart
        new Chart(document.getElementById('dailyRevenueChart'), {
            type: 'line',
            data: {
                labels: {!! $dailyRevenue->pluck('date')->toJson() !!},
                datasets: [{
                    label: 'Pendapatan Harian',
                    data: {!! $dailyRevenue->pluck('total_revenue')->toJson() !!},
                    borderColor: 'blue',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Monthly Revenue Chart
        new Chart(document.getElementById('monthlyRevenueChart'), {
            type: 'bar',
            data: {
                labels: {!! $monthlyRevenue->map(function($item) { 
                    return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT); 
                })->toJson() !!},
                datasets: [{
                    label: 'Pendapatan Bulanan',
                    data: {!! $monthlyRevenue->pluck('total_revenue')->toJson() !!},
                    backgroundColor: 'green'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Product Sales Chart
        new Chart(document.getElementById('productSalesChart'), {
            type: 'pie',
            data: {
                labels: {!! $productSales->pluck('name')->toJson() !!},
                datasets: [{
                    label: 'Penjualan per Produk',
                    data: {!! $productSales->pluck('total_quantity')->toJson() !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });

        // Yearly Revenue Chart
        new Chart(document.getElementById('yearlyRevenueChart'), {
            type: 'bar',
            data: {
                labels: {!! $yearlyRevenue->pluck('year')->toJson() !!},
                datasets: [{
                    label: 'Pendapatan Tahunan',
                    data: {!! $yearlyRevenue->pluck('total_revenue')->toJson() !!},
                    backgroundColor: 'purple'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        @if($filter !== 'all')
        new Chart(document.getElementById('previousYearlyRevenueChart'), {
            type: 'bar',
            data: {
                labels: {!! $previousYearlyRevenue->pluck('year')->toJson() !!},
                datasets: [{
                    label: 'Pendapatan Tahun Lalu',
                    data: {!! $previousYearlyRevenue->pluck('total_revenue')->toJson() !!},
                    backgroundColor: 'orange'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        @endif
    });
</script>