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

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Bugün Sona Eriyor</div>
                        <div class="fs-3 fw-semibold">{{ $expiringToday->count() }}</div>
                    </div>
                    <span class="badge rounded-circle bg-danger-subtle text-danger-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="flag"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Yaklaşan Yenilemeler ({{ $window }} Gün)</div>
                        <div class="fs-3 fw-semibold">{{ $upcomingRenewals->count() }}</div>
                    </div>
                    <span class="badge rounded-circle bg-warning-subtle text-warning-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="clock"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Süresi Geçenler</div>
                        <div class="fs-3 fw-semibold">{{ $expiredPolicies->count() }}</div>
                    </div>
                    <span class="badge rounded-circle bg-secondary-subtle text-secondary-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="alert-triangle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Tüm Aktif Poliçeler</div>
                        <div class="fs-3 fw-semibold">{{ $totalActivePolicies }}</div>
                    </div>
                    <span class="badge rounded-circle bg-info-subtle text-info-emphasis p-3 d-inline-flex align-items-center justify-content-center">
                        <i data-lucide="layers"></i>
                    </span>
                </div>
            </div>
        </div>
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

    <!-- Süresi Geçmişler -->
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
                        <span class="fw-medium text-dark">{{ $policy->customer_title }}</span>
                        <small class="text-secondary">{{ $policy->policy_number }} - {{ $policy->policy_type }}</small>
                        <small class="text-secondary">{{ $policy->customer_phone }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Süresi Geçti</span>
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
                            $waPhone = preg_replace('/\D+/', '', $policy->customer_phone ?? '');
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

    <!-- Bugün -->
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
                        <span class="fw-medium text-dark">{{ $policy->customer_title }}</span>
                        <small class="text-secondary">{{ $policy->policy_number }} - {{ $policy->policy_type }}</small>
                        <small class="text-secondary">{{ $policy->customer_phone }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Bugün</span>
                        <a href="{{ route('policies.show', $policy) }}" class="btn btn-sm btn-outline-secondary" title="Detay">
                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                        </a>
                        <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                            <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                        </a>
                        @php
                            $daysLeft = 0;
                            $waAllowed = $daysLeft <= $whatsappReminderDays && $daysLeft >= 0;
                            $waText = urlencode("Sayın {$policy->customer_title}, {$policy->policy_number} numaralı poliçenizin bitiş tarihi olan " . optional($policy->end_date)->format('Y-m-d') . " yaklaşıyor. Yenileme için bize ulaşabilirsiniz.");
                            $waPhone = preg_replace('/\D+/', '', $policy->customer_phone ?? '');
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

    <!-- Yaklaşanlar -->
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
                        <span class="fw-medium text-dark">{{ $policy->customer_title }}</span>
                        <small class="text-secondary">{{ $policy->policy_number }} - {{ $policy->policy_type }}</small>
                        <small class="text-secondary">{{ $policy->customer_phone }}</small>
                    </div>
                    @php
                        $daysUntil = now()->startOfDay()->diffInDays($policy->end_date->startOfDay(), false);
                    @endphp
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ $daysUntil }} gün kaldı</span>
                        <a href="{{ route('policies.show', $policy) }}" class="btn btn-sm btn-outline-secondary" title="Detay">
                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                        </a>
                        <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                            <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                        </a>
                        @php
                            $waAllowed = $daysUntil <= $whatsappReminderDays && $daysUntil >= 0;
                            $waText = urlencode("Sayın {$policy->customer_title}, {$policy->policy_number} numaralı poliçenizin bitiş tarihi olan " . optional($policy->end_date)->format('Y-m-d') . " yaklaşıyor. Yenileme için bize ulaşabilirsiniz.");
                            $waPhone = preg_replace('/\D+/', '', $policy->customer_phone ?? '');
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
    @push('scripts')
    <script>
        (function() {
            function initTracking() {
                // Pasifleri gizle toggle - tracking sayfası
                var hideInactiveToggle = document.getElementById('toggle-hide-inactive-tracking');
                if (!hideInactiveToggle) return;
                var saved = localStorage.getItem('tracking_hide_inactive');
                if (saved === '1') hideInactiveToggle.checked = true;

                function applyHideInactive() {
                    var hide = hideInactiveToggle.checked;
                    ['expired-list','today-list','upcoming-list'].forEach(function(id) {
                        var container = document.getElementById(id);
                        if (!container) return;
                        container.querySelectorAll('[data-status]')
                            .forEach(function(row) {
                                var status = (row.getAttribute('data-status') || '').trim().toLowerCase();
                                row.style.display = (hide && status === 'pasif') ? 'none' : '';
                            });
                    });
                }
                hideInactiveToggle.addEventListener('change', function() {
                    localStorage.setItem('tracking_hide_inactive', this.checked ? '1' : '0');
                    applyHideInactive();
                });
                applyHideInactive();

                // AJAX status toggle
                document.querySelectorAll('form[action*="toggle-status"]').forEach(function(form) {
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
                            // Toast
                            showToast('Durum güncellendi');
                            // data-status güncelle
                            var row = form.closest('[data-status]');
                            if (row) {
                                row.setAttribute('data-status', input.checked ? 'aktif' : 'pasif');
                                applyHideInactive();
                            }
                        }).catch(function(){
                            input.checked = !input.checked; // geri al
                            showToast('Güncelleme başarısız', true);
                        });
                    });
                });

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
