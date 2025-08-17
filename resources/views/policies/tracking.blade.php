@extends('layouts.app')

@section('title', 'Poliçe Takip')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i data-lucide="radar" class="text-muted me-2" style="width: 24px; height: 24px;"></i>
                Poliçe Takip
            </h1>
            <p class="text-muted mb-0 mt-1 small">Yaklaşan yenilemeler, bugün sona erenler ve süresi geçmiş poliçeleri yönetin</p>
        </div>
        <a href="{{ route('policies.index') }}" class="btn btn-outline-secondary">
            <i data-lucide="list" class="me-2" style="width: 16px; height: 16px;"></i>
            Tüm Poliçeler
        </a>
    </div>

    <!-- Pasifleri Gizle Toggle -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="toggle-hide-inactive-tracking">
                    <label class="form-check-label text-secondary" for="toggle-hide-inactive-tracking">Pasifleri gizle</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Sekmeli görünüm - Ayarlar sayfası ile uyumlu stil -->
    <style>
        .nav-tabs-line {
            background: #ffffff !important;
            border-radius: 12px !important;
            padding: 12px !important;
            border: 1px solid #e9ecef !important;
            display: flex !important;
            gap: 16px !important;
            margin: 0 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06) !important;
        }

        .nav-tabs-line .nav-item {
            flex: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .nav-tabs-line .nav-link {
            border: none !important;
            border-radius: 8px !important;
            color: #6c757d !important;
            padding: 1.25rem 1.5rem !important;
            font-weight: 500 !important;
            font-size: 15px !important;
            text-align: center !important;
            position: relative !important;
            transition: all 0.2s ease !important;
            background: #f8f9fa !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 10px !important;
            min-height: 90px !important;
            justify-content: center !important;
            border: 1px solid transparent !important;
            width: 100% !important;
        }

        .nav-tabs-line .nav-link:hover {
            color: #495057 !important;
            background: #e9ecef !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(39, 100, 149, 0.15) !important;
        }

        .nav-tabs-line .nav-link.active {
            color: #ffffff !important;
            background: #276495 !important;
            transform: none !important;
            box-shadow: 0 2px 8px rgba(39, 100, 149, 0.2) !important;
            border-color: #276495 !important;
            font-weight: 600 !important;
            font-size: 15px !important;
        }

        .nav-tabs-line .nav-link i {
            color: inherit !important;
            opacity: 0.8 !important;
            transition: all 0.2s ease !important;
            font-size: 18px !important;
            margin-bottom: 6px !important;
        }

        .nav-tabs-line .nav-link.active i {
            color: #ffffff !important;
            transform: none !important;
        }

        /* Tracking özel: ikon + başlık ilk satırda, badge ikinci satırda */
        .nav-tabs-line .nav-link .tab-title i {
            margin-bottom: 0 !important;
        }

        /* Müşteri ismi linki hover efekti */
        h6 a:hover {
            color: #0d6efd !important;
            text-decoration: underline !important;
        }
    </style>

    <ul class="nav nav-tabs nav-tabs-line mb-3" id="trackingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="today-tab" data-bs-toggle="tab" data-bs-target="#tab-today" type="button" role="tab">
                <div class="tab-title d-flex align-items-center gap-2">
                    <i data-lucide="sun" style="width: 18px; height: 18px;"></i>
                    <span>Bugün</span>
                </div>
                <span class="badge bg-light text-dark" id="badge-today">{{ $expiringToday->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#tab-upcoming" type="button" role="tab">
                <div class="tab-title d-flex align-items-center gap-2">
                    <i data-lucide="calendar-range" style="width: 18px; height: 18px;"></i>
                    <span>Yaklaşan ({{ $window }}g)</span>
                </div>
                <span class="badge bg-light text-dark" id="badge-upcoming">{{ $upcomingRenewals->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="expired-tab" data-bs-toggle="tab" data-bs-target="#tab-expired" type="button" role="tab">
                <div class="tab-title d-flex align-items-center gap-2">
                    <i data-lucide="alert-octagon" style="width: 18px; height: 18px;"></i>
                    <span>Süresi Geçen</span>
                </div>
                <span class="badge bg-light text-dark" id="badge-expired">{{ $expiredPolicies->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#tab-active" type="button" role="tab">
                <div class="tab-title d-flex align-items-center gap-2">
                    <i data-lucide="layers" style="width: 18px; height: 18px;"></i>
                    <span>Tüm Poliçeler</span>
                </div>
                <span class="badge bg-light text-dark" id="badge-active">{{ isset($activePolicies) ? $activePolicies->count() : $totalActivePolicies }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="trackingTabsContent">
        <!-- Bugün -->
        <div class="tab-pane fade show active" id="tab-today" role="tabpanel" aria-labelledby="today-tab">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <i data-lucide="sun" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Bugün Sona Eren Poliçeler</h5>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('policies.tracking.export', ['type' => 'today']) }}" class="btn btn-sm btn-outline-success">
                                <i data-lucide="download" class="me-1" style="width: 14px; height: 14px;"></i>
                                Excel İndir
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="today-list">
                    @forelse ($expiringToday as $policy)
                        <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-warning-subtle rounded border" data-status="{{ $policy->status }}">
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">
                                    <a href="{{ route('policies.show', $policy) }}" class="text-decoration-none" title="Poliçe detayını görüntüle">{{ $policy->customer_title }}</a>
                                </h6>
                                <small class="text-secondary">{{ $policy->policy_number }} - {{ $policy->policy_type }}</small>
                                <small class="text-secondary">{{ $policy->customer_phone }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Bugün</span>
                                <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                </a>
                                @php
                                    $daysLeft = 0;
                                    $waAllowed = $daysLeft <= $whatsappReminderDays && $daysLeft >= 0;
                                    $waText = urlencode("Sayın {$policy->customer_title}, {$policy->policy_number} numaralı poliçenizin bitiş tarihi olan " . optional($policy->end_date)->format('Y-m-d') . " yaklaşıyor. Yenileme için bize ulaşabilirsiniz.");
                                    $waPhone = preg_replace('/\\D+/', '', $policy->customer_phone ?? '');
                                @endphp
                                @if($waAllowed && $waPhone)
                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="btn btn-sm btn-success" title="WhatsApp ile Hatırlat">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                <form action="{{ route('policies.toggleStatus', $policy) }}" method="POST" class="ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $policy->status == 'aktif' ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Bugün sona eren poliçe bulunmamaktadır.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Yaklaşanlar -->
        <div class="tab-pane fade" id="tab-upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <i data-lucide="calendar-range" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Yaklaşan Yenilemeler</h5>
                        </div>
                        <form method="GET" action="{{ route('policies.tracking') }}" class="ms-auto d-flex align-items-center gap-2">
                            <label for="window" class="form-label mb-0 text-secondary small">Pencere:</label>
                            <select name="window" id="window" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 120px;">
                                @foreach ($allowedWindows as $opt)
                                    <option value="{{ $opt }}" {{ $window == $opt ? 'selected' : '' }}>{{ $opt }} gün</option>
                                @endforeach
                            </select>
                        </form>
                        <div class="ms-2">
                            <a href="{{ route('policies.tracking.export', ['type' => 'upcoming', 'window' => $window]) }}" class="btn btn-sm btn-outline-success">
                                <i data-lucide="download" class="me-1" style="width: 14px; height: 14px;"></i>
                                Excel İndir
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="upcoming-list">
                    @forelse ($upcomingRenewals as $policy)
                        <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-warning-subtle rounded border" data-status="{{ $policy->status }}">
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">
                                    <a href="{{ route('policies.show', $policy) }}" class="text-decoration-none" title="Poliçe detayını görüntüle">{{ $policy->customer_title }}</a>
                                </h6>
                                <small class="text-secondary">{{ $policy->policy_number }} - {{ $policy->policy_type }}</small>
                                <small class="text-secondary">{{ $policy->customer_phone }}</small>
                            </div>
                            @php
                                $daysUntil = now()->startOfDay()->diffInDays($policy->end_date->startOfDay(), false);
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ $daysUntil }} gün kaldı</span>
                                <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                </a>
                                @php
                                    $waAllowed = $daysUntil <= $whatsappReminderDays && $daysUntil >= 0;
                                    $waText = urlencode("Sayın {$policy->customer_title}, {$policy->policy_number} numaralı poliçenizin bitiş tarihi olan " . optional($policy->end_date)->format('Y-m-d') . " yaklaşıyor. Yenileme için bize ulaşabilirsiniz.");
                                    $waPhone = preg_replace('/\\D+/', '', $policy->customer_phone ?? '');
                                @endphp
                                @if($waAllowed && $waPhone)
                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="btn btn-sm btn-success" title="WhatsApp ile Hatırlat">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                <form action="{{ route('policies.toggleStatus', $policy) }}" method="POST" class="ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $policy->status == 'aktif' ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Yaklaşan yenileme bulunmamaktadır.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Süresi Geçmişler -->
        <div class="tab-pane fade" id="tab-expired" role="tabpanel" aria-labelledby="expired-tab">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <i data-lucide="alert-octagon" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Süresi Geçmiş Poliçeler</h5>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('policies.tracking.export', ['type' => 'expired']) }}" class="btn btn-sm btn-outline-success">
                                <i data-lucide="download" class="me-1" style="width: 14px; height: 14px;"></i>
                                Excel İndir
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="expired-list">
                    @forelse ($expiredPolicies as $policy)
                        <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-danger-subtle rounded border" data-status="{{ $policy->status }}">
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">
                                    <a href="{{ route('policies.show', $policy) }}" class="text-decoration-none" title="Poliçe detayını görüntüle">{{ $policy->customer_title }}</a>
                                </h6>
                                <small class="text-secondary">{{ $policy->policy_number }} - {{ $policy->policy_type }}</small>
                                <small class="text-secondary">{{ $policy->customer_phone }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $daysLeft = now()->startOfDay()->diffInDays($policy->end_date->startOfDay(), false);
                                @endphp
                                @if($daysLeft < 0)
                                    <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Süresi Geçti</span>
                                @elseif($daysLeft === 0)
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Bugün</span>
                                @else
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ $daysLeft }} gün kaldı</span>
                                @endif
                                <a href="{{ route('policies.show', $policy) }}" class="btn btn-sm btn-outline-secondary" title="Detay">
                                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                </a>
                                <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                </a>
                                @php
                                    $daysLeft = now()->startOfDay()->diffInDays($policy->end_date->startOfDay(), false);
                                    $waAllowed = $daysLeft <= $whatsappReminderDays && $daysLeft >= 0;
                                    $waText = urlencode("Sayın {$policy->customer_title}, {$policy->policy_number} numaralı poliçenizin bitiş tarihi olan " . optional($policy->end_date)->format('Y-m-d') . " yaklaşıyor. Yenileme için bize ulaşabilirsiniz.");
                                    $waPhone = preg_replace('/\\D+/', '', $policy->customer_phone ?? '');
                                @endphp
                                @if($waAllowed && $waPhone)
                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="btn btn-sm btn-success" title="WhatsApp ile Hatırlat">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                <form action="{{ route('policies.toggleStatus', $policy) }}" method="POST" class="ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $policy->status == 'aktif' ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Süresi geçmiş poliçe bulunmamaktadır.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tüm Poliçeler -->
        <div class="tab-pane fade" id="tab-active" role="tabpanel" aria-labelledby="active-tab">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <i data-lucide="layers" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Tüm Poliçeler</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="active-list">
                    @forelse ($activePolicies as $policy)
                        <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded border" data-status="{{ $policy->status }}">
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">
                                    <a href="{{ route('policies.show', $policy) }}" class="text-decoration-none" title="Poliçe detayını görüntüle">{{ $policy->customer_title }}</a>
                                </h6>
                                <small class="text-secondary">{{ $policy->policy_number }} - {{ $policy->policy_type }}</small>
                                <small class="text-secondary">{{ $policy->customer_phone }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $today = now()->startOfDay();
                                    $end = $policy->end_date ? $policy->end_date->copy()->startOfDay() : null;
                                    $daysUntil = $end ? (int) floor(($end->getTimestamp() - $today->getTimestamp()) / 86400) : null;
                                @endphp
                                @if($daysUntil !== null)
                                    @if($daysUntil < 0)
                                        <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Süresi Geçti</span>
                                    @elseif($daysUntil === 0)
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Bugün</span>
                                    @elseif($daysUntil <= 10)
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ $daysUntil }} gün kaldı</span>
                                    @elseif($daysUntil <= 30)
                                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis">{{ $daysUntil }} gün kaldı</span>
                                    @else
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">{{ $daysUntil }} gün kaldı</span>
                                    @endif
                                @endif
                                @if($policy->status === 'aktif')
                                    <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Aktif</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Pasif</span>
                                @endif
                                <a href="{{ route('policies.show', $policy) }}" class="btn btn-sm btn-outline-secondary" title="Detay">
                                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                </a>
                                <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                </a>
                                <form action="{{ route('policies.toggleStatus', $policy) }}" method="POST" class="ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $policy->status == 'aktif' ? 'checked' : '' }}>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Poliçe bulunmamaktadır.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        (function() {
            function initTracking() {
                // Aktif sekmeyi yerel depoda tut ve geri yükle
                (function persistActiveTab() {
                    var tabs = document.querySelectorAll('#trackingTabs .nav-link');
                    tabs.forEach(function(tabBtn) {
                        tabBtn.addEventListener('shown.bs.tab', function(e) {
                            var target = e.target && e.target.getAttribute('data-bs-target');
                            if (target) {
                                localStorage.setItem('tracking_active_tab', target);
                            }
                        });
                    });
                    // URL'deki tab parametresini temizle (istenmiyor)
                    try {
                        var url = new URL(window.location.href);
                        if (url.searchParams.has('tab')) {
                            url.searchParams.delete('tab');
                            window.history.replaceState({}, '', url.toString());
                        }
                    } catch(_) {}
                    // Yalnızca localStorage'dan geri yükle
                    var savedTab = localStorage.getItem('tracking_active_tab');
                    if (savedTab) {
                        var toActivate = document.querySelector('#trackingTabs .nav-link[data-bs-target="' + savedTab + '"]');
                        if (toActivate && !toActivate.classList.contains('active')) {
                            toActivate.click();
                        }
                    }
                })();

                // Pasifleri gizle toggle - tracking sayfası
                var hideInactiveToggle = document.getElementById('toggle-hide-inactive-tracking');
                if (!hideInactiveToggle) return;
                var saved = localStorage.getItem('tracking_hide_inactive');
                if (saved === '1') hideInactiveToggle.checked = true;

                function countVisibleRows(containerId) {
                    var container = document.getElementById(containerId);
                    if (!container) return 0;
                    var rows = Array.prototype.slice.call(container.querySelectorAll('[data-status]'));
                    var count = 0;
                    rows.forEach(function(row) {
                        if (!row.classList.contains('d-none')) count++;
                    });
                    return count;
                }

                function updateTabBadges() {
                    var today = countVisibleRows('today-list');
                    var upcoming = countVisibleRows('upcoming-list');
                    var expired = countVisibleRows('expired-list');
                    var active = countVisibleRows('active-list');
                    var bToday = document.getElementById('badge-today'); if (bToday) bToday.textContent = today;
                    var bUpcoming = document.getElementById('badge-upcoming'); if (bUpcoming) bUpcoming.textContent = upcoming;
                    var bExpired = document.getElementById('badge-expired'); if (bExpired) bExpired.textContent = expired;
                    var bActive = document.getElementById('badge-active'); if (bActive) bActive.textContent = active;
                }

                function applyHideInactive() {
                    var hide = hideInactiveToggle.checked;
                    ['expired-list','today-list','upcoming-list','active-list'].forEach(function(id) {
                        var container = document.getElementById(id);
                        if (!container) return;
                        container.querySelectorAll('[data-status]')
                            .forEach(function(row) {
                                var status = (row.getAttribute('data-status') || '').trim().toLowerCase();
                                var shouldHide = hide && status === 'pasif';
                                row.classList.toggle('d-none', shouldHide);
                            });
                    });
                    updateTabBadges();
                }
                hideInactiveToggle.addEventListener('change', function() {
                    localStorage.setItem('tracking_hide_inactive', this.checked ? '1' : '0');
                    applyHideInactive();
                });
                applyHideInactive();

                // AJAX status toggle
                function bindAjaxToggles(context) {
                    var scope = context || document;
                    scope.querySelectorAll('form[action*="toggle-status"]').forEach(function(form) {
                        // Aynı forma birden fazla listener eklenmesini önle
                        if (form.__boundAjaxToggle) return;
                        form.__boundAjaxToggle = true;
                        var input = form.querySelector('input[type="checkbox"]');
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                },
                                body: new URLSearchParams(new FormData(form))
                            }).then(function(res) {
                                if (!res.ok) throw new Error('İstek başarısız');
                                showToast('Durum güncellendi');
                                var row = form.closest('[data-status]');
                                if (row) {
                                    var isActive = input.checked;
                                    row.setAttribute('data-status', isActive ? 'aktif' : 'pasif');
                                    var parent = row.parentElement;
                                    if (parent && parent.id === 'active-list' && !isActive) {
                                        row.classList.add('d-none');
                                    }
                                    applyHideInactive();
                                }
                                updateTabBadges();
                            }).catch(function(){
                                input.checked = !input.checked;
                                showToast('Güncelleme başarısız', true);
                            });
                        });
                    });
                }
                bindAjaxToggles(document);

                // Yaklaşan penceresini AJAX ile güncelle
                (function bindUpcomingWindowAjax() {
                    var windowSelect = document.getElementById('window');
                    if (!windowSelect) return;
                    var form = windowSelect.closest('form');
                    if (!form) return;
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        var params = new URLSearchParams(new FormData(form));
                        var url = new URL(form.action || window.location.href, window.location.origin);
                        url.search = params.toString();
                        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(function(res){ return res.text(); })
                            .then(function(html){
                                var parser = new DOMParser();
                                var doc = parser.parseFromString(html, 'text/html');
                                var newUpcoming = doc.querySelector('#upcoming-list');
                                var newBadgeText = doc.querySelector('#badge-upcoming');
                                var currentUpcoming = document.getElementById('upcoming-list');
                                var currentBadge = document.getElementById('badge-upcoming');
                                if (newUpcoming && currentUpcoming) {
                                    currentUpcoming.innerHTML = newUpcoming.innerHTML;
                                    // Yeni DOM içindeki toggle formlarına tekrar AJAX bağları
                                    bindAjaxToggles(currentUpcoming);
                                    // İkonları yeniden oluştur
                                    if (window.lucide && typeof lucide.createIcons === 'function') {
                                        lucide.createIcons();
                                    }
                                }
                                if (newBadgeText && currentBadge) {
                                    currentBadge.textContent = newBadgeText.textContent;
                                }
                                // URL'yi güncelle, tab parametresi olmadan
                                try {
                                    var clean = new URL(url.toString());
                                    clean.searchParams.delete('tab');
                                    window.history.replaceState({}, '', clean.toString());
                                } catch(_) {}
                                applyHideInactive();
                                updateTabBadges();
                                showToast('Yaklaşan liste güncellendi');
                            })
                            .catch(function(){ showToast('Liste güncellenemedi', true); });
                    });
                })();

                // Basit Toast
                function showToast(message, isError) {
                    var toast = document.createElement('div');
                    toast.className = 'position-fixed top-0 end-0 m-3 alert ' + (isError ? 'alert-danger' : 'alert-success');
                    toast.style.zIndex = 1080;
                    toast.textContent = message;
                    document.body.appendChild(toast);
                    setTimeout(function(){ toast.remove(); }, 2000);
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTracking);
            } else {
                initTracking();
            }
        })();
    </script>
    @endpush
@endsection
