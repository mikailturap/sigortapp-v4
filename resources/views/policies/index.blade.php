@extends('layouts.app')

@section('title', 'Tüm Poliçeler')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i data-lucide="shield-check" class="text-muted me-2" style="width: 24px; height: 24px;"></i>
                Tüm Poliçeler
            </h1>
            <p class="text-muted mb-0 mt-1 small">Sigorta poliçelerinizi yönetin ve takip edin</p>
        </div>
        <a href="{{ route('policies.create') }}" class="btn btn-outline-primary">
            <i data-lucide="plus" class="me-2" style="width: 16px; height: 16px;"></i>
            Yeni Poliçe
        </a>
    </div>

    <!-- Modern Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="filter" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Filtreleme</h5>
                <button class="btn btn-link text-decoration-none ms-auto text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false">
                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                </button>
            </div>
        </div>
        <div class="collapse" id="filterCollapse">
            <div class="card-body pt-0">
                <form action="{{ route('policies.index') }}" method="GET" class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label for="customer_title" class="form-label fw-normal text-secondary">
                            <i data-lucide="user" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Müşteri Ünvan
                        </label>
                        <input type="text" class="form-control" id="customer_title" name="customer_title" 
                               value="{{ request('customer_title') }}" placeholder="Müşteri adı...">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="policy_number" class="form-label fw-normal text-secondary">
                            <i data-lucide="file-text" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Poliçe No
                        </label>
                        <input type="text" class="form-control" id="policy_number" name="policy_number" 
                               value="{{ request('policy_number') }}" placeholder="Poliçe numarası...">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="plate_or_other" class="form-label fw-normal text-secondary">
                            <i data-lucide="car" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Plaka/Diğer
                        </label>
                        <input type="text" class="form-control" id="plate_or_other" name="plate_or_other" 
                               value="{{ request('plate_or_other') }}" placeholder="Plaka veya diğer...">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="policy_type" class="form-label fw-normal text-secondary">
                            <i data-lucide="tag" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Poliçe Türü
                        </label>
                        <select class="form-select" id="policy_type" name="policy_type">
                            <option value="">Tüm Türler</option>
                            @foreach(\App\Models\PolicyType::active()->ordered()->get() as $policyType)
                                <option value="{{ $policyType->name }}" {{ request('policy_type') == $policyType->name ? 'selected' : '' }}>
                                    @switch($policyType->name)
                                        @case('Zorunlu Trafik Sigortası')
                                            🚗 {{ $policyType->name }}
                                            @break
                                        @case('Kasko')
                                            🛡️ {{ $policyType->name }}
                                            @break
                                        @case('DASK')
                                            🏠 {{ $policyType->name }}
                                            @break
                                        @case('Konut Sigortası')
                                            🏡 {{ $policyType->name }}
                                            @break
                                        @case('Sağlık Sigortası')
                                            🏥 {{ $policyType->name }}
                                            @break
                                        @case('TARSİM')
                                            🐄 {{ $policyType->name }}
                                            @break
                                        @default
                                            {{ $policyType->name }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="policy_company" class="form-label fw-normal text-secondary">
                            <i data-lucide="building" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Poliçe Şirketi
                        </label>
                        <select class="form-select" id="policy_company" name="policy_company">
                            <option value="">Tüm Şirketler</option>
                            @foreach(\App\Models\InsuranceCompany::active()->ordered()->get() as $company)
                                <option value="{{ $company->name }}" {{ request('policy_company') == $company->name ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="status" class="form-label fw-normal text-secondary">
                            <i data-lucide="check-circle" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Durum
                        </label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tüm Durumlar</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                            <option value="pasif" {{ request('status') == 'pasif' ? 'selected' : '' }}>❌ Pasif</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="start_date_from" class="form-label fw-normal text-secondary">
                            <i data-lucide="calendar" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Başlangıç Tarihi (Başlangıç)
                        </label>
                        <input type="date" class="form-control" id="start_date_from" name="start_date_from" value="{{ request('start_date_from') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="start_date_to" class="form-label fw-normal text-secondary">
                            <i data-lucide="calendar" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Başlangıç Tarihi (Bitiş)
                        </label>
                        <input type="date" class="form-control" id="start_date_to" name="start_date_to" value="{{ request('start_date_to') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="end_date_from" class="form-label fw-normal text-secondary">
                            <i data-lucide="calendar" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Bitiş Tarihi (Başlangıç)
                        </label>
                        <input type="date" class="form-control" id="end_date_from" name="end_date_from" value="{{ request('end_date_from') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="end_date_to" class="form-label fw-normal text-secondary">
                            <i data-lucide="calendar" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Bitiş Tarihi (Bitiş)
                        </label>
                        <input type="date" class="form-control" id="end_date_to" name="end_date_to" value="{{ request('end_date_to') }}">
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-3">
                                <i data-lucide="search" class="me-2" style="width: 16px; height: 16px;"></i>
                                Filtrele
                            </button>
                            <a href="{{ route('policies.index') }}" class="btn btn-outline-secondary px-3">
                                <i data-lucide="x" class="me-2" style="width: 16px; height: 16px;"></i>
                                Filtreyi Temizle
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <!-- Main Content Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <h6 class="mb-0 fw-normal text-secondary">
                        <i data-lucide="list" class="text-muted me-2" style="width: 16px; height: 16px;"></i>
                        Poliçe Listesi
                    </h6>
                    <span class="badge bg-light text-muted border">{{ $policies->count() }} poliçe</span>
                </div>
                
                <!-- Excel Export Button -->
                <div class="d-flex align-items-center gap-2">
                    <button id="export-excel" class="btn btn-outline-success btn-sm" style="font-size: 0.875rem;">
                        <i data-lucide="download" class="me-1" style="width: 14px; height: 14px;"></i>
                        Excel İndir
                    </button>

                    <!-- Pasifleri Gizle Toggle -->
                    <div class="form-check form-switch ms-3" title="Pasif poliçeleri gizle/göster">
                        <input class="form-check-input" type="checkbox" id="toggle-hide-inactive">
                        <label class="form-check-label text-secondary" for="toggle-hide-inactive">Pasifleri gizle</label>
                    </div>
                </div>
                

            </div>
        </div>

        <div class="card-body p-0">
            <!-- Bulk Actions Bar -->
            <div class="bg-light border-bottom p-3">
                <form id="bulk-action-form" action="{{ route('policies.bulkActions') }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Sol taraf - Bulk Actions -->
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select-all-policies">
                                                            <label class="form-check-label fw-normal text-secondary" for="select-all-policies">
                                Tümünü Seç
                            </label>
                            </div>
                            
                            <select name="action" id="bulk-action-select" class="form-select form-select-sm" style="width: 140px; font-size: 0.875rem;">
                                <option value="">Toplu İşlem Seç</option>
                                <option value="activate">✅ Aktif Yap</option>
                                <option value="deactivate">❌ Pasif Yap</option>
                                <option value="delete">🗑️ Sil</option>
                                <option value="download_pdf">📄 PDF İndir</option>
                            </select>
                            
                            <button type="submit" class="btn btn-outline-primary btn-sm" id="bulk-action-button" disabled style="height: 31px; font-size: 0.875rem;">
                                <i data-lucide="play" class="me-1" style="width: 14px; height: 14px;"></i>
                                Uygula
                            </button>
                            
                            <span class="text-muted ms-3" id="selected-count">0 poliçe seçildi</span>
                        </div>

                        <!-- Sağ taraf - Arama Kutusu -->
                        <div class="d-flex align-items-center">
                            <label for="custom-search" class="form-label me-2 mb-0 text-secondary fw-normal">
                                <i data-lucide="search" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                                Arama:
                            </label>
                            <div class="input-group input-group-sm" style="width: 350px;">
                                <input type="text" id="custom-search" class="form-control" 
                                       placeholder="Müşteri, poliçe no, plaka, tür, telefon... herhangi bir alanda arama yapın">
                                <button class="btn btn-outline-secondary" type="button" id="clear-search" title="Aramayı Temizle">
                                    <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- DataTable Section -->
            <div class="table-responsive">
                <table id="policies-datatable" class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-bold" style="width: 50px;">
                                <i data-lucide="square" class="text-muted" style="width: 16px; height: 16px;"></i>
                            </th>
                            <th class="border-0 text-center fw-normal text-secondary" style="width: 60px;">#</th>
                            <th class="border-0 fw-normal text-secondary">Müşteri Ünvan</th>
                            <th class="border-0 d-none">TC/Vergi No</th>
                            <th class="border-0 d-none">Müşteri Telefon</th>
                            <th class="border-0 d-none">Doğum Tarihi</th>
                            <th class="border-0 d-none">Adres</th>
                            <th class="border-0 fw-normal text-secondary">Poliçe Türü</th>
                            <th class="border-0 d-none">Poliçe Şirketi</th>
                            <th class="border-0 d-none">Poliçe No</th>
                            <th class="border-0 d-none">Plaka/Diğer</th>
                            <th class="border-0 d-none">Tanzim Tarihi</th>
                            <th class="border-0 fw-normal text-secondary">Bitiş Tarihi</th>
                            <th class="border-0 fw-normal text-secondary">Kalan Gün</th>
                            <th class="border-0 d-none">Bitiş Tarihi</th>
                            <th class="border-0 d-none">Belge Seri/Diğer/UAVT</th>
                            <th class="border-0 d-none">Sigorta Ettiren Ünvan</th>
                            <th class="border-0 d-none">Sigorta Ettiren Telefon</th>
                            <th class="border-0 d-none">TARSİM İşletme No</th>
                            <th class="border-0 d-none">TARSİM Hayvan No</th>
                            <th class="border-0 fw-normal text-secondary">Durum</th>
                            <th class="border-0 text-center fw-normal text-secondary" style="width: 120px;">
                                <i data-lucide="more-horizontal" class="text-muted" style="width: 16px; height: 16px;"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($policies as $policy)
                            <tr class="align-middle">
                                <td class="text-center">
                                    <input type="checkbox" name="selected_policies[]" value="{{ $policy->id }}" class="form-check-input">
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $policy->id }}</span>
                                </td>
                                <!-- Müşteri Ünvan (Export için sadece ünvan) -->
                                <td data-export="{{ $policy->customer_title }}">
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-dark">{{ $policy->customer_title }}</span>
                                        <small class="text-secondary fw-medium">
                                            <i data-lucide="credit-card" class="text-muted me-1" style="width: 12px; height: 12px;"></i>
                                            {{ $policy->customer_identity_number }}
                                        </small>
                                        <small class="text-secondary fw-medium">
                                            <i data-lucide="phone" class="text-muted me-1" style="width: 12px; height: 12px;"></i>
                                            {{ $policy->customer_phone }}
                                        </small>
                                    </div>
                                </td>
                                <td class="d-none">{{ $policy->customer_identity_number }}</td>
                                <td class="d-none">{{ $policy->customer_phone }}</td>
                                <td class="d-none">{{ $policy->customer_birth_date->format('d/m/Y') }}</td>
                                <td class="d-none">{{ $policy->customer_address }}</td>
                                <!-- Poliçe Türü (Export için sadece tür) -->
                                <td data-export="{{ $policy->policy_type }}">
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-dark">{{ $policy->policy_type }}</span>
                                        <span class="text-dark fw-medium fs-6">{{ $policy->policy_number }}</span>
                                        @if($policy->plate_or_other)
                                            <small class="text-secondary fw-medium">
                                                <i data-lucide="car" class="text-muted me-1" style="width: 12px; height: 12px;"></i>
                                                {{ $policy->plate_or_other }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td class="d-none">{{ $policy->policy_company ?: '' }}</td>
                                <td class="d-none">{{ $policy->policy_number }}</td>
                                <td class="d-none">{{ $policy->plate_or_other ?: '' }}</td>
                                <td class="d-none">{{ $policy->issue_date->format('d/m/Y') }}</td>
                                <!-- Bitiş Tarihi (siralama ve export için bitiş baz alınır) -->
                                <td data-export="{{ $policy->end_date->format('Y-m-d') }}" data-order="{{ $policy->end_date->format('Ymd') }}">
                                    <div class="d-flex flex-column">
                                        <small class="text-danger fw-medium">
                                            <i data-lucide="calendar-x" class="text-danger me-1" style="width: 12px; height: 16px;"></i>
                                            {{ $policy->end_date->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </td>
                                <!-- Kalan Gün -->
                                <td
                                    @php
                                        $today = \Carbon\Carbon::today();
                                        $endDateCarbon = $policy->end_date instanceof \Carbon\Carbon
                                            ? $policy->end_date->copy()->startOfDay()
                                            : \Carbon\Carbon::parse($policy->end_date)->startOfDay();
                                        $secondsDiff = $endDateCarbon->getTimestamp() - $today->getTimestamp();
                                        $daysUntilExpiration = (int) floor($secondsDiff / 86400);
                                    @endphp
                                    data-order="{{ $daysUntilExpiration }}"
                                    data-export="{{ $daysUntilExpiration }}"
                                >
                                    @if($daysUntilExpiration < 0)
                                        <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Süresi Geçti</span>
                                    @elseif($daysUntilExpiration === 0)
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Bugün</span>
                                    @elseif($daysUntilExpiration >= 1 && $daysUntilExpiration <= 10)
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ $daysUntilExpiration }} gün kaldı</span>
                                    @elseif($daysUntilExpiration >= 11 && $daysUntilExpiration <= 30)
                                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis">{{ $daysUntilExpiration }} gün kaldı</span>
                                    @else
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">{{ $daysUntilExpiration }} gün kaldı</span>
                                    @endif
                                </td>
                                <td class="d-none">{{ $policy->end_date->format('d/m/Y') }}</td>
                                <td class="d-none">{{ $policy->document_info ?: '' }}</td>
                                <td class="d-none">{{ $policy->insured_name ?: '' }}</td>
                                <td class="d-none">{{ $policy->insured_phone ?: '' }}</td>
                                <td class="d-none">{{ $policy->tarsim_business_number ?: '' }}</td>
                                <td class="d-none">{{ $policy->tarsim_animal_number ?: '' }}</td>
                                <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input policy-status-toggle" type="checkbox" role="switch" 
                                               id="policyStatusToggle{{ $policy->id }}" data-policy-id="{{ $policy->id }}" 
                                               {{ $policy->status == 'aktif' ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="policyStatusToggle{{ $policy->id }}">
                                            @if($policy->status == 'aktif')
                                                <span class="badge bg-light text-success border">Aktif</span>
                                            @else
                                                <span class="badge bg-light text-danger border">Pasif</span>
                                            @endif
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-secondary" title="Düzenle">
                                            <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <a href="{{ route('policies.show', $policy) }}" class="btn btn-sm btn-outline-secondary" title="Detay">
                                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <a href="{{ route('policies.downloadPdf', $policy) }}" class="btn btn-sm btn-outline-secondary" title="PDF İndir">
                                            <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="22" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>Henüz poliçe bulunmuyor</h5>
                                        <p>İlk poliçenizi oluşturmak için yukarıdaki "Yeni Poliçe" butonuna tıklayın.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Basit tablo stilleri */
        .table-hover-highlight {
            background-color: rgba(0, 0, 0, 0.05) !important;
        }
    </style>
    <script>
        // jQuery ve DataTable varlık kontrolü
        function checkLibraries() {
            console.log('Checking libraries...');
            console.log('jQuery available:', typeof jQuery !== 'undefined');
            console.log('$ available:', typeof $ !== 'undefined');
            console.log('DataTable available:', typeof $.fn !== 'undefined' && typeof $.fn.DataTable !== 'undefined');
            
            if (typeof jQuery === 'undefined') {
                console.error('jQuery not loaded!');
                return false;
            }
            
            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTable library not loaded!');
                return false;
            }
            
            return true;
        }
        
        // DOM hazır olduğunda çalıştır
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            
            // Kütüphaneleri kontrol et
            if (!checkLibraries()) {
                console.log('Retrying in 1 second...');
                setTimeout(function() {
                    if (checkLibraries()) {
                        initializeDataTable();
                    }
                }, 1000);
                return;
            }
            
            initializeDataTable();
        });
        
        // Global seçili poliçeleri saklamak için dizi
        window.selectedPolicies = window.selectedPolicies || [];
        
        // Global table değişkeni
        window.policiesTable = null;
        
        // Lucide ikonlarını yeniden başlatmak için yardımcı fonksiyon
        function reinitializeIcons() {
            if (typeof lucide !== 'undefined') {
                try {
                    lucide.createIcons();
                    console.log('Icons reinitialized successfully');
                } catch (error) {
                    console.warn('Failed to reinitialize icons:', error);
                }
            } else {
                console.warn('Lucide library not available');
            }
        }
        
        function initializeDataTable() {
            console.log('DataTable initialization started...');
            
            // Basic DataTable initialization
            window.policiesTable = $('#policies-datatable').DataTable({
                "paging": true,
                "pageLength": 100,
                "lengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "Tümü"]],
                "ordering": true,
                // Varsayılan sıralama: Bitiş Tarihi (kolon index 12) artan (en yakın bitiş en üstte)
                "order": [[12, 'asc']],
                "info": true,
                "searching": true,

                "responsive": true,
                "language": {
                    "decimal":        "",
                    "emptyTable":     "Tabloda veri bulunmuyor",
                    "info":           "_START_ - _END_ / _TOTAL_ kayıt gösteriliyor",
                    "infoEmpty":      "0 - 0 / 0 kayıt gösteriliyor",
                    "infoFiltered":   "(_MAX_ kayıt içerisinden filtrelendi)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Sayfa başına _MENU_ kayıt göster",
                    "loadingRecords": "Yükleniyor...",
                    "processing":     "İşleniyor...",
                    "search":         "Tabloda ara:",
                    "searchPlaceholder": "Arama yapın...",
                    "zeroRecords":    "Eşleşen kayıt bulunamadı",
                    "paginate": {
                        "first":      "İlk",
                        "last":       "Son",
                        "next":       "Sonraki",
                        "previous":   "Önceki"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [0, 21] },
                    { "className": "text-center", "targets": [0, 1, 20, 21] },
                    { "visible": false, "targets": [3, 4, 5, 6, 8, 9, 10, 11, 14, 15, 16, 17, 18, 19] },
                    { "searchable": true, "targets": "_all" } // Tüm sütunlarda arama yapılabilir
                ],
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel İndir',
                        className: 'btn btn-success btn-sm d-none', // Gizli buton, özel butonumuzla tetikleyeceğiz
                        filename: function() {
                            var date = new Date();
                            var isFiltered = window.policiesTable.search() !== '' || 
                                            window.policiesTable.columns().search().join('') !== '';
                            var prefix = isFiltered ? 'Poliçeler_Filtrelenmiş_' : 'Poliçeler_Tümü_';
                            return prefix + date.getFullYear() + '-' + 
                                   String(date.getMonth() + 1).padStart(2, '0') + '-' + 
                                   String(date.getDate()).padStart(2, '0');
                        },
                        title: 'Poliçe Listesi - Detaylı Rapor',
                        messageTop: function() {
                            var date = new Date().toLocaleDateString('tr-TR');
                            var isFiltered = window.policiesTable.search() !== '' || 
                                            window.policiesTable.columns().search().join('') !== '';
                            var status = isFiltered ? 'Filtrelenmiş' : 'Tüm';
                            return status + ' veriler - Rapor tarihi: ' + date;
                        },
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20], // Manuel olarak tüm data sütunları
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            },
                            format: {
                                body: function(data, row, column, node) {
                                    // Eğer data-export attribute'u varsa onu kullan
                                    var exportValue = $(node).attr('data-export');
                                    if (exportValue !== undefined) {
                                        return exportValue;
                                    }
                                    
                                    // HTML etiketlerini temizle ve basitleştir
                                    var cleanData = data.replace(/<[^>]*>/g, '').trim();
                                    // Çoklu boşlukları tek boşluk yap
                                    cleanData = cleanData.replace(/\s+/g, ' ');
                                    return cleanData;
                                }
                            }
                        }
                    }
                ],
                "dom": '<"row"<"col-sm-12"tr>>' +
                       '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"i><"col-sm-12 col-md-4"p>>' +
                       '<"row"<"col-sm-12"B>>',
                "initComplete": function() {
                    console.log('DataTable initialization completed');
                    
                    // DataTable'ın kendi arama kutusunu gizle
                    $('.dataTables_filter').hide();
                    
                    // Pasifleri gizle toggle'ını kontrol et ve filtreyi uygula
                    var hideInactive = $('#toggle-hide-inactive').prop('checked');
                    if (hideInactive) {
                        console.log('Hide inactive is checked, applying filter...');
                        this.draw();
                    }
                },
                "drawCallback": function() {
                    // Her sayfa değişiminde checkbox durumlarını güncelle
                    updateCheckboxStates();
                    
                    // Lucide ikonlarını yeniden başlat (yeni eklenen satırlar için)
                    reinitializeIcons();
                }
            });
            
            console.log('DataTable object:', window.policiesTable);

            // Pasifleri gizle filtresi: DataTables custom filter
            var hideInactiveFilter = function(settings, data, dataIndex, rowData, counter) {
                try {
                    // Sadece bizim tabloya uygulansın
                    if (settings.nTable && settings.nTable.id !== 'policies-datatable') return true;

                    var hideInactive = $('#toggle-hide-inactive').prop('checked');
                    if (!hideInactive) return true;

                    // Durum sütunu index: 20 (0-based)
                    // data[20] içinde badge ile 'Aktif' / 'Pasif' yazısı var; metni sadeleştir
                    var statusHtml = data[20] || '';
                    var statusText = statusHtml.replace(/<[^>]*>/g, '').toLowerCase();
                    // 'Pasif' içeriyorsa ve gizleme açık ise satırı gösterme
                    return statusText.indexOf('pasif') === -1;
                } catch (e) {
                    console.warn('Custom filter error:', e);
                    return true;
                }
            };

            // Filter'ı ekle
            $.fn.dataTable.ext.search.push(hideInactiveFilter);

            // Toggle başlangıç durumunu localStorage'dan yükle
            var savedHideInactive = localStorage.getItem('policies_hide_inactive');
            if (savedHideInactive === '1') {
                $('#toggle-hide-inactive').prop('checked', true);
                // Sayfa yüklendiğinde filtreyi uygula
                setTimeout(function() {
                    window.policiesTable.draw();
                }, 100);
            }

            // Toggle değiştikçe yeniden çiz ve değeri kaydet
            $('#toggle-hide-inactive').on('change', function() {
                var isChecked = $(this).prop('checked');
                localStorage.setItem('policies_hide_inactive', isChecked ? '1' : '0');
                console.log('Toggle changed:', isChecked);
                // Filtreyi uygula
                window.policiesTable.draw();
            });

            // Arama fonksiyonlarını DataTable initialize edildikten sonra bağla
            if (window.policiesTable) {
                console.log('Setting up search functionality...');
                
                // Custom arama kutusunu DataTable'a bağla - Tüm sütunlarda arama
                $('#custom-search').on('input', function() {
                    var searchValue = this.value;
                    console.log('Searching for:', searchValue);
                    
                    try {
                        // Global search - tüm sütunlarda arama yap
                        window.policiesTable.search(searchValue).draw();
                        
                        // Arama sonuçlarını göster
                        var info = window.policiesTable.page.info();
                        console.log('Search results:', info.recordsDisplay, 'of', info.recordsTotal, 'records');
                    } catch (error) {
                        console.error('Search error:', error);
                    }
                });
                
                // Enter tuşu ile arama
                $('#custom-search').on('keypress', function(e) {
                    if (e.which === 13) { // Enter tuşu
                        e.preventDefault();
                        try {
                            window.policiesTable.search(this.value).draw();
                        } catch (error) {
                            console.error('Enter search error:', error);
                        }
                    }
                });
                
                // Arama temizle butonu
                $('#clear-search').on('click', function() {
                    try {
                        $('#custom-search').val('');
                        window.policiesTable.search('').draw();
                        $('#custom-search').focus();
                        console.log('Search cleared');
                    } catch (error) {
                        console.error('Clear search error:', error);
                    }
                });
                
                // DataTable event'lerini bağla
                window.policiesTable.on('search.dt', function() {
                    setTimeout(function() {
                        reinitializeIcons();
                    }, 100);
                });
                
                console.log('Search functionality setup completed');
            } else {
                console.error('DataTable not available for search setup');
            }

            // Excel Export butonu - Akıllı filtreleme
            $('#export-excel').on('click', function() {
                try {
                    var isFiltered = window.policiesTable.search() !== '' || 
                                    window.policiesTable.columns().search().join('') !== '';
                    
                    console.log('Excel export triggered. Filtered:', isFiltered);
                    console.log('DataTable object:', window.policiesTable);
                    
                    // DataTable'ın Excel export butonunu tetikle
                    if (window.policiesTable && window.policiesTable.button) {
                        console.log('Triggering Excel export...');
                        window.policiesTable.button(0).trigger();
                    } else {
                        console.error('DataTable or button method not available');
                        alert('Excel export için DataTable hazır değil. Lütfen sayfayı yenileyin.');
                    }
                } catch (error) {
                    console.error('Excel export error:', error);
                    alert('Excel export hatası: ' + error.message);
                }
            });


            // Global selectedPolicies dizisini kullan
            var selectedPolicies = window.selectedPolicies;

            // Checkbox işlemleri
            $('#select-all-policies').on('change', function() {
                var isChecked = $(this).prop('checked');
                
                if (isChecked) {
                    // Tüm görünen checkboxları işaretle ve ID'leri diziye ekle
                    $('input[name="selected_policies[]"]').each(function() {
                        $(this).prop('checked', true);
                        var policyId = $(this).val();
                        if (selectedPolicies.indexOf(policyId) === -1) {
                            selectedPolicies.push(policyId);
                        }
                    });
                } else {
                    // Tüm görünen checkboxları temizle ve ID'leri diziden çıkar
                    $('input[name="selected_policies[]"]').each(function() {
                        $(this).prop('checked', false);
                        var policyId = $(this).val();
                        var index = selectedPolicies.indexOf(policyId);
                        if (index > -1) {
                            selectedPolicies.splice(index, 1);
                        }
                    });
                }
                updateBulkActionButton();
            });

            // Individual checkbox change
            $(document).on('change', 'input[name="selected_policies[]"]', function() {
                var policyId = $(this).val();
                var isChecked = $(this).prop('checked');
                
                if (isChecked) {
                    // ID'yi diziye ekle
                    if (selectedPolicies.indexOf(policyId) === -1) {
                        selectedPolicies.push(policyId);
                    }
                } else {
                    // ID'yi diziden çıkar
                    var index = selectedPolicies.indexOf(policyId);
                    if (index > -1) {
                        selectedPolicies.splice(index, 1);
                    }
                }
                
                updateBulkActionButton();
                updateSelectAllCheckbox();
            });

            $('#bulk-action-select').on('change', function() {
                updateBulkActionButton();
            });

            // Form submit olduğunda seçili ID'leri gönder
            $('#bulk-action-form').on('submit', function(e) {
                console.log('Form submit triggered');
                console.log('Selected policies:', selectedPolicies);
                console.log('Selected action:', $('#bulk-action-select').val());
                
                // Önce tüm eski hidden input'ları temizle
                $(this).find('input[name="selected_policies[]"]').remove();
                
                // Seçili policy ID'lerini hidden input olarak ekle
                selectedPolicies.forEach(function(policyId) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'selected_policies[]',
                        value: policyId
                    }).appendTo('#bulk-action-form');
                    console.log('Added hidden input for policy ID:', policyId);
                });
                
                // Eğer hiç seçim yoksa formu durdu
                if (selectedPolicies.length === 0) {
                    e.preventDefault();
                    alert('Lütfen en az bir poliçe seçin.');
                    return false;
                }
                
                // İşlem seçimi kontrolü
                var action = $('#bulk-action-select').val();
                if (!action || action === '') {
                    e.preventDefault();
                    alert('Lütfen bir işlem seçin.');
                    return false;
                }
                
                // Silme işlemi için onay iste
                if (action === 'delete') {
                    if (!confirm('Seçili poliçeleri silmek istediğinizden emin misiniz?')) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                console.log('Form submission allowed');
                return true;
            });

            function updateBulkActionButton() {
                // Global selectedPolicies dizisini kullan
                var selectedPolicies = window.selectedPolicies || [];
                
                const anyChecked = selectedPolicies.length > 0;
                const actionSelected = $('#bulk-action-select').val() !== '';
                
                console.log('Updating bulk action button:', {
                    selectedCount: selectedPolicies.length,
                    actionSelected: actionSelected,
                    buttonDisabled: !(anyChecked && actionSelected)
                });
                
                $('#bulk-action-button').prop('disabled', !(anyChecked && actionSelected));
                $('#selected-count').text(selectedPolicies.length + ' poliçe seçildi');
            }

            function updateSelectAllCheckbox() {
                const visibleCheckboxes = $('input[name="selected_policies[]"]:visible');
                const visibleCheckedCount = visibleCheckboxes.filter(':checked').length;
                const visibleTotal = visibleCheckboxes.length;
                
                if (visibleCheckedCount === 0) {
                    $('#select-all-policies').prop('indeterminate', false).prop('checked', false);
                } else if (visibleCheckedCount === visibleTotal) {
                    $('#select-all-policies').prop('indeterminate', false).prop('checked', true);
                } else {
                    $('#select-all-policies').prop('indeterminate', true);
                }
            }

            function updateCheckboxStates() {
                // selectedPolicies dizisinin tanımlı olduğundan emin ol
                if (typeof selectedPolicies === 'undefined') {
                    selectedPolicies = [];
                }
                
                // DataTable sayfa değiştiğinde mevcut seçimleri koru
                $('input[name="selected_policies[]"]').each(function() {
                    var policyId = $(this).val();
                    if (selectedPolicies.indexOf(policyId) !== -1) {
                        $(this).prop('checked', true);
                    }
                });
                
                updateSelectAllCheckbox();
                updateBulkActionButton();
            }

            // Policy status toggle with confirmation
            $(document).on('change', '.policy-status-toggle', function() {
                const policyId = $(this).data('policy-id');
                const isChecked = $(this).prop('checked');
                const newStatus = isChecked ? 'aktif' : 'pasif';
                
                if (confirm('Bu poliçeyi ' + newStatus + ' yapmak istediğinizden emin misiniz?')) {
                    const form = $('<form action="{{ url("policies") }}/' + policyId + '/toggle-status" method="POST"></form>');
                    form.append('{{ csrf_field() }}');
                    form.append('<input type="hidden" name="_method" value="PATCH">');
                $('body').append(form);
                form.submit();
                } else {
                    // İşlem iptal edildi, checkbox'ı eski haline getir
                    $(this).prop('checked', !isChecked);
                }
            });

            // DataTable search ve pagination için URL parametrelerini koru
            window.policiesTable.on('page.dt', function() {
                var info = window.policiesTable.page.info();
                var url = new URL(window.location);
                url.searchParams.set('page', info.page + 1);
                window.history.pushState({}, '', url);
                
                // Sayfa değişiminden sonra ikonları yeniden başlat
                setTimeout(function() {
                    reinitializeIcons();
                }, 100);
            });

            window.policiesTable.on('search.dt', function() {
                var search = window.policiesTable.search();
                var url = new URL(window.location);
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }
                window.history.pushState({}, '', url);
            });

            // Responsive table için ek event listener
            window.policiesTable.on('responsive-display', function() {
                // Responsive modal açıldığında ek işlemler
                $('.dtr-modal').addClass('modal-lg');
            });

            // Table row hover effect
            $('#policies-datatable tbody').on('mouseenter', 'tr', function() {
                $(this).addClass('table-hover-highlight');
            }).on('mouseleave', 'tr', function() {
                $(this).removeClass('table-hover-highlight');
            });

            // Sütun sıralama için ek event listener
            window.policiesTable.on('order.dt', function() {
                var order = window.policiesTable.order();
                var url = new URL(window.location);
                if (order.length > 0) {
                    url.searchParams.set('order_column', order[0][0]);
                    url.searchParams.set('order_direction', order[0][1]);
                } else {
                    url.searchParams.delete('order_column');
                    url.searchParams.delete('order_direction');
                }
                window.history.pushState({}, '', url);
            });
        }
    </script>
    @endpush
@endsection
