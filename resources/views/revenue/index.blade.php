@extends('layouts.app')

@section('title', 'Gelir Yönetimi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i class="fas fa-chart-line text-muted me-2"></i>
                Gelir Yönetimi
            </h1>
            <p class="text-muted mb-0 mt-1 small">Poliçe gelirleri, müşteri cari hesapları ve maliyet analizi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('revenue.customer-accounts') }}" class="btn btn-outline-info">
                <i class="fas fa-users me-2"></i>Müşteri Cari
            </a>
            <a href="{{ route('revenue.payment-transactions') }}" class="btn btn-outline-success">
                <i class="fas fa-credit-card me-2"></i>Ödeme İşlemleri
            </a>
            <a href="{{ route('revenue.cost-analysis') }}" class="btn btn-outline-warning">
                <i class="fas fa-chart-pie me-2"></i>Maliyet Analizi
            </a>
        </div>
    </div>

    <!-- Gelir Özeti -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Toplam Gelir</div>
                        <div class="fs-3 fw-semibold text-success">₺{{ number_format($totalRevenue, 2) }}</div>
                    </div>
                    <span class="badge rounded-circle bg-success-subtle text-success-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-money-bill-wave"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Bekleyen Gelir</div>
                        <div class="fs-3 fw-semibold text-warning">₺{{ number_format($pendingRevenue, 2) }}</div>
                    </div>
                    <span class="badge rounded-circle bg-warning-subtle text-warning-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-clock"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Gecikmiş Gelir</div>
                        <div class="fs-3 fw-semibold text-danger">₺{{ number_format($overdueRevenue, 2) }}</div>
                    </div>
                    <span class="badge rounded-circle bg-danger-subtle text-danger-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Bu Ay Gelir</div>
                        <div class="fs-3 fw-semibold text-primary">₺{{ number_format($thisMonthRevenue, 2) }}</div>
                    </div>
                    <span class="badge rounded-circle bg-primary-subtle text-primary-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Müşteri Cari ve Ödeme Özeti -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Toplam Cari Bakiye</div>
                        <div class="fs-3 fw-semibold text-info">₺{{ number_format($totalCustomerBalance, 2) }}</div>
                    </div>
                    <span class="badge rounded-circle bg-info-subtle text-info-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-address-book"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Gecikmiş Müşteri</div>
                        <div class="fs-3 fw-semibold text-danger">{{ $overdueCustomers }}</div>
                    </div>
                    <span class="badge rounded-circle bg-danger-subtle text-danger-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-user-times"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Yüksek Risk</div>
                        <div class="fs-3 fw-semibold text-warning">{{ $highRiskCustomers }}</div>
                    </div>
                    <span class="badge rounded-circle bg-warning-subtle text-warning-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-shield-alt"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Başarılı İşlem</div>
                        <div class="fs-3 fw-semibold text-success">{{ $successfulTransactions }}</div>
                    </div>
                    <span class="badge rounded-circle bg-success-subtle text-success-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Aylık Gelir Grafiği -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Aylık Gelir Grafiği ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Ödeme Durumu Dağılımı -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Ödeme Durumu</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Müşteri Risk Dağılımı -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Müşteri Risk Dağılımı</h5>
                </div>
                <div class="card-body">
                    <canvas id="customerRiskChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Ödeme Yöntemi Analizi -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Ödeme Yöntemi Analizi</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gecikmiş Ödemeler ve Sona Erecek Poliçeler -->
    <div class="row g-4 mt-4">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Gecikmiş Ödemeler</h5>
                </div>
                <div class="card-body">
                    @if($overduePolicies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Müşteri</th>
                                        <th>Poliçe No</th>
                                        <th>Gecikme</th>
                                        <th>Tutar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overduePolicies->take(5) as $policy)
                                        <tr>
                                            <td>{{ $policy->customer_title }}</td>
                                            <td>{{ $policy->policy_number }}</td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    {{ $policy->getDaysOverdue() }} gün
                                                </span>
                                            </td>
                                            <td>₺{{ number_format($policy->total_amount ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($overduePolicies->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('revenue.policies') }}" class="btn btn-sm btn-outline-primary">
                                    Tümünü Görüntüle ({{ $overduePolicies->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <p>Gecikmiş ödeme bulunmuyor</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Bu Ay Sona Erecek Poliçeler</h5>
                </div>
                <div class="card-body">
                    @if($expiringThisMonth->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Müşteri</th>
                                        <th>Poliçe No</th>
                                        <th>Bitiş Tarihi</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiringThisMonth->take(5) as $policy)
                                        <tr>
                                            <td>{{ $policy->customer_title }}</td>
                                            <td>{{ $policy->policy_number }}</td>
                                            <td>{{ $policy->end_date->format('d.m.Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $policy->getPaymentStatusColor() }}">
                                                    {{ $policy->getPaymentStatusText() }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($expiringThisMonth->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('revenue.policies') }}" class="btn btn-sm btn-outline-primary">
                                    Tümünü Görüntüle ({{ $expiringThisMonth->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-check fa-2x mb-2"></i>
                            <p>Bu ay sona erecek poliçe bulunmuyor</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aylık Gelir Grafiği
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyData = {!! json_encode($monthlyRevenue) !!};
    
    const monthNames = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 
                       'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    
    const monthlyLabels = monthlyData.map(item => monthNames[item.month - 1]);
    const monthlyValues = monthlyData.map(item => item.revenue);

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Aylık Gelir (₺)',
                data: monthlyValues,
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
                            return '₺' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Ödeme Durumu Dağılımı
    const statusCtx = document.getElementById('paymentStatusChart').getContext('2d');
    const statusData = {!! json_encode($paymentStatusCounts) !!};
    
    const statusLabels = statusData.map(item => {
        switch(item.payment_status) {
            case 'ödendi': return 'Ödendi';
            case 'bekliyor': return 'Bekliyor';
            case 'gecikmiş': return 'Gecikmiş';
            default: return item.payment_status;
        }
    });
    const statusValues = statusData.map(item => item.count);
    const statusColors = ['#28a745', '#ffc107', '#dc3545'];

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: statusColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Müşteri Risk Dağılımı
    const riskCtx = document.getElementById('customerRiskChart').getContext('2d');
    const riskData = {!! json_encode($customerRiskDistribution) !!};
    
    const riskLabels = riskData.map(item => {
        switch(item.risk_level) {
            case 'düşük': return 'Düşük Risk';
            case 'orta': return 'Orta Risk';
            case 'yüksek': return 'Yüksek Risk';
            case 'kritik': return 'Kritik Risk';
            default: return item.risk_level;
        }
    });
    const riskValues = riskData.map(item => item.count);
    const riskColors = ['#28a745', '#ffc107', '#fd7e14', '#dc3545'];

    new Chart(riskCtx, {
        type: 'bar',
        data: {
            labels: riskLabels,
            datasets: [{
                label: 'Müşteri Sayısı',
                data: riskValues,
                backgroundColor: riskColors,
                borderWidth: 0
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
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Ödeme Yöntemi Analizi
    const methodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const methodData = @json($paymentMethodAnalysis ?? []);
    
    if (methodData.length > 0) {
        const methodLabels = methodData.map(item => item.payment_method);
        const methodValues = methodData.map(item => item.total_amount);
        const methodColors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'];

        new Chart(methodCtx, {
            type: 'pie',
            data: {
                labels: methodLabels,
                datasets: [{
                    data: methodValues,
                    backgroundColor: methodColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endpush
