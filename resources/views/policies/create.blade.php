@extends('layouts.app')

@section('title', 'Yeni Poliçe Oluştur')

@section('suppress_global_errors')
@endsection

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="h4 font-weight-bold">
            Yeni Poliçe Oluştur
        </h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Formda hatalar var, lütfen düzeltin:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('policies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Müşteri Bilgileri</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_identity_number" class="form-label">TC/Vergi No</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="customer_identity_number" name="customer_identity_number" value="{{ old('customer_identity_number') }}" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="checkCustomerIdentity()">Kontrol Et</button>
                            </div>
                            <div class="form-text">10 hane girilirse VKN, 11 hane girilirse TCKN doğrulaması yapılır.</div>
                            <div id="identityCheckResult" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_title" class="form-label">Müşteri Ünvan</label>
                            <input type="text" class="form-control" id="customer_title" name="customer_title" value="{{ old('customer_title') }}" required @if(!old('customer_title')) readonly @endif>
                            <div class="form-text">Önce TC/Vergi No girin ve kontrol edin.</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Müşteri Telefon</label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="0XXX XXX XX XX" value="{{ old('customer_phone') }}" required @if(!old('customer_title')) readonly @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_birth_date" class="form-label">Doğum Tarihi</label>
                                    <input type="date" class="form-control" id="customer_birth_date" name="customer_birth_date" value="{{ old('customer_birth_date') }}" required @if(!old('customer_title')) readonly @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_type" class="form-label">Müşteri Türü</label>
                                    <select class="form-select" id="customer_type" name="customer_type" required @if(!old('customer_title')) disabled @endif>
                                        <option value="">Seçiniz</option>
                                        <option value="bireysel" @selected(old('customer_type') === 'bireysel')>Bireysel</option>
                                        <option value="kurumsal" @selected(old('customer_type') === 'kurumsal')>Kurumsal</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Adres</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required @if(!old('customer_title')) readonly @endif>{{ old('customer_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Sigorta Ettiren Bilgileri <small class="text-muted">(boş bırakılırsa Müşteri bilgileri kullanılır)</small></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="insured_name" class="form-label">Sigorta Ettiren Ünvan</label>
                            <input type="text" class="form-control" id="insured_name" name="insured_name" value="{{ old('insured_name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="insured_phone" class="form-label">Sigorta Ettiren Telefon</label>
                            <input type="text" class="form-control" id="insured_phone" name="insured_phone" placeholder="0XXX XXX XX XX" value="{{ old('insured_phone') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Poliçe Detayları</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_type" class="form-label">Poliçe Türü</label>
                                    <select class="form-select" id="policy_type" name="policy_type" required>
                                        <option value="">Poliçe türü seçin...</option>
                                        @foreach(\App\Models\PolicyType::active()->ordered()->get() as $policyType)
                                            <option value="{{ $policyType->name }}" @selected(old('policy_type') === $policyType->name)>{{ $policyType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_number" class="form-label">Poliçe No</label>
                                    <input type="text" class="form-control" id="policy_number" name="policy_number" value="{{ old('policy_number') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="policy_company" class="form-label">Poliçe Şirketi</label>
                            <select class="form-select" id="policy_company" name="policy_company">
                                <option value="">Sigorta şirketi seçin...</option>
                                @foreach(\App\Models\InsuranceCompany::active()->ordered()->get() as $company)
                                    <option value="{{ $company->name }}" @selected(old('policy_company') === $company->name)>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="plate_or_other" class="form-label">
                                <i data-lucide="car" class="text-muted me-1" style="width: 16px; height: 16px;"></i>
                                Plaka/Diğer
                            </label>
                            <input type="text" class="form-control" id="plate_or_other" name="plate_or_other" 
                                   placeholder="Araç plakası veya diğer tanımlayıcı bilgi..." value="{{ old('plate_or_other') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">Tanzim Tarihi</label>
                                    <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{ old('issue_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="document_info" class="form-label">Belge Seri/Diğer/UAVT</label>
                            <input type="text" class="form-control" id="document_info" name="document_info" value="{{ old('document_info') }}">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">TARSİM Bilgileri <small class="text-muted">(Poliçe Türü TARSİM ise doldurun)</small></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tarsim_business_number" class="form-label">TARSİM İşletme No</label>
                                    <input type="text" class="form-control" id="tarsim_business_number" name="tarsim_business_number" value="{{ old('tarsim_business_number') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tarsim_animal_number" class="form-label">TARSİM Hayvan No</label>
                                    <input type="text" class="form-control" id="tarsim_animal_number" name="tarsim_animal_number" value="{{ old('tarsim_animal_number') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">Dosyalar</div>
                    <div class="card-body">
                        <div class="mb-2 text-muted small">En fazla 4 dosya, her biri en fazla 5 MB. İzinli türler: pdf, csv, xlsx, xls, doc, docx, jpg, jpeg, png, rar</div>
                        <input type="file" name="files[]" id="files_input" class="form-control" multiple accept=".pdf,.csv,.xlsx,.xls,.doc,.docx,.jpg,.jpeg,.png,.rar">
                        <div class="form-text">Toplam 4 dosya sınırı. Her dosya 5MB veya daha küçük olmalı.</div>
                        <ul id="selected_files_list" class="list-group mt-3"></ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gelir Yönetimi Alanları -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-money-bill-wave me-2"></i>Gelir Yönetimi
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="policy_premium" class="form-label">Poliçe Primi (₺)</label>
                                    <input type="number" class="form-control" id="policy_premium" name="policy_premium" 
                                           step="0.01" min="0" placeholder="0.00" value="{{ old('policy_premium') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="commission_rate" class="form-label">Komisyon Oranı (%)</label>
                                    <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                           step="0.01" min="0" max="100" placeholder="0.00" value="{{ old('commission_rate') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="payment_due_date" class="form-label">Ödeme Vade Tarihi</label>
                                    <input type="date" class="form-control" id="payment_due_date" name="payment_due_date" value="{{ old('payment_due_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="tax_rate" class="form-label">Vergi Oranı (%)</label>
                                    <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                           step="0.01" min="0" max="100" value="{{ old('tax_rate', '18.00') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_notes" class="form-label">Ödeme Notları</label>
                                    <textarea class="form-control" id="payment_notes" name="payment_notes" 
                                              rows="2" placeholder="Ödeme ile ilgili notlar...">{{ old('payment_notes') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Fatura Numarası</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" 
                                           placeholder="Fatura numarası..." value="{{ old('invoice_number') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('policies.index') }}" class="btn btn-secondary me-2">İptal</a>
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
// Kimlik doğrulama yardımcıları
function isDigits(str) {
    return /^\d+$/.test(str);
}

function isValidVKN(vkn) {
    if (!/^\d{10}$/.test(vkn)) return false;
    const digits = vkn.split('').map(d => parseInt(d, 10));
    const lastDigit = digits[9];
    let sum = 0;
    for (let i = 0; i < 9; i++) {
        const digit = digits[i];
        const tmp = (digit + 10 - (i + 1)) % 10;
        if (tmp === 9) {
            sum += tmp;
        } else {
            const pow2 = Math.pow(2, 9 - i); // 2^(10-(i+1)) = 2^(9-i)
            const mod = (tmp * pow2) % 9;
            sum += mod;
        }
    }
    const check = (10 - (sum % 10)) % 10;
    return lastDigit === check;
}

function isValidTCKN(tckn) {
    if (!/^\d{11}$/.test(tckn)) return false;
    const d = tckn.split('').map(n => parseInt(n, 10));
    // Format kontrolünü ayrıca yapan regex zaten var ama burada da güvence olsun
    if (!/^[1-9][0-9]{9}[02468]$/.test(tckn)) return false;
    const o = d[0] + d[2] + d[4] + d[6] + d[8]; // tek indexler (1.,3.,5.,7.,9. haneler)
    const e = d[1] + d[3] + d[5] + d[7];       // çift indexler (2.,4.,6.,8. haneler)
    const c1 = (10 - ((o * 3 + e) % 10)) % 10;
    const c2 = (10 - ((((e + c1) * 3) + o) % 10)) % 10;
    return d[9] === c1 && d[10] === c2;
}

// Telefon formatlama yardımcıları (TR: 3-3-2-2)
function formatPhoneTR(value) {
    // Başına otomatik 0 ekleyelim, görüntüde de 0 ile başlasın
    const raw = (value || '').replace(/\D/g, '');
    let digits = raw;
    if (digits.length === 0) return '';
    if (digits[0] !== '0') digits = '0' + digits;
    digits = digits.slice(0, 11); // 0 + 10 hane
    // Görsel format: 0___ ___ __ __ (0 XXX XXX XX XX)
    if (digits.length <= 1) return digits;
    if (digits.length <= 4) return digits.slice(0,1) + digits.slice(1);
    if (digits.length <= 7) return digits.slice(0,1) + digits.slice(1,4) + ' ' + digits.slice(4);
    if (digits.length <= 9) return digits.slice(0,1) + digits.slice(1,4) + ' ' + digits.slice(4,7) + ' ' + digits.slice(7);
    return digits.slice(0,1) + digits.slice(1,4) + ' ' + digits.slice(4,7) + ' ' + digits.slice(7,9) + ' ' + digits.slice(9,11);
}

// Yeni TC/Vergi sorgusunda önce müşteri alanlarını ve uyarıyı sıfırla
function resetCustomerFieldsBeforeCheck() {
    const resultDiv = document.getElementById('identityCheckResult');
    if (resultDiv) { resultDiv.innerHTML = ''; }

    const titleEl = document.getElementById('customer_title');
    const phoneEl = document.getElementById('customer_phone');
    const addressEl = document.getElementById('customer_address');
    const birthEl = document.getElementById('customer_birth_date');
    const typeEl = document.getElementById('customer_type');

    [titleEl, phoneEl, addressEl, birthEl].forEach(function(el){
        if (!el) return;
        el.readOnly = false;
        if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
            el.value = '';
        }
    });
    if (typeEl) { typeEl.disabled = true; typeEl.value = ''; }
}

function checkCustomerIdentity() {
    const identityNumber = document.getElementById('customer_identity_number').value.trim();
    if (!identityNumber) {
        alert('Lütfen TC/Vergi no girin');
        return;
    }

    // Her yeni sorguda önce formu temizle
    resetCustomerFieldsBeforeCheck();

    // 1) Format kontrolü ve algoritma doğrulaması
    const resultDiv = document.getElementById('identityCheckResult');
    const onlyDigits = /^\d+$/.test(identityNumber);
    if (!onlyDigits) {
        resultDiv.innerHTML = '<div class="alert alert-warning alert-sm">Sadece rakam giriniz.</div>';
        return;
    }
    if (identityNumber.length === 10) {
        // VKN format: ^[0-9]{10}$
        if (!/^\d{10}$/.test(identityNumber)) {
            resultDiv.innerHTML = '<div class="alert alert-warning alert-sm">Vergi No formatı geçersiz.</div>';
            return;
        }
        if (!isValidVKN(identityNumber)) {
            resultDiv.innerHTML = '<div class="alert alert-danger alert-sm">Vergi No algoritma doğrulaması başarısız.</div>';
            return;
        }
    } else if (identityNumber.length === 11) {
        // TCKN format: ^[1-9]{1}[0-9]{9}[02468]{1}$
        if (!/^[1-9]{1}[0-9]{9}[02468]{1}$/.test(identityNumber)) {
            resultDiv.innerHTML = '<div class="alert alert-warning alert-sm">TCKN formatı geçersiz.</div>';
            return;
        }
        if (!isValidTCKN(identityNumber)) {
            resultDiv.innerHTML = '<div class="alert alert-danger alert-sm">TCKN algoritma doğrulaması başarısız.</div>';
            return;
        }
    } else {
        resultDiv.innerHTML = '<div class="alert alert-warning alert-sm">TC için 11 hane, VKN için 10 hane giriniz.</div>';
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    

    fetch('/policies/check-customer-identity', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ customer_identity_number: identityNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.exists) {
                resultDiv.innerHTML = `
                    <div class="alert alert-warning alert-sm">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        <strong>Dikkat!</strong> Bu TC/Vergi no ile kayıtlı müşteri bulundu: <strong>${data.customer.customer_title}</strong>
                        <br><small class="text-muted">Müşteri bilgileri otomatik olarak güncellenecek.</small>
                    </div>
                `;
                
                // Müşteri bilgilerini doldur ve kilitle
                const titleEl = document.getElementById('customer_title');
                const phoneEl = document.getElementById('customer_phone');
                const addressEl = document.getElementById('customer_address');
                const birthEl = document.getElementById('customer_birth_date');

                if (titleEl) { titleEl.value = data.customer.customer_title || ''; titleEl.readOnly = true; }
                if (phoneEl) { phoneEl.value = data.customer.phone || ''; phoneEl.readOnly = true; }
                if (addressEl) { addressEl.value = data.customer.address || ''; addressEl.readOnly = true; }
                if (birthEl) { birthEl.value = (data.customer.customer_birth_date || '').slice(0,10); birthEl.readOnly = true; }
                const typeSel = document.getElementById('customer_type');
                if (typeSel) { typeSel.value = data.customer.customer_type || ''; typeSel.disabled = true; }
                const typeEl = document.getElementById('customer_type');
                if (typeEl) { typeEl.value = data.customer.customer_type || ''; typeEl.disabled = true; }
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-info alert-sm">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        <strong>Bilgi:</strong> Bu TC/Vergi no ile kayıtlı müşteri bulunamadı. Yeni müşteri oluşturulacak.
                    </div>
                `;
                // Yeni müşteri: alanları aktif et
                const titleEl = document.getElementById('customer_title');
                const phoneEl = document.getElementById('customer_phone');
                const addressEl = document.getElementById('customer_address');
                const birthEl = document.getElementById('customer_birth_date');
                if (titleEl) titleEl.readOnly = false;
                if (phoneEl) phoneEl.readOnly = false;
                if (addressEl) addressEl.readOnly = false;
                if (birthEl) birthEl.readOnly = false;
                const typeEl2 = document.getElementById('customer_type');
                if (typeEl2) typeEl2.disabled = false;
            }
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger alert-sm">
                    <i class="fa-solid fa-times-circle me-2"></i>
                    <strong>Hata:</strong> ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = `
            <div class="alert alert-danger alert-sm">
                <i class="fa-solid fa-times-circle me-2"></i>
                <strong>Hata:</strong> Bir hata oluştu
            </div>
        `;
    });
}

// TC/Vergi no alanında Enter tuşu ile kontrol
document.addEventListener('DOMContentLoaded', function() {
    const identityInput = document.getElementById('customer_identity_number');
    if (identityInput) {
        identityInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                checkCustomerIdentity();
            }
        });
    }
    // Telefon alanları otomatik format
    // Telefon inputlarını 0XXX XXX XX XX formatına zorla
    function formatToStandardPhone(value) {
        let d = (value || '').replace(/\D/g, '').slice(0, 11);
        if (!d) return '';
        if (d[0] !== '0') d = '0' + d.slice(0, 10);
        const first = d.slice(0, 4);      // 0 + 3 hane
        const mid = d.slice(4, 7);
        const part3 = d.slice(7, 9);
        const part4 = d.slice(9, 11);
        let out = first;
        if (mid) out += ' ' + mid;
        if (part3) out += ' ' + part3;
        if (part4) out += ' ' + part4;
        return out;
    }

    [document.getElementById('customer_phone'), document.getElementById('insured_phone')]
        .filter(Boolean)
        .forEach(function(el){
            el.addEventListener('input', function(e){ e.target.value = formatToStandardPhone(e.target.value); });
            el.addEventListener('paste', function(e){
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text');
                e.target.value = formatToStandardPhone(text);
            });
            // İlk değer varsa normalize et
            if (el.value) el.value = formatToStandardPhone(el.value);
        });
    
    // URL parametrelerinden müşteri bilgilerini doldur
    fillCustomerInfoFromURL();

    // TC/Vergi no yazılırken eski bilgiler formda kalmasın
    const identityInput2 = document.getElementById('customer_identity_number');
    if (identityInput2) {
        identityInput2.addEventListener('input', function(){
            resetCustomerFieldsBeforeCheck();
        });
    }
});

// URL parametrelerinden müşteri bilgilerini doldur
function fillCustomerInfoFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Müşteri bilgilerini doldur
    if (urlParams.has('customer_title')) {
        document.getElementById('customer_title').value = urlParams.get('customer_title');
    }
    
    if (urlParams.has('customer_identity_number')) {
        document.getElementById('customer_identity_number').value = urlParams.get('customer_identity_number');
    }
    
    if (urlParams.has('customer_phone')) {
        document.getElementById('customer_phone').value = urlParams.get('customer_phone');
    }
    
    if (urlParams.has('customer_address')) {
        document.getElementById('customer_address').value = urlParams.get('customer_address');
    }
    
    // Sigorta ettiren bilgilerini de doldur (eğer boşsa)
    if (urlParams.has('customer_title') && !document.getElementById('insured_name').value) {
        document.getElementById('insured_name').value = urlParams.get('customer_title');
    }
    
    if (urlParams.has('customer_phone') && !document.getElementById('insured_phone').value) {
        document.getElementById('insured_phone').value = urlParams.get('customer_phone');
    }
    
    // Eğer müşteri bilgileri doldurulduysa, TC/Vergi no kontrolü yap
    if (urlParams.has('customer_identity_number')) {
        setTimeout(() => {
            checkCustomerIdentity();
        }, 500);
    }
}
</script>
<script>
// Gelişmiş dosya seçim: listele + kaldır + 5MB + 4 dosya limiti
document.addEventListener('DOMContentLoaded', function(){
    const fileInput = document.getElementById('files_input');
    const listEl = document.getElementById('selected_files_list');
    const maxTotal = 4;
    let selectedFiles = [];

    function syncInputFiles() {
        if (!fileInput) return;
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function renderSelectedList() {
        if (!listEl) return;
        listEl.innerHTML = '';
        if (selectedFiles.length === 0) return;
        selectedFiles.forEach((file, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            const left = document.createElement('div');
            left.className = 'd-flex align-items-center gap-2';
            left.innerHTML = '<i data-lucide="file" class="text-muted" style="width:16px;height:16px;"></i>'
                + '<span>' + file.name + '</span>'
                + '<small class="text-muted">(' + (file.size/1024).toFixed(1) + ' KB)</small>';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-outline-danger';
            removeBtn.textContent = 'Kaldır';
            removeBtn.addEventListener('click', function(){
                selectedFiles.splice(idx, 1);
                syncInputFiles();
                renderSelectedList();
            });
            li.appendChild(left);
            li.appendChild(removeBtn);
            listEl.appendChild(li);
        });
        if (window.lucide) { window.lucide.createIcons(); }
    }

    if (fileInput && listEl) {
        fileInput.addEventListener('change', function(){
            const incoming = Array.from(fileInput.files || []);
            for (const file of incoming) {
                if (selectedFiles.length >= maxTotal) {
                    alert('En fazla 4 dosya yükleyebilirsiniz.');
                    break;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert(file.name + ' dosyası çok büyük. Maksimum 5MB olmalıdır.');
                    continue;
                }
                selectedFiles.push(file);
            }
            syncInputFiles();
            renderSelectedList();
        });
    }
});
</script>
@endpush
