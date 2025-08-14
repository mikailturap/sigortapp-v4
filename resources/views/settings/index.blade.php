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

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="sliders" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Genel</h5>
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

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="message-square" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Otomatik SMS Bildirimleri</h5>
            </div>
            <p class="text-muted small mb-0 mt-2">Müşterilerinize poliçe bitim tarihlerinde otomatik SMS gönderin. SMS gönderimi harici bir servis sağlayıcı üzerinden yapılır.</p>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PATCH')
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="sms_enabled" name="sms_enabled" {{ ( ($settings['sms_enabled'] ?? '0') === '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="sms_enabled">Otomatik SMS Gönderimi Pasif</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-normal text-secondary" for="sms_reminder_days">Hatırlatma Günü</label>
                    <input type="number" min="1" max="60" class="form-control" id="sms_reminder_days" name="sms_reminder_days" value="{{ $settings['sms_reminder_days'] ?? '3' }}" placeholder="Poliçe bitimine kaç gün kala SMS gönderilecek?">
                </div>

                <div class="col-12">
                    <label class="form-label fw-normal text-secondary" for="sms_template">SMS Şablonu</label>
                    <textarea class="form-control" id="sms_template" name="sms_template" rows="3" placeholder="Sayın {customerTitle}, {policyNumber} numaralı poliçenizin bitiş tarihi olan {endDate} yaklaşıyor. Yenileme için bize ulaşabilirsiniz.">{{ $settings['sms_template'] ?? '' }}</textarea>
                    <div class="form-text">Kullanılabilir alanlar: {customerTitle}, {policyNumber}, {endDate}</div>
                </div>

                <div class="col-12">
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>SMS servis sağlayıcı bilgileri (API Anahtarı vb.) güvenlik nedeniyle sunucu tarafında ayarlanmalıdır. Bu ayarları değiştirmek için sistem yöneticinizle iletişime geçin.</div>
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

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="mail" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Günlük E-posta Özeti</h5>
            </div>
            <p class="text-muted small mb-0 mt-2">Poliçe takip listelerini her gün seçtiğiniz saatte şirket e-postanıza gönderelim.</p>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PATCH')
                <div class="col-md-6">
                    <label class="form-label fw-normal text-secondary" for="company_email">Şirket E-postası</label>
                    <input type="email" class="form-control" id="company_email" name="company_email" value="{{ $settings['company_email'] ?? '' }}" placeholder="mail@company.com">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-normal text-secondary" for="daily_summary_time">Gönderim Saati</label>
                    <input type="time" class="form-control" id="daily_summary_time" name="daily_summary_time" value="{{ $settings['daily_summary_time'] ?? '08:30' }}">
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

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="user" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Profil Bilgileri</h5>
            </div>
            <p class="text-muted small mb-0 mt-2">Hesabınızın profil bilgilerini ve e-posta adresini güncelleyin.</p>
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

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="lock" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Şifre Değiştir</h5>
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

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="message-circle" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Manuel Hatırlatma Ayarları (WhatsApp)</h5>
            </div>
            <p class="text-muted small mb-0 mt-2">Poliçe bitiminden kaç gün öncesine kadar "WhatsApp ile Hatırlat" butonunun gösterileceğini belirleyin.</p>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" class="row g-3">
                @csrf
                @method('PATCH')
                <div class="col-md-6">
                    <label class="form-label fw-normal text-secondary" for="whatsapp_reminder_days">Hatırlatma Gün Sayısı</label>
                    <input type="number" min="1" max="60" class="form-control" id="whatsapp_reminder_days" name="whatsapp_reminder_days" value="{{ $settings['whatsapp_reminder_days'] ?? '7' }}">
                    <div class="form-text">Örn: 7. Poliçe bitimine 7 gün veya daha az kala hatırlatma butonu görünür.</div>
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
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="eye-off" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Dashboard Veri Gizleme</h5>
            </div>
            <p class="text-muted small mb-0 mt-2">Dashboard'daki hassas verileri belirlenen tuş kombinasyonu ile gizleyin/açın</p>
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
                        <i data-lucide="info" class="me-2" style="width: 16px; height: 16px;"></i>
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
@endsection



