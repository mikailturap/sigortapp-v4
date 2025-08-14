@extends('layouts.app')

@section('title', 'Gelir Yönetimi - Raporlar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-chart-bar me-2"></i>Gelir Yönetimi - Raporlar
                </h1>
                <div>
                    <a href="{{ route('revenue.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Geri
                    </a>
                    <button class="btn btn-outline-success" onclick="exportReport()">
                        <i class="fas fa-file-excel me-1"></i>Excel Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarih Filtreleri -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('revenue.reports') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Filtrele
                    </button>
                    <a href="{{ route('revenue.reports') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Günlük Gelir Grafiği -->
        <div class="col-12 col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Günlük Gelir Grafiği
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Özet İstatistikler -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Özet Bilgiler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Toplam Gelir:</span>
                        <span class="fw-semibold text-success">₺{{ number_format($revenueReport->sum('daily_revenue'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Toplam Poliçe:</span>
                        <span class="fw-semibold text-primary">{{ $revenueReport->sum('policy_count') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Ortalama Günlük:</span>
                        <span class="fw-semibold text-info">₺{{ number_format($revenueReport->avg('daily_revenue'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Rapor Tarihi:</span>
                        <span class="fw-semibold">{{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sigorta Şirketine Göre Gelir -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Sigorta Şirketine Göre Gelir
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="companyRevenueChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Poliçe Türüne Göre Gelir -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-contract me-2"></i>Poliçe Türüne Göre Gelir
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="typeRevenueChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detaylı Tablo -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>Detaylı Gelir Raporu
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tarih</th>
                            <th>Günlük Gelir</th>
                            <th>Poliçe Sayısı</th>
                            <th>Ortalama Poliçe Geliri</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revenueReport as $report)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($report->date)->format('d.m.Y') }}</span>
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($report->date)->format('l') }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-success">₺{{ number_format($report->daily_revenue, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $report->policy_count }}</span>
                            </td>
                            <td>
                                <span class="text-muted">₺{{ number_format($report->daily_revenue / $report->policy_count, 2) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Seçilen tarih aralığında veri bulunamadı</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Günlük Gelir Grafiği
    const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    const dailyRevenueChart = new Chart(dailyRevenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueReport->pluck('date')->map(function($date) { 
                return \Carbon\Carbon::parse($date)->format('d.m'); 
            })) !!},
            datasets: [{
                label: 'Günlük Gelir (₺)',
                data: {!! json_encode($revenueReport->pluck('daily_revenue')) !!},
                borderColor: '#276495',
                backgroundColor: 'rgba(39, 100, 149, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₺' + value.toLocaleString('tr-TR');
                        }
                    }
                }
            }
        }
    });

    // Sigorta Şirketine Göre Gelir Grafiği
    const companyRevenueCtx = document.getElementById('companyRevenueChart').getContext('2d');
    const companyRevenueChart = new Chart(companyRevenueCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($companyRevenue->pluck('policy_company')) !!},
            datasets: [{
                data: {!! json_encode($companyRevenue->pluck('total_revenue')) !!},
                backgroundColor: [
                    '#276495', '#dc3545', '#198754', '#ffc107', '#6f42c1',
                    '#fd7e14', '#20c997', '#e83e8c', '#6c757d', '#0dcaf0'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Poliçe Türüne Göre Gelir Grafiği
    const typeRevenueCtx = document.getElementById('typeRevenueChart').getContext('2d');
    const typeRevenueChart = new Chart(typeRevenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($typeRevenue->pluck('policy_type')) !!},
            datasets: [{
                label: 'Gelir (₺)',
                data: {!! json_encode($typeRevenue->pluck('total_revenue')) !!},
                backgroundColor: 'rgba(39, 100, 149, 0.8)',
                borderColor: '#276495',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₺' + value.toLocaleString('tr-TR');
                        }
                    }
                }
            }
        }
    });
});

function exportReport() {
    // Excel export işlemi
    alert('Excel export özelliği yakında eklenecek');
}
</script>
@endpush
@endsection
