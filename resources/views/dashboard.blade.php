@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h4 mb-0">Dashboard</h2>
            <small class="text-muted">Genel görünüm ve hızlı aksiyonlar</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('policies.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="me-1"></i> Yeni Poliçe
            </a>
            <a href="{{ route('policies.tracking') }}" class="btn btn-outline-secondary">
                <i data-lucide="alarm-clock" class="me-1"></i> Poliçe Takip
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Toplam Poliçe</div>
                        <div class="fs-3 fw-semibold">{{ $totalPolicies }}</div>
                    </div>
                    <span class="badge rounded-circle bg-primary-subtle text-primary-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="file-text"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Aktif Poliçe</div>
                        <div class="fs-3 fw-semibold">{{ $activePolicies }}</div>
                    </div>
                    <span class="badge rounded-circle bg-success-subtle text-success-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="check-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Pasif Poliçe</div>
                        <div class="fs-3 fw-semibold">{{ $passivePolicies }}</div>
                    </div>
                    <span class="badge rounded-circle bg-danger-subtle text-danger-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="ban"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Bitişe Yakın</div>
                        <div class="fs-3 fw-semibold">{{ $expiringSoonPolicies }}</div>
                    </div>
                    <span class="badge rounded-circle bg-warning-subtle text-warning-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="hourglass"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Toplam Müşteri</div>
                        <div class="fs-3 fw-semibold">{{ $totalCustomers }}</div>
                    </div>
                    <span class="badge rounded-circle bg-info-subtle text-info-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="users"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Yeni Poliçe (30g)</div>
                        <div class="fs-3 fw-semibold">{{ $newPoliciesLast30Days }}</div>
                    </div>
                    <span class="badge rounded-circle bg-secondary-subtle text-secondary-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="sparkles"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Toplam Gelir</div>
                        <div class="fs-3 fw-semibold text-success">₺{{ number_format($totalRevenue ?? 0, 0) }}</div>
                    </div>
                    <span class="badge rounded-circle bg-success-subtle text-success-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="dollar-sign"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xxl-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Bekleyen Gelir</div>
                        <div class="fs-3 fw-semibold text-warning">₺{{ number_format($pendingRevenue ?? 0, 0) }}</div>
                    </div>
                    <span class="badge rounded-circle bg-warning-subtle text-warning-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="clock"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Poliçe Türü Dağılımı</div>
                <div class="card-body">
                    <canvas id="policyTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Durum Dağılımı</div>
                <div class="card-body">
                    <canvas id="statusDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Son 12 Ayda Oluşturulan Poliçeler</div>
                <div class="card-body">
                    <canvas id="policiesLast12MonthsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Sigorta Şirketleri Dağılımı</div>
                <div class="card-body">
                    <canvas id="insuranceCompaniesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function() {
            var policyTypeDistribution = <?php echo json_encode($policyTypeDistribution ?? []); ?>;
            var policiesLast12MonthsData = <?php echo json_encode($policiesLast12Months ?? []); ?>;
            var activePoliciesCount = <?php echo json_encode($activePolicies ?? 0); ?>;
            var passivePoliciesCount = <?php echo json_encode($passivePolicies ?? 0); ?>;
            var insuranceCompaniesDistribution = <?php echo json_encode($insuranceCompaniesDistribution ?? []); ?>;

            // Poliçe Türü Dağılımı
            const policyTypeCtx = document.getElementById('policyTypeChart').getContext('2d');
            new Chart(policyTypeCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(policyTypeDistribution),
                    datasets: [{
                        label: 'Poliçe Sayısı',
                        data: Object.values(policyTypeDistribution),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Durum Dağılımı (Aktif/Pasif)
            const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Pasif'],
                    datasets: [{
                        data: [activePoliciesCount, passivePoliciesCount],
                        backgroundColor: ['rgba(25, 135, 84, 0.6)', 'rgba(220, 53, 69, 0.6)'],
                        borderColor: ['rgba(25, 135, 84, 1)', 'rgba(220, 53, 69, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // Son 12 Ay - Daha anlaşılır grafik
            const monthsCtx = document.getElementById('policiesLast12MonthsChart').getContext('2d');
            const monthNames = ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
            const labels = [];
            const data = [];
            
            // Son 12 ayı doğru sırayla hazırla
            for (let i = 11; i >= 0; i--) {
                const d = new Date();
                d.setMonth(d.getMonth() - i);
                labels.push(monthNames[d.getMonth()]);
                data.push(policiesLast12MonthsData[d.getMonth() + 1] || 0);
            }
            
            new Chart(monthsCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Oluşturulan Poliçeler',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.3)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    return context.parsed.y + ' poliçe oluşturuldu';
                                }
                            }
                        },
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    size: 12
                                },
                                callback: function(value) {
                                    return value + ' poliçe';
                                }
                            }
                        }
                    }
                }
            });

            // Sigorta Şirketleri Dağılımı
            const companiesCtx = document.getElementById('insuranceCompaniesChart').getContext('2d');
            const companyLabels = Object.keys(insuranceCompaniesDistribution);
            const companyData = Object.values(insuranceCompaniesDistribution);
            
            // Renk paleti oluştur
            const colors = [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)',
                'rgba(199, 199, 199, 0.8)',
                'rgba(83, 102, 255, 0.8)',
                'rgba(78, 252, 3, 0.8)',
                'rgba(252, 3, 244, 0.8)'
            ];

            new Chart(companiesCtx, {
                type: 'doughnut',
                data: {
                    labels: companyLabels,
                    datasets: [{
                        data: companyData,
                        backgroundColor: colors.slice(0, companyLabels.length),
                        borderColor: '#fff',
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    return context.parsed + ' poliçe';
                                }
                            }
                        }
                    }
                }
            });
        });

        // Dashboard Veri Gizleme Sistemi
        let isDataHidden = false;
        let privacySettings = null;

        // Ayarları yükle
        async function loadPrivacySettings() {
            try {
                const response = await fetch('/api/dashboard-privacy-settings');
                if (response.ok) {
                    privacySettings = await response.json();
                    setupPrivacyHotkey();
                }
            } catch (error) {
                console.log('Privacy settings yüklenemedi, varsayılan ayarlar kullanılıyor');
                privacySettings = {
                    key_combination: 'Ctrl+Shift+P',
                    is_enabled: true
                };
                setupPrivacyHotkey();
            }
        }

        // Tuş kombinasyonu kurulumu
        function setupPrivacyHotkey() {
            if (!privacySettings || !privacySettings.is_enabled) return;

            const keys = privacySettings.key_combination.split('+');
            let pressedKeys = new Set();

            document.addEventListener('keydown', function(e) {
                // Ctrl, Shift, Alt tuşlarını kontrol et
                if (e.ctrlKey) pressedKeys.add('Ctrl');
                if (e.shiftKey) pressedKeys.add('Shift');
                if (e.altKey) pressedKeys.add('Alt');

                // Diğer tuşları ekle
                if (e.key !== 'Control' && e.key !== 'Shift' && e.key !== 'Alt') {
                    pressedKeys.add(e.key);
                }

                // Tuş kombinasyonunu kontrol et
                const allKeysPressed = keys.every(key => pressedKeys.has(key));
                if (allKeysPressed && keys.length === pressedKeys.size) {
                    toggleDataPrivacy();
                    e.preventDefault();
                }
            });

            document.addEventListener('keyup', function(e) {
                // Tuşları temizle
                if (e.key === 'Control') pressedKeys.delete('Ctrl');
                if (e.key === 'Shift') pressedKeys.delete('Shift');
                if (e.key === 'Alt') pressedKeys.delete('Alt');
                if (e.key !== 'Control' && e.key !== 'Shift' && e.key !== 'Alt') {
                    pressedKeys.delete(e.key);
                }
            });
        }

        // Veri gizleme/açma
        function toggleDataPrivacy() {
            isDataHidden = !isDataHidden;
            
            const numericElements = document.querySelectorAll('.fs-3, .h4, .h5, .h6, .badge');
            
            numericElements.forEach(element => {
                if (isDataHidden) {
                    // Veriyi gizle
                    element.style.filter = 'blur(8px)';
                    element.style.userSelect = 'none';
                    element.title = 'Veri gizlendi - ' + privacySettings.key_combination + ' ile açın';
                } else {
                    // Veriyi göster
                    element.style.filter = 'none';
                    element.style.userSelect = 'auto';
                    element.title = '';
                }
            });

            // Bildirim göster
            const notification = document.createElement('div');
            notification.className = `alert alert-${isDataHidden ? 'warning' : 'success'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i data-lucide="${isDataHidden ? 'eye-off' : 'eye'}" class="me-2" style="width: 16px; height: 16px;"></i>
                <strong>${isDataHidden ? 'Veriler Gizlendi' : 'Veriler Gösterildi'}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // 3 saniye sonra otomatik kapat
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Sayfa yüklendiğinde privacy ayarlarını yükle
        document.addEventListener('DOMContentLoaded', function() {
            loadPrivacySettings();
        });
    </script>
    @endpush
@endsection
