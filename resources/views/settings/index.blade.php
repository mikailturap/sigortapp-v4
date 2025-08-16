@extends('layouts.app')

@section('title', 'Ayarlar')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i data-lucide="settings" class="text-muted me-2" style="width: 24px; height: 24px;"></i>
                Ayarlar
            </h1>
            <p class="text-muted mb-0 mt-1 small">Uygulama genel ayarlarını yönetin</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 p-0">
            <style>
            /* SADE TAB TASARIMI - INLINE */
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

            /* TABLO GENİŞLİĞİ DÜZELTME */
            .table-responsive {
                width: 100% !important;
                overflow-x: auto !important;
            }

            .table {
                width: 100% !important;
                min-width: 100% !important;
            }

            .dataTables_wrapper {
                width: 100% !important;
            }

            .dataTables_wrapper .dataTables_scroll {
                width: 100% !important;
            }

            /* DataTables özel stilleri */
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                width: 100% !important;
                margin: 10px 0 !important;
            }

            .dataTables_length select,
            .dataTables_filter input {
                min-width: 120px !important;
            }

            /* Tablo sütun genişlikleri */
            #policyTypesTable th,
            #policyTypesTable td,
            #companiesTable th,
            #companiesTable td {
                white-space: nowrap !important;
                min-width: 120px !important;
            }

            /* Durum sütunu için özel genişlik */
            #policyTypesTable th:nth-child(3),
            #policyTypesTable td:nth-child(3),
            #companiesTable th:nth-child(3),
            #companiesTable td:nth-child(3) {
                width: 100px !important;
                min-width: 100px !important;
            }

            /* İşlemler sütunu için özel genişlik */
            #policyTypesTable th:nth-child(5),
            #policyTypesTable td:nth-child(5),
            #companiesTable th:nth-child(5),
            #companiesTable td:nth-child(5) {
                width: 120px !important;
                min-width: 120px !important;
            }

            /* Sıralama ikonları için CSS - GÜÇLÜ */
            .dataTables_wrapper .dataTables_sorting,
            .dataTables_wrapper .dataTables_sorting_asc,
            .dataTables_wrapper .dataTables_sorting_desc {
                cursor: pointer !important;
                position: relative !important;
                padding-right: 20px !important;
            }

            .dataTables_wrapper .dataTables_sorting::before,
            .dataTables_wrapper .dataTables_sorting::after,
            .dataTables_wrapper .dataTables_sorting_asc::before,
            .dataTables_wrapper .dataTables_sorting_asc::after,
            .dataTables_wrapper .dataTables_sorting_desc::before,
            .dataTables_wrapper .dataTables_sorting_desc::after {
                position: absolute !important;
                right: 8px !important;
                color: #6c757d !important;
                font-size: 14px !important;
                font-weight: bold !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
            }

            .dataTables_wrapper .dataTables_sorting::before {
                content: "⇅" !important;
                color: #6c757d !important;
            }

            .dataTables_wrapper .dataTables_sorting_asc::before {
                content: "↑" !important;
                color: #0d6efd !important;
            }

            .dataTables_wrapper .dataTables_sorting_desc::before {
                content: "↓" !important;
                color: #0d6efd !important;
            }

            /* Tablo başlıkları için ek stiller */
            .dataTables_wrapper th {
                position: relative !important;
                cursor: pointer !important;
                user-select: none !important;
            }

            .dataTables_wrapper th:hover {
                background-color: #f8f9fa !important;
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
            </style>
            <ul class="nav nav-tabs nav-tabs-line" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                        <i data-lucide="settings" style="width: 20px; height: 20px;"></i>
                        <span>Sistem Ayarları</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                        <i data-lucide="user" style="width: 20px; height: 20px;"></i>
                        <span>Hesap Ayarları</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                        <i data-lucide="bell" style="width: 20px; height: 20px;"></i>
                        <span>Bildirimler</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management" type="button" role="tab">
                        <i data-lucide="database" style="width: 20px; height: 20px;"></i>
                        <span>Poliçe Yönetimi</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="settingsTabContent">
                <!-- Sistem Ayarları Tab -->
                <div class="tab-pane fade show active" id="system" role="tabpanel">
                    <div class="p-4">
                        <h5 class="mb-3 text-dark">Sistem Ayarları</h5>
                        
                        <!-- Genel Ayarlar -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2" style="font-size: 18px;">🎛️</span>
                                    <h6 class="mb-0 fw-normal text-dark">Genel</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('settings.update') }}" method="POST" class="row g-3">
                                    @csrf
                                    @method('PATCH')

                                    <div class="col-md-6">
                                        <label class="form-label fw-normal text-secondary" for="tracking_window_days">Poliçe Takip Penceresi</label>
                                        <select name="tracking_window_days" id="tracking_window_days" class="form-select">
                                            @foreach (['7'=>'7 gün','15'=>'15 gün','30'=>'30 gün','60'=>'60 gün'] as $k=>$label)
                                                <option value="{{ $k }}" {{ ($settings['tracking_window_days'] ?? '30') == $k ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Yaklaşan yenilemelerde gösterilecek gün aralığı</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-normal text-secondary" for="notifications_expiration_threshold_days">Bildirim Eşiği (Gün)</label>
                                        <input type="number" min="1" max="365" class="form-control" id="notifications_expiration_threshold_days" name="notifications_expiration_threshold_days" value="{{ $settings['notifications_expiration_threshold_days'] ?? '7' }}">
                                        <div class="form-text">Bitişe şu kadar gün kala bildirim göster</div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end mt-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>
                                            Kaydet
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Dashboard Privacy Settings -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2" style="font-size: 18px;">👁️‍🗨️</span>
                                    <h6 class="mb-0 fw-normal text-dark">Dashboard Veri Gizleme</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('settings.update') }}" method="POST" class="row g-3">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="dashboard_privacy_enabled" name="dashboard_privacy_enabled" {{ $dashboardPrivacy->is_enabled ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dashboard_privacy_enabled">Dashboard Veri Gizleme Özelliği Aktif</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-normal text-secondary" for="dashboard_privacy_key_combination">Tuş Kombinasyonu</label>
                                        <input type="text" class="form-control" id="dashboard_privacy_key_combination" name="dashboard_privacy_key_combination" 
                                               value="{{ $dashboardPrivacy->key_combination }}" placeholder="Örn: Ctrl+Shift+P">
                                        <div class="form-text">Dashboard'da veri gizleme/açma için kullanılacak tuş kombinasyonu</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="alert alert-info d-flex align-items-center" role="alert">
                                            <span class="me-2" style="font-size: 16px;">ℹ️</span>
                                            <div>
                                                <strong>Kullanım:</strong> Dashboard sayfasında belirlenen tuş kombinasyonuna basarak tüm rakamsal veriler gizlenir. 
                                                Aynı kombinasyonu tekrar basarak veriler tekrar görünür hale gelir.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end mt-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>
                                            Kaydet
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hesap Ayarları Tab -->
                <div class="tab-pane fade" id="account" role="tabpanel">
                    <div class="p-4">
                        <h5 class="mb-3 text-dark">Hesap Ayarları</h5>
                        
                        <!-- Profil Bilgileri -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2" style="font-size: 18px;">👤</span>
                                    <h6 class="mb-0 fw-normal text-dark">Profil Bilgileri</h6>
                                </div>
                                <p class="text-muted small mb-0 mt-2">Hesabınızın profil bilgilerini ve e-posta adresini görüntüleyin.</p>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-normal text-secondary">Ad Soyad</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-normal text-secondary">E-posta Adresi</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Şifre Değiştir -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2" style="font-size: 18px;">🔒</span>
                                    <h6 class="mb-0 fw-normal text-dark">Şifre Değiştir</h6>
                                </div>
                                <p class="text-muted small mb-0 mt-2">Güvenli bir hesap için uzun ve rastgele bir şifre kullandığınızdan emin olun.</p>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-md-4">
                                        <label for="update_password_current_password" class="form-label fw-normal text-secondary">Mevcut Şifre</label>
                                        <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                                        @if ($errors->updatePassword->get('current_password'))
                                            <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label for="update_password_password" class="form-label fw-normal text-secondary">Yeni Şifre</label>
                                        <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
                                        @if ($errors->updatePassword->get('password'))
                                            <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label for="update_password_password_confirmation" class="form-label fw-normal text-secondary">Yeni Şifre Tekrar</label>
                                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                                        @if ($errors->updatePassword->get('password_confirmation'))
                                            <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-12 d-flex align-items-center gap-2 mt-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>
                                            Kaydet
                                        </button>
                                        @if (session('status') === 'password-updated')
                                            <span class="text-success small">Kaydedildi.</span>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bildirimler Tab -->
                <div class="tab-pane fade" id="notifications" role="tabpanel">
                    <div class="p-4">
                        <h5 class="mb-3 text-dark">Bildirim Ayarları</h5>
                        
                        <!-- SMS Bildirimleri -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2" style="font-size: 18px;">💬</span>
                                    <h6 class="mb-0 fw-normal text-dark">Otomatik SMS Bildirimleri</h6>
                                </div>
                                <p class="text-muted small mb-0 mt-2">Müşterilerinize poliçe bitim tarihlerinde otomatik SMS gönderin.</p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('settings.update') }}" method="POST" class="row g-3">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="sms_enabled" name="sms_enabled" {{ ( ($settings['sms_enabled'] ?? '0') === '1') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sms_enabled">Otomatik SMS Gönderimi Aktif</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-normal text-secondary" for="sms_reminder_days">Hatırlatma Günü</label>
                                        <input type="number" min="1" max="60" class="form-control" id="sms_reminder_days" name="sms_reminder_days" value="{{ $settings['sms_reminder_days'] ?? '3' }}" placeholder="Poliçe bitimine kaç gün kala SMS gönderilecek?">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-normal text-secondary" for="whatsapp_reminder_days">WhatsApp Hatırlatma Günü</label>
                                        <input type="number" min="1" max="60" class="form-control" id="whatsapp_reminder_days" name="whatsapp_reminder_days" value="{{ $settings['whatsapp_reminder_days'] ?? '7' }}" placeholder="Kaç gün önce hatırlatma butonu görünsün?">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-normal text-secondary" for="sms_template">SMS Şablonu</label>
                                        <textarea class="form-control" id="sms_template" name="sms_template" rows="3" placeholder="Sayın {customerTitle}, {policyNumber} numaralı poliçenizin bitiş tarihi olan {endDate} yaklaşıyor. Yenileme için bize ulaşabilirsiniz.">{{ $settings['sms_template'] ?? '' }}</textarea>
                                        <div class="form-text">Kullanılabilir alanlar: {customerTitle}, {policyNumber}, {endDate}</div>
                                    </div>

                                    <div class="col-12">
                                                                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                                <span class="me-2" style="font-size: 16px;">ℹ️</span>
                                                <div>SMS servis sağlayıcı bilgileri güvenlik nedeniyle sunucu tarafında ayarlanmalıdır.</div>
                                            </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end mt-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>
                                            Kaydet
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- E-posta Özeti -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2" style="font-size: 18px;">📧</span>
                                    <h6 class="mb-0 fw-normal text-dark">Poliçe Takip E-posta Bildirimi</h6>
                                </div>
                                <p class="text-muted small mb-0 mt-2">Poliçe takip listelerini seçtiğiniz periyotta ve saatte şirket e-postanıza gönderelim.</p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('settings.update') }}" method="POST" class="row g-3">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-md-4">
                                        <label class="form-label fw-normal text-secondary" for="company_email">Şirket E-postası</label>
                                        <input type="email" class="form-control" id="company_email" name="company_email" value="{{ $settings['company_email'] ?? '' }}" placeholder="mail@company.com">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-normal text-secondary" for="email_summary_frequency">Gönderim Periyodu</label>
                                        <select id="email_summary_frequency" name="email_summary_frequency" class="form-select">
                                            <option value="daily" {{ ($settings['email_summary_frequency'] ?? 'daily') === 'daily' ? 'selected' : '' }}>📅 Günlük</option>
                                            <option value="weekly" {{ ($settings['email_summary_frequency'] ?? 'daily') === 'weekly' ? 'selected' : '' }}>📅 Haftalık</option>
                                            <option value="monthly" {{ ($settings['email_summary_frequency'] ?? 'daily') === 'monthly' ? 'selected' : '' }}>📅 Aylık</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-normal text-secondary" for="email_summary_day">Gün Seçimi</label>
                                        <select id="email_summary_day" name="email_summary_day" class="form-select">
                                            <option value="1" {{ ($settings['email_summary_day'] ?? '1') === '1' ? 'selected' : '' }}>Pazartesi</option>
                                            <option value="2" {{ ($settings['email_summary_day'] ?? '1') === '2' ? 'selected' : '' }}>Salı</option>
                                            <option value="3" {{ ($settings['email_summary_day'] ?? '1') === '3' ? 'selected' : '' }}>Çarşamba</option>
                                            <option value="4" {{ ($settings['email_summary_day'] ?? '1') === '4' ? 'selected' : '' }}>Perşembe</option>
                                            <option value="5" {{ ($settings['email_summary_day'] ?? '1') === '5' ? 'selected' : '' }}>Cuma</option>
                                            <option value="6" {{ ($settings['email_summary_day'] ?? '1') === '6' ? 'selected' : '' }}>Cumartesi</option>
                                            <option value="7" {{ ($settings['email_summary_day'] ?? '1') === '7' ? 'selected' : '' }}>Pazar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-normal text-secondary" for="email_summary_time">Gönderim Saati</label>
                                        <input type="time" class="form-control" id="email_summary_time" name="email_summary_time" value="{{ $settings['email_summary_time'] ?? '08:30' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-normal text-secondary" for="daily_summary_format">Biçim</label>
                                        <select id="daily_summary_format" name="daily_summary_format" class="form-select">
                                            <option value="xlsx" {{ ($settings['daily_summary_format'] ?? 'xlsx') === 'xlsx' ? 'selected' : '' }}>Excel (.xlsx)</option>
                                            <option value="pdf" {{ ($settings['daily_summary_format'] ?? 'xlsx') === 'pdf' ? 'selected' : '' }}>PDF</option>
                                        </select>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>
                                            Kaydet
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Poliçe Yönetimi Tab -->
                <div class="tab-pane fade" id="management" role="tabpanel">
                    <div class="p-4">
                        <h5 class="mb-3 text-dark">Poliçe Yönetimi</h5>
                        
                        <!-- Poliçe Türleri (Yeni Mantık: Modal ile ekle/düzenle) -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="text-primary me-2" style="font-size: 18px;">🏷️</span>
                                        <h6 class="mb-0 fw-normal text-dark">Poliçe Türleri</h6>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm btn-new-policy-type">
                                        <i data-lucide="plus" class="me-1" style="width: 14px; height: 14px;"></i>
                                        Yeni Tür
                                    </button>
                                </div>
                                <p class="text-muted small mb-0 mt-2">Tüm işlemler bu ekranda; ekleme/düzenleme için modal açılır.</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="width: 100%;">
                                    <table id="policyTypesTable" class="table table-hover mb-0" style="width: 100%;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Poliçe Türü</th>
                                                <th>Açıklama</th>
                                                <th class="text-center">Durum</th>
                                                <th class="text-center">Kullanım</th>
                                                <th class="text-center">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(\App\Models\PolicyType::ordered()->get() as $policyType)
                                            <tr id="policyTypeRow{{ $policyType->id }}">
                                                <td>
                                                    <strong>{{ $policyType->name }}</strong>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ Str::limit($policyType->description, 50) ?: 'Açıklama yok' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" onchange="updatePolicyTypeStatus({{ $policyType->id }}, this.checked)" {{ $policyType->is_active ? 'checked' : '' }} style="width: 3rem; height: 1.5rem;">
                                                    </div>
                                                </td>
                                                <td class="text-center"><span class="badge bg-info">{{ $policyType->policies()->count() }} poliçe</span></td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm btn-edit-policy-type"
                                                            data-id="{{ $policyType->id }}"
                                                            data-name="{{ e($policyType->name) }}"
                                                            data-description="{{ e($policyType->description) }}"
                                                            data-active="{{ $policyType->is_active ? '1' : '0' }}"
                                                            title="Düzenle">
                                                            <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                        @if($policyType->policies()->count() == 0)
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deletePolicyType({{ $policyType->id }})" title="Sil">
                                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-muted">Henüz poliçe türü eklenmemiş</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Sigorta Şirketleri (Yeni Mantık: Modal ile ekle/düzenle) -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="text-success me-2" style="font-size: 18px;">🏢</span>
                                        <h6 class="mb-0 fw-normal text-dark">Sigorta Şirketleri</h6>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm btn-new-company">
                                        <i data-lucide="plus" class="me-1" style="width: 14px; height: 14px;"></i>
                                        Yeni Şirket
                                    </button>
                                </div>
                                <p class="text-muted small mb-0 mt-2">Tüm işlemler bu ekranda; ekleme/düzenleme için modal açılır.</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="width: 100%;">
                                    <table id="companiesTable" class="table table-hover mb-0" style="width: 100%;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Şirket Adı</th>
                                                <th>Açıklama</th>
                                                <th class="text-center">Durum</th>
                                                <th class="text-center">Kullanım</th>
                                                <th class="text-center">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(\App\Models\InsuranceCompany::ordered()->get() as $company)
                                            <tr id="companyRow{{ $company->id }}">
                                                <td><strong>{{ $company->name }}</strong></td>
                                                <td><span class="text-muted">{{ Str::limit($company->description, 50) ?: 'Açıklama yok' }}</span></td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" onchange="updateCompanyStatus({{ $company->id }}, this.checked)" {{ $company->is_active ? 'checked' : '' }} style="width: 3rem; height: 1.5rem;">
                                                    </div>
                                                </td>
                                                <td class="text-center"><span class="badge bg-info">{{ $company->policies()->count() }} poliçe</span></td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm btn-edit-company"
                                                            data-id="{{ $company->id }}"
                                                            data-name="{{ e($company->name) }}"
                                                            data-description="{{ e($company->description) }}"
                                                            data-active="{{ $company->is_active ? '1' : '0' }}"
                                                            title="Düzenle">
                                                            <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                        @if($company->policies()->count() == 0)
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteCompany({{ $company->id }})" title="Sil">
                                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                    @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-muted">Henüz sigorta şirketi eklenmemiş</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
<!-- Modal Bilesenleri: Poliçe Türü -->
<div class="modal fade" id="policyTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Poliçe Türü</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="policyTypeForm" class="row g-3" onsubmit="event.preventDefault(); submitPolicyTypeModal();">
                    <input type="hidden" name="id" />
                    <div class="col-12">
                        <label class="form-label">Ad <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Durum</label>
                        <select class="form-select" name="is_active">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i data-lucide="x" class="me-1" style="width:14px;height:14px;"></i>Kapat
                </button>
                <button type="button" class="btn btn-primary" onclick="submitPolicyTypeModal()">
                    <i data-lucide="save" class="me-1" style="width:14px;height:14px;"></i>Kaydet
                </button>
            </div>
        </div>
    </div>
    </div>

<!-- Modal Bilesenleri: Şirket -->
<div class="modal fade" id="companyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sigorta Şirketi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="companyForm" class="row g-3" onsubmit="event.preventDefault(); submitCompanyModal();">
                    <input type="hidden" name="id" />
                    <div class="col-12">
                        <label class="form-label">Ad <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Durum</label>
                        <select class="form-select" name="is_active">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i data-lucide="x" class="me-1" style="width:14px;height:14px;"></i>Kapat
                </button>
                <button type="button" class="btn btn-primary" onclick="submitCompanyModal()">
                    <i data-lucide="save" class="me-1" style="width:14px;height:14px;"></i>Kaydet
                </button>
            </div>
        </div>
    </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Route yardımcıları
    const routes = {
        policyTypeStore: '{{ route('settings.policy-types.store') }}',
        companyStore: '{{ route('settings.insurance-companies.store') }}'
    };
    // Yardımcılar
    const getCsrfToken = () => {
        const el = document.querySelector('meta[name="csrf-token"]');
        return el ? el.getAttribute('content') : '';
    };
    
    const fetchJson = async (url, options = {}) => {
        const defaultHeaders = {
            'Accept': 'application/json'
        };
        const headers = Object.assign({}, defaultHeaders, options.headers || {});
        const response = await fetch(url, Object.assign({}, options, { headers }));
        if (!response.ok) {
            const text = await response.text().catch(() => '');
            const msg = `HTTP ${response.status}${text && text.startsWith('<') ? '' : `: ${text}`}`;
            throw new Error(msg);
        }
        return response.json();
    };

    const getDataTable = (selector) => {
        if (window.$ && window.$.fn && window.$.fn.DataTable && window.$.fn.DataTable.isDataTable(selector)) {
            return window.$(selector).DataTable();
        }
        return null;
    };

    const reinitIcons = () => {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    };

    const renderPolicyTypeRow = (policyType) => {
        const isActive = policyType.is_active ? 'checked' : '';
        const description = policyType.description || '';
        const id = policyType.id;
        const usageBadge = `<span class="badge bg-info">0 poliçe</span>`;
        return `
        <tr id="policyTypeRow${id}">
            <td>
                <div class="policy-type-name" data-id="${id}" data-name="${policyType.name}" style="cursor: pointer;">
                    <strong>${policyType.name}</strong>
                </div>
                <div class="policy-type-edit" style="display: none;">
                    <input type="text" class="form-control form-control-sm" value="${policyType.name}"
                           onblur="updatePolicyType(${id}, this.value, 'name')">
                </div>
            </td>
            <td>
                <div class="policy-type-description" data-id="${id}" data-description="${description}" style="cursor: pointer;">
                    <span class="text-muted">${description || 'Açıklama yok'}</span>
                </div>
                <div class="policy-type-edit" style="display: none;">
                    <input type="text" class="form-control form-control-sm" value="${description}"
                           onblur="updatePolicyType(${id}, this.value, 'description')">
                </div>
            </td>
            <td class="text-center">
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" onchange="updatePolicyTypeStatus(${id}, this.checked)" ${isActive} style="width: 3rem; height: 1.5rem;">
                </div>
            </td>
            <td class="text-center">${usageBadge}</td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editPolicyType(${id})" title="Düzenle">
                        <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deletePolicyType(${id})" title="Sil">
                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    };

    const renderCompanyRow = (company) => {
        const isActive = company.is_active ? 'checked' : '';
        const description = company.description || '';
        const id = company.id;
        const usageBadge = `<span class="badge bg-info">0 poliçe</span>`;
        return `
        <tr id="companyRow${id}">
            <td>
                <div class="company-name" data-id="${id}" data-name="${company.name}" style="cursor: pointer;">
                    <strong>${company.name}</strong>
                </div>
                <div class="company-edit" style="display: none;">
                    <input type="text" class="form-control form-control-sm" value="${company.name}"
                           onblur="updateCompany(${id}, this.value, 'name')">
                </div>
            </td>
            <td>
                <div class="company-description" data-id="${id}" data-description="${description}" style="cursor: pointer;">
                    <span class="text-muted">${description || 'Açıklama yok'}</span>
                </div>
                <div class="company-edit" style="display: none;">
                    <input type="text" class="form-control form-control-sm" value="${description}"
                           onblur="updateCompany(${id}, this.value, 'description')">
                </div>
            </td>
            <td class="text-center">
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" onchange="updateCompanyStatus(${id}, this.checked)" ${isActive} style="width: 3rem; height: 1.5rem;">
                </div>
            </td>
            <td class="text-center">${usageBadge}</td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editCompany(${id})" title="Düzenle">
                        <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteCompany(${id})" title="Sil">
                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    };
    // Local storage'dan aktif tab bilgisini al
    const activeTab = localStorage.getItem('activeSettingsTab');
    
    if (activeTab) {
        // İlgili tabı bul ve aktif et
        const targetTab = document.querySelector(`#${activeTab}-tab`);
        const targetContent = document.querySelector(`#${activeTab}`);
        
        if (targetTab && targetContent) {
            // Tüm tabları pasif yap
            document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(content => content.classList.remove('show', 'active'));
            
            // Hedef tabı aktif yap
            targetTab.classList.add('active');
            targetContent.classList.add('show', 'active');
            
            // Local storage'dan temizle
            localStorage.removeItem('activeSettingsTab');
        }
    }

    // Tab değişim animasyonları
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            
            if (targetPane) {
                // Slide in animasyonu
                targetPane.style.animation = 'none';
                targetPane.offsetHeight; // Trigger reflow
                targetPane.style.animation = 'slideInUp 0.4s ease-out';
            }
        });
    });

    // Tab hover efektleri
    const tabLinks = document.querySelectorAll('.nav-tabs-line .nav-link');
    
    tabLinks.forEach(tab => {
        tab.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-3px)';
            }
        });
        
        tab.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });

    // E-posta özeti periyod kontrolü
    const frequencySelect = document.getElementById('email_summary_frequency');
    const daySelect = document.getElementById('email_summary_day');
    
    if (frequencySelect && daySelect) {
        function updateDaySelection() {
            const frequency = frequencySelect.value;
            
            if (frequency === 'daily') {
                daySelect.style.display = 'none';
                daySelect.previousElementSibling.style.display = 'none';
            } else if (frequency === 'weekly') {
                daySelect.style.display = 'block';
                daySelect.previousElementSibling.style.display = 'block';
                daySelect.innerHTML = `
                    <option value="1">Pazartesi</option>
                    <option value="2">Salı</option>
                    <option value="3">Çarşamba</option>
                    <option value="4">Perşembe</option>
                    <option value="5">Cuma</option>
                    <option value="6">Cumartesi</option>
                    <option value="7">Pazar</option>
                `;
            } else if (frequency === 'monthly') {
                daySelect.style.display = 'block';
                daySelect.previousElementSibling.style.display = 'block';
                daySelect.innerHTML = `
                    <option value="1">Ayın 1'i</option>
                    <option value="5">Ayın 5'i</option>
                    <option value="10">Ayın 10'u</option>
                    <option value="15">Ayın 15'i</option>
                    <option value="20">Ayın 20'si</option>
                    <option value="25">Ayın 25'i</option>
                    <option value="30">Ayın son günü</option>
                `;
            }
        }
        
        // Sayfa yüklendiğinde kontrol et
        updateDaySelection();
        
        // Periyod değiştiğinde güncelle
        frequencySelect.addEventListener('change', updateDaySelection);
    }

    // Poliçe Türü Yönetimi Fonksiyonları
    // Yeni mantık: Modal ile ekleme/düzenleme
    const policyTypeModalEl = document.getElementById('policyTypeModal');
    const policyTypeModal = policyTypeModalEl ? new bootstrap.Modal(policyTypeModalEl) : null;
    const companyModalEl = document.getElementById('companyModal');
    const companyModal = companyModalEl ? new bootstrap.Modal(companyModalEl) : null;

    const policyTypeForm = document.getElementById('policyTypeForm');
    const companyForm = document.getElementById('companyForm');

    const openPolicyTypeModal = (data = null) => {
        if (!policyTypeForm || !policyTypeModal) return;
        policyTypeForm.reset();
        policyTypeForm.querySelector('[name="id"]').value = data?.id || '';
        policyTypeForm.querySelector('[name="name"]').value = data?.name || '';
        policyTypeForm.querySelector('[name="description"]').value = data?.description || '';
        policyTypeForm.querySelector('[name="is_active"]').value = data?.is_active ? '1' : '0';
        policyTypeModal.show();
        reinitIcons();
    };

    const openCompanyModal = (data = null) => {
        if (!companyForm || !companyModal) return;
        companyForm.reset();
        companyForm.querySelector('[name="id"]').value = data?.id || '';
        companyForm.querySelector('[name="name"]').value = data?.name || '';
        companyForm.querySelector('[name="description"]').value = data?.description || '';
        companyForm.querySelector('[name="is_active"]').value = data?.is_active ? '1' : '0';
        companyModal.show();
        reinitIcons();
    };

    // Modal açma butonları (delegation)
    document.addEventListener('click', function(e) {
        const newPolicyTypeBtn = e.target.closest('.btn-new-policy-type');
        if (newPolicyTypeBtn) {
            openPolicyTypeModal();
            return;
        }
        const editPolicyTypeBtn = e.target.closest('.btn-edit-policy-type');
        if (editPolicyTypeBtn) {
            const dataset = editPolicyTypeBtn.dataset;
            openPolicyTypeModal({
                id: dataset.id,
                name: dataset.name || '',
                description: dataset.description || '',
                is_active: dataset.active === '1'
            });
            return;
        }

        const newCompanyBtn = e.target.closest('.btn-new-company');
        if (newCompanyBtn) {
            openCompanyModal();
            return;
        }
        const editCompanyBtn = e.target.closest('.btn-edit-company');
        if (editCompanyBtn) {
            const dataset = editCompanyBtn.dataset;
            openCompanyModal({
                id: dataset.id,
                name: dataset.name || '',
                description: dataset.description || '',
                is_active: dataset.active === '1'
            });
            return;
        }
    });

    // Modal submitleri
    window.submitPolicyTypeModal = function() {
        if (!policyTypeForm) return;
        const id = policyTypeForm.querySelector('[name="id"]').value;
        const payload = {
            name: policyTypeForm.querySelector('[name="name"]').value.trim(),
            description: policyTypeForm.querySelector('[name="description"]').value.trim(),
            is_active: policyTypeForm.querySelector('[name="is_active"]').value
        };
        const url = id ? `/settings/policy-types/${id}` : routes.policyTypeStore;
        const method = id ? 'PATCH' : 'POST';
        fetchJson(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify(payload)
        })
        .then(data => {
            if (id) {
                // Güncelle
                const row = document.getElementById('policyTypeRow' + id);
                if (row) {
                    row.children[0].querySelector('strong').textContent = payload.name;
                    row.children[1].querySelector('span').textContent = payload.description || 'Açıklama yok';
                    const switchInput = row.querySelector('.form-check-input');
                    if (switchInput) switchInput.checked = payload.is_active === '1';
                }
                showToast('Poliçe türü güncellendi', 'success');
            } else {
                // Ekle
                if (data && data.data) {
                    const table = getDataTable('#policyTypesTable');
                    const newRowHtml = renderPolicyTypeRow(data.data);
                    if (table) {
                        table.row.add($(newRowHtml)).draw(false);
                    } else {
                        const tbody = document.querySelector('#policyTypesTable tbody');
                        if (tbody) tbody.insertAdjacentHTML('beforeend', newRowHtml);
                    }
                }
                showToast('Poliçe türü eklendi', 'success');
            }
            if (policyTypeModal) policyTypeModal.hide();
            reinitIcons();
        })
        .catch(err => {
            // Sunucu Türkçe validasyon döndürürse JSON içeriğini ayrıştırıp mesaj verelim
            try {
                const m = err.message || '';
                const jsonStart = m.indexOf('{');
                if (jsonStart !== -1) {
                    const obj = JSON.parse(m.slice(jsonStart));
                    const first = (obj.errors && Object.values(obj.errors)[0] && Object.values(obj.errors)[0][0]) || obj.message;
                    return showToast(first || 'Bir doğrulama hatası oluştu.', 'error');
                }
            } catch (_) {}
            showToast('Hata: ' + err.message, 'error');
        });
    };

    window.submitCompanyModal = function() {
        if (!companyForm) return;
        const id = companyForm.querySelector('[name="id"]').value;
        const payload = {
            name: companyForm.querySelector('[name="name"]').value.trim(),
            description: companyForm.querySelector('[name="description"]').value.trim(),
            is_active: companyForm.querySelector('[name="is_active"]').value
        };
        const url = id ? `/settings/insurance-companies/${id}` : routes.companyStore;
        const method = id ? 'PATCH' : 'POST';
        fetchJson(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify(payload)
        })
        .then(data => {
            if (id) {
                const row = document.getElementById('companyRow' + id);
                if (row) {
                    row.children[0].querySelector('strong').textContent = payload.name;
                    row.children[1].querySelector('span').textContent = payload.description || 'Açıklama yok';
                    const switchInput = row.querySelector('.form-check-input');
                    if (switchInput) switchInput.checked = payload.is_active === '1';
                }
                showToast('Şirket güncellendi', 'success');
            } else {
                if (data && data.data) {
                    const table = getDataTable('#companiesTable');
                    const newRowHtml = renderCompanyRow(data.data);
                    if (table) {
                        table.row.add($(newRowHtml)).draw(false);
                    } else {
                        const tbody = document.querySelector('#companiesTable tbody');
                        if (tbody) tbody.insertAdjacentHTML('beforeend', newRowHtml);
                    }
                }
                showToast('Şirket eklendi', 'success');
            }
            if (companyModal) companyModal.hide();
            reinitIcons();
        })
        .catch(err => {
            try {
                const m = err.message || '';
                const jsonStart = m.indexOf('{');
                if (jsonStart !== -1) {
                    const obj = JSON.parse(m.slice(jsonStart));
                    const first = (obj.errors && Object.values(obj.errors)[0] && Object.values(obj.errors)[0][0]) || obj.message;
                    return showToast(first || 'Bir doğrulama hatası oluştu.', 'error');
                }
            } catch (_) {}
            showToast('Hata: ' + err.message, 'error');
        });
    };

    // Toast mesajları için basit fonksiyon
    window.showToast = function(message, type = 'info') {
        // Mevcut toast'ları temizle
        const existingToasts = document.querySelectorAll('.toast-message');
        existingToasts.forEach(toast => toast.remove());
        
        // Yeni toast oluştur
        const toast = document.createElement('div');
        toast.className = `toast-message alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <span class="me-2">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
                ${message}
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // 3 saniye sonra kaldır
        setTimeout(() => {
            toast.remove();
        }, 3000);
    };

    // Click event listener'ları ekle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.policy-type-description')) {
            const descriptionDiv = e.target.closest('.policy-type-description');
            const id = descriptionDiv.getAttribute('data-id');
            const currentDesc = descriptionDiv.getAttribute('data-description');
            editPolicyTypeDescription(id, currentDesc);
        }
        
        if (e.target.closest('.policy-type-name')) {
            const nameDiv = e.target.closest('.policy-type-name');
            const id = nameDiv.getAttribute('data-id');
            const currentName = nameDiv.querySelector('strong').textContent;
            editPolicyTypeName(id, currentName);
        }
        
        if (e.target.closest('.company-description')) {
            const descriptionDiv = e.target.closest('.company-description');
            const id = descriptionDiv.getAttribute('data-id');
            const currentDesc = descriptionDiv.getAttribute('data-description');
            editCompanyDescription(id, currentDesc);
        }
        
        if (e.target.closest('.company-name')) {
            const nameDiv = e.target.closest('.company-name');
            const id = nameDiv.getAttribute('data-id');
            const currentName = nameDiv.querySelector('strong').textContent;
            editCompanyName(id, currentName);
        }
    });

    window.hideAddPolicyTypeForm = function() {
        document.getElementById('addPolicyTypeForm').style.display = 'none';
    };

    window.editPolicyType = function(id) {
        console.log('editPolicyType çağrıldı:', id);
        
        const row = document.getElementById('policyTypeRow' + id);
        if (!row) {
            console.error('Row bulunamadı:', id);
            return;
        }
        
        const nameDiv = row.querySelector('.policy-type-name');
        const descriptionDiv = row.querySelector('.policy-type-description');
        
        if (!nameDiv || !descriptionDiv) {
            console.error('Name veya description div bulunamadı');
            return;
        }
        
        // Mevcut değerleri al
        const currentName = nameDiv.getAttribute('data-name');
        const currentDescription = descriptionDiv.getAttribute('data-description');
        
        console.log('Mevcut değerler:', { currentName, currentDescription });
        
        // Düzenleme moduna geç
        nameDiv.style.display = 'none';
        descriptionDiv.style.display = 'none';
        
        // Düzenleme alanlarını bul ve göster
        const editDivs = row.querySelectorAll('.policy-type-edit');
        console.log('Bulunan edit div sayısı:', editDivs.length);
        
        editDivs.forEach((editDiv, index) => {
            editDiv.style.display = 'block';
            const input = editDiv.querySelector('input');
            if (input) {
                if (index === 0) {
                    input.value = currentName;
                    console.log('Name input değeri ayarlandı:', currentName);
                } else if (index === 1) {
                    input.value = currentDescription;
                    console.log('Description input değeri ayarlandı:', currentDescription);
                }
            }
        });
        
        // İşlem butonlarını güncelle
        const actionCell = row.querySelector('.btn-group');
        if (actionCell) {
            actionCell.innerHTML = `
                <button type="button" class="btn btn-success btn-sm" onclick="savePolicyType(${id})" title="Kaydet">
                    <i data-lucide="check" style="width: 14px; height: 14px;"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelEditPolicyType(${id})" title="İptal">
                    <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                </button>
            `;
            console.log('Butonlar güncellendi');
            // Dinamik eklenen lucide ikonlarını yeniden oluştur
            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        }
        
        console.log('editPolicyType tamamlandı');
    };

    window.editPolicyTypeName = function(id, currentName) {
        const row = document.getElementById('policyTypeRow' + id);
        const nameDiv = row.querySelector('.policy-type-name');
        const currentNameValue = nameDiv.getAttribute('data-name');
        
        nameDiv.style.display = 'none';
        row.querySelector('.policy-type-edit').style.display = 'block';
        row.querySelector('.policy-type-edit input').value = currentNameValue;
        row.querySelector('.policy-type-edit input').focus();
    };

    window.editPolicyTypeDescription = function(id, currentDescription) {
        const row = document.getElementById('policyTypeRow' + id);
        const descriptionDiv = row.querySelector('.policy-type-description');
        const currentDesc = descriptionDiv.getAttribute('data-description');
        
        descriptionDiv.style.display = 'none';
        row.querySelector('.policy-type-edit').style.display = 'block';
        row.querySelector('.policy-type-edit input').value = currentDesc;
        row.querySelector('.policy-type-edit input').focus();
    };

    window.savePolicyType = function(id) {
        const row = document.getElementById('policyTypeRow' + id);
        const nameInput = row.querySelector('.policy-type-edit input').value;
        const descriptionInput = row.querySelectorAll('.policy-type-edit input')[1]?.value || '';
        
        const newName = nameInput;
        const newDescription = descriptionInput;
        
        console.log('savePolicyType çağrıldı:', { id, newName, newDescription });
        
        // CSRF token'ı al
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token bulunamadı');
            showToast('CSRF token bulunamadı', 'error');
            return;
        }
        
        // AJAX ile güncelle
        fetch(`/settings/policy-types/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                name: newName, 
                description: newDescription 
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Başarılı güncelleme
                const nameDiv = row.querySelector('.policy-type-name');
                const descriptionDiv = row.querySelector('.policy-type-description');
                
                // Data attribute'ları güncelle
                nameDiv.setAttribute('data-name', newName);
                descriptionDiv.setAttribute('data-description', newDescription);
                
                // Görünümü güncelle
                nameDiv.querySelector('strong').textContent = newName;
                descriptionDiv.querySelector('span').textContent = newDescription || 'Açıklama yok';
                
                // Normal moda dön
                cancelEditPolicyType(id);
                showToast('Poliçe türü güncellendi', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Güncelleme başarısız: ' + error.message, 'error');
        });
    };

    window.cancelEditPolicyType = function(id) {
        const row = document.getElementById('policyTypeRow' + id);
        
        // Düzenleme modundan çık
        row.querySelectorAll('.policy-type-edit').forEach(editDiv => {
            editDiv.style.display = 'none';
        });
        
        row.querySelector('.policy-type-name').style.display = 'block';
        row.querySelector('.policy-type-description').style.display = 'block';
        
        // İşlem butonlarını geri yükle
        const actionCell = row.querySelector('.btn-group');
        const policyCount = row.querySelector('.badge').textContent.split(' ')[0];
        
        actionCell.innerHTML = `
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="editPolicyType(${id})" title="Düzenle">
                <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
            </button>
            ${policyCount == 0 ? `<button type="button" class="btn btn-outline-danger btn-sm" onclick="deletePolicyType(${id})" title="Sil">
                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
            </button>` : ''}
        `;
        // Dinamik eklenen lucide ikonlarını yeniden oluştur
        if (window.lucide && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    };

    window.updatePolicyTypeStatus = function(id, isActive) {
        // Boolean değeri kontrol et ve düzelt
        let value;
        if (typeof isActive === 'boolean') {
            value = isActive ? 1 : 0;
        } else if (typeof isActive === 'string') {
            value = isActive === 'true' || isActive === '1' ? 1 : 0;
        } else {
            value = isActive ? 1 : 0;
        }
        
        // CSRF token'ı al
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token bulunamadı');
            showToast('CSRF token bulunamadı', 'error');
            return;
        }
        
        console.log('Switch durumu güncelleniyor:', { id, isActive, value, type: typeof isActive });
        
        fetch(`/settings/policy-types/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_active: value })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Başarılı güncelleme - switch'i güncelle
                const row = document.getElementById('policyTypeRow' + id);
                const switchInput = row.querySelector('.form-check-input');
                switchInput.checked = isActive;
                
                // Başarı mesajı göster
                showToast('Poliçe türü durumu güncellendi', 'success');
            } else {
                // Hata durumunda switch'i eski haline döndür
                const row = document.getElementById('policyTypeRow' + id);
                const switchInput = row.querySelector('.form-check-input');
                switchInput.checked = !isActive;
                
                showToast('Güncelleme başarısız', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Hata durumunda switch'i eski haline döndür
            const row = document.getElementById('policyTypeRow' + id);
            const switchInput = row.querySelector('.form-check-input');
            switchInput.checked = !isActive;
            
            showToast('Bir hata oluştu', 'error');
        });
    };

    window.updatePolicyType = function(id, value, field) {
        fetch(`/settings/policy-types/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Başarılı güncelleme
                const row = document.getElementById('policyTypeRow' + id);
                if (field === 'name') {
                    row.querySelector('.policy-type-name').style.display = 'block';
                    row.querySelector('.policy-type-edit').style.display = 'none';
                    row.querySelector('.policy-type-name strong').textContent = value;
                } else if (field === 'description') {
                    row.querySelector('.policy-type-description').style.display = 'block';
                    row.querySelector('.policy-type-edit').style.display = 'none';
                    row.querySelector('.policy-type-description span').textContent = value || 'Açıklama yok';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    };

    window.deletePolicyType = function(id) {
        if (confirm('Bu poliçe türünü silmek istediğinizden emin misiniz?')) {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            
            fetch(`/settings/policy-types/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('policyTypeRow' + id).remove();
                    showToast('Poliçe türü silindi', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Silme işlemi başarısız', 'error');
            });
        }
    };

    // Sigorta Şirketi Yönetimi Fonksiyonları
    window.showAddCompanyForm = function() {
        document.getElementById('addCompanyForm').style.display = 'block';
    };

    window.hideAddCompanyForm = function() {
        document.getElementById('addCompanyForm').style.display = 'none';
    };

    window.editCompany = function(id) {
        const row = document.getElementById('companyRow' + id);
        const nameDiv = row.querySelector('.company-name');
        const descriptionDiv = row.querySelector('.company-description');
        
        // Mevcut değerleri al
        const currentName = nameDiv.getAttribute('data-name');
        const currentDescription = descriptionDiv.getAttribute('data-description');
        
        // Düzenleme moduna geç
        nameDiv.style.display = 'none';
        descriptionDiv.style.display = 'none';
        
        // Input alanlarını bul ve değerleri doldur
        const nameInput = row.querySelector('.company-edit input');
        const descriptionInput = row.querySelectorAll('.company-edit input')[1];
        
        if (nameInput) nameInput.value = currentName;
        if (descriptionInput) descriptionInput.value = currentDescription;
        
        // Düzenleme alanlarını göster
        row.querySelectorAll('.company-edit').forEach(editDiv => {
            editDiv.style.display = 'block';
        });
        
        // İşlem butonlarını güncelle
        const actionCell = row.querySelector('.btn-group');
        actionCell.innerHTML = `
            <button type="button" class="btn btn-success btn-sm" onclick="saveCompany(${id})" title="Kaydet">
                <i data-lucide="check" style="width: 14px; height: 14px;"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelEditCompany(${id})" title="İptal">
                <i data-lucide="x" style="width: 14px; height: 14px;"></i>
            </button>
        `;
        // Dinamik eklenen lucide ikonlarını yeniden oluştur
        if (window.lucide && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    };

    window.editCompanyName = function(id, currentName) {
        const row = document.getElementById('companyRow' + id);
        const nameDiv = row.querySelector('.company-name');
        const currentNameValue = nameDiv.getAttribute('data-name');
        
        nameDiv.style.display = 'none';
        row.querySelector('.company-edit').style.display = 'block';
        row.querySelector('.company-edit input').value = currentNameValue;
        row.querySelector('.company-edit input').focus();
    };

    window.editCompanyDescription = function(id, currentDescription) {
        const row = document.getElementById('companyRow' + id);
        const descriptionDiv = row.querySelector('.company-description');
        const currentDesc = descriptionDiv.getAttribute('data-description');
        
        descriptionDiv.style.display = 'none';
        row.querySelector('.company-edit').style.display = 'block';
        row.querySelector('.company-edit input').value = currentDesc;
        row.querySelector('.company-edit input').focus();
    };

    window.saveCompany = function(id) {
        const row = document.getElementById('companyRow' + id);
        const nameInput = row.querySelector('.company-edit input').value;
        const descriptionInput = row.querySelectorAll('.company-edit input')[1]?.value || '';
        
        const newName = nameInput;
        const newDescription = descriptionInput;
        
        console.log('saveCompany çağrıldı:', { id, newName, newDescription });
        
        // CSRF token'ı al
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token bulunamadı');
            showToast('CSRF token bulunamadı', 'error');
            return;
        }
        
        // AJAX ile güncelle
        fetch(`/settings/insurance-companies/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                name: newName, 
                description: newDescription 
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Başarılı güncelleme
                const nameDiv = row.querySelector('.company-name');
                const descriptionDiv = row.querySelector('.company-description');
                
                // Data attribute'ları güncelle
                nameDiv.setAttribute('data-name', newName);
                descriptionDiv.setAttribute('data-description', newDescription);
                
                // Görünümü güncelle
                nameDiv.querySelector('strong').textContent = newName;
                descriptionDiv.querySelector('span').textContent = newDescription || 'Açıklama yok';
                
                // Normal moda dön
                cancelEditCompany(id);
                showToast('Sigorta şirketi güncellendi', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Güncelleme başarısız: ' + error.message, 'error');
        });
    };

    window.cancelEditCompany = function(id) {
        const row = document.getElementById('companyRow' + id);
        
        // Düzenleme modundan çık
        row.querySelectorAll('.company-edit').forEach(editDiv => {
            editDiv.style.display = 'none';
        });
        
        row.querySelector('.company-name').style.display = 'block';
        row.querySelector('.company-description').style.display = 'block';
        
        // İşlem butonlarını geri yükle
        const actionCell = row.querySelector('.btn-group');
        const policyCount = row.querySelector('.badge').textContent.split(' ')[0];
        
        actionCell.innerHTML = `
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="editCompany(${id})" title="Düzenle">
                <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
            </button>
            ${policyCount == 0 ? `<button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteCompany(${id})" title="Sil">
                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
            </button>` : ''}
        `;
        // Dinamik eklenen lucide ikonlarını yeniden oluştur
        if (window.lucide && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    };

    window.updateCompanyStatus = function(id, isActive) {
        // Boolean değeri kontrol et ve düzelt
        let value;
        if (typeof isActive === 'boolean') {
            value = isActive ? 1 : 0;
        } else if (typeof isActive === 'string') {
            value = isActive === 'true' || isActive === '1' ? 1 : 0;
        } else {
            value = isActive ? 1 : 0;
        }
        
        // CSRF token'ı al
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token bulunamadı');
            showToast('CSRF token bulunamadı', 'error');
            return;
        }
        
        console.log('Şirket switch durumu güncelleniyor:', { id, isActive, value, type: typeof isActive });
        
        fetch(`/settings/insurance-companies/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ is_active: value })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Başarılı güncelleme - switch'i güncelle
                const row = document.getElementById('companyRow' + id);
                const switchInput = row.querySelector('.form-check-input');
                switchInput.checked = isActive;
                
                // Başarı mesajı göster
                showToast('Sigorta şirketi durumu güncellendi', 'success');
            } else {
                // Hata durumunda switch'i eski haline döndür
                const row = document.getElementById('companyRow' + id);
                const switchInput = row.querySelector('.form-check-input');
                switchInput.checked = !isActive;
                
                showToast('Güncelleme başarısız', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Hata durumunda switch'i eski haline döndür
            const row = document.getElementById('companyRow' + id);
            const switchInput = row.querySelector('.form-check-input');
            switchInput.checked = !isActive;
            
            showToast('Bir hata oluştu', 'error');
        });
    };

    window.updateCompany = function(id, value, field) {
        fetch(`/settings/insurance-companies/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Başarılı güncelleme
                const row = document.getElementById('companyRow' + id);
                if (field === 'name') {
                    row.querySelector('.company-name').style.display = 'block';
                    row.querySelector('.company-edit').style.display = 'none';
                    row.querySelector('.company-name strong').textContent = value;
                } else if (field === 'description') {
                    row.querySelector('.company-description').style.display = 'block';
                    row.querySelector('.company-edit').style.display = 'none';
                    row.querySelector('.company-description span').textContent = value || 'Açıklama yok';
                }
            }
        })
        .catch(error => console.error('Error:', error));
    };

    window.deleteCompany = function(id) {
        if (confirm('Bu sigorta şirketini silmek istediğinizden emin misiniz?')) {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            
            fetch(`/settings/insurance-companies/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('companyRow' + id).remove();
                    showToast('Sigorta şirketi silindi', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Silme işlemi başarısız', 'error');
            });
        }
    };

    // Form submit fonksiyonları
    window.submitPolicyTypeForm = function() {
        const form = document.getElementById('addPolicyTypeFormElement');
        const formData = new FormData(form);
        formData.append('_token', getCsrfToken());

        fetchJson('{{ route("settings.policy-types.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(data => {
            if (data && data.success && data.data) {
                const table = getDataTable('#policyTypesTable');
                const newRowHtml = renderPolicyTypeRow(data.data);
                if (table) {
                    table.row.add($(newRowHtml)).draw(false);
                } else {
                    const tbody = document.querySelector('#policyTypesTable tbody');
                    if (tbody) {
                        tbody.insertAdjacentHTML('beforeend', newRowHtml);
                    }
                }
                reinitIcons();
                showToast('Poliçe türü başarıyla eklendi', 'success');
                hideAddPolicyTypeForm();
                form.reset();
            } else {
                showToast('Ekleme başarısız', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Bir hata oluştu: ' + error.message, 'error');
        });
    };

    window.submitCompanyForm = function() {
        const form = document.getElementById('addCompanyFormElement');
        const formData = new FormData(form);
        formData.append('_token', getCsrfToken());

        fetchJson('{{ route("settings.insurance-companies.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(data => {
            if (data && data.success && data.data) {
                const table = getDataTable('#companiesTable');
                const newRowHtml = renderCompanyRow(data.data);
                if (table) {
                    table.row.add($(newRowHtml)).draw(false);
                } else {
                    const tbody = document.querySelector('#companiesTable tbody');
                    if (tbody) {
                        tbody.insertAdjacentHTML('beforeend', newRowHtml);
                    }
                }
                reinitIcons();
                showToast('Sigorta şirketi başarıyla eklendi', 'success');
                hideAddCompanyForm();
                form.reset();
            } else {
                showToast('Ekleme başarısız', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Bir hata oluştu: ' + error.message, 'error');
        });
    };

    // DataTables entegrasyonu
    $(document).ready(function() {
        console.log('DataTables başlatılıyor...');
        
        // Poliçe Türleri Tablosu
        if ($('#policyTypesTable').length) {
            console.log('Poliçe Türleri tablosu bulundu, DataTable başlatılıyor...');
            
            // Önce mevcut DataTable'ı yok et
            if ($.fn.DataTable.isDataTable('#policyTypesTable')) {
                $('#policyTypesTable').DataTable().destroy();
                console.log('Mevcut DataTable yok edildi');
            }
            
            // Yeni DataTable başlat
            const policyTypesTable = $('#policyTypesTable').DataTable({
                language: {
                    // Türkçe dil ayarları inline olarak tanımlandı
                    "sDecimal":        ",",
                    "sEmptyTable":    "Tabloda herhangi bir veri mevcut değil",
                    "sInfo":          "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                    "sInfoEmpty":     "Kayıt yok",
                    "sInfoFiltered":  "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix":   "",
                    "sInfoThousands":  ".",
                    "sLengthMenu":    "Sayfada _MENU_ kayıt göster",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing":    "İşleniyor...",
                    "sSearch":        "Ara:",
                    "sZeroRecords":   "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst":    "İlk",
                        "sLast":     "Son",
                        "sNext":     "Sonraki",
                        "sPrevious": "Önceki"
                    }
                },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [2, 4] }, // Durum ve İşlemler sütunları sıralanamaz
                    { orderable: true, targets: [0, 1, 3] } // Poliçe Türü, Açıklama ve Kullanım sütunları sıralanabilir
                ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                responsive: true,
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true
            });
            
            console.log('Poliçe Türleri DataTable başlatıldı:', policyTypesTable);
        } else {
            console.log('Poliçe Türleri tablosu bulunamadı');
        }

        // Sigorta Şirketleri Tablosu
        if ($('#companiesTable').length) {
            console.log('Sigorta Şirketleri tablosu bulundu, DataTable başlatılıyor...');
            
            // Önce mevcut DataTable'ı yok et
            if ($.fn.DataTable.isDataTable('#companiesTable')) {
                $('#companiesTable').DataTable().destroy();
                console.log('Mevcut DataTable yok edildi');
            }
            
            // Yeni DataTable başlat
            const companiesTable = $('#companiesTable').DataTable({
                language: {
                    // Türkçe dil ayarları inline olarak tanımlandı
                    "sDecimal":        ",",
                    "sEmptyTable":    "Tabloda herhangi bir veri mevcut değil",
                    "sInfo":          "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
                    "sInfoEmpty":     "Kayıt yok",
                    "sInfoFiltered":  "(_MAX_ kayıt içerisinden bulunan)",
                    "sInfoPostFix":   "",
                    "sInfoThousands":  ".",
                    "sLengthMenu":    "Sayfada _MENU_ kayıt göster",
                    "sLoadingRecords": "Yükleniyor...",
                    "sProcessing":    "İşleniyor...",
                    "sSearch":        "Ara:",
                    "sZeroRecords":   "Eşleşen kayıt bulunamadı",
                    "oPaginate": {
                        "sFirst":    "İlk",
                        "sLast":     "Son",
                        "sNext":     "Sonraki",
                        "sPrevious": "Önceki"
                    }
                },
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [2, 4] }, // Durum ve İşlemler sütunları sıralanamaz
                    { orderable: true, targets: [0, 1, 3] } // Şirket Adı, Açıklama ve Kullanım sütunları sıralanabilir
                ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                responsive: true,
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true
            });
            
            console.log('Sigorta Şirketleri DataTable başlatıldı:', companiesTable);
        } else {
            console.log('Sigorta Şirketleri tablosu bulunamadı');
        }
        
        console.log('DataTables başlatma tamamlandı');
    });
});
</script>
@endpush




