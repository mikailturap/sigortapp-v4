@extends('layouts.app')

@section('title', 'Yeni Müşteri')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i class="fas fa-user-plus text-muted me-2"></i>
                Yeni Müşteri
            </h1>
            <p class="text-muted mb-0 mt-1 small">Yeni müşteri bilgilerini girin</p>
        </div>
        <div>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Geri
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Müşteri Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_title" class="form-label">Müşteri Ünvanı *</label>
                                <input type="text" class="form-control @error('customer_title') is-invalid @enderror" 
                                       id="customer_title" name="customer_title" value="{{ old('customer_title') }}" required>
                                @error('customer_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_identity_number" class="form-label">TC/Vergi No *</label>
                                <input type="text" class="form-control @error('customer_identity_number') is-invalid @enderror" 
                                       id="customer_identity_number" name="customer_identity_number" value="{{ old('customer_identity_number') }}" required>
                                @error('customer_identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" placeholder="0___ ___ __ __">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Doğum Tarihi</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adres</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_type" class="form-label">Müşteri Türü *</label>
                            <select class="form-select @error('customer_type') is-invalid @enderror" 
                                    id="customer_type" name="customer_type" required>
                                <option value="">Seçiniz</option>
                                <option value="bireysel" {{ old('customer_type') == 'bireysel' ? 'selected' : '' }}>Bireysel</option>
                                <option value="kurumsal" {{ old('customer_type') == 'kurumsal' ? 'selected' : '' }}>Kurumsal</option>
                            </select>
                            @error('customer_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Müşteriyi Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// TC/Vergi no formatlaması
document.getElementById('customer_identity_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 10) {
        // TC Kimlik No formatı: 12345678901
        e.target.value = value;
    } else if (value.length <= 11) {
        // Vergi No formatı: 1234567890
        e.target.value = value;
    }
});

// TCKN/VKN doğrulama yordamları
function isValidVKN(vkn){
    if(!/^\d{10}$/.test(vkn)) return false;
    const d=vkn.split('').map(n=>parseInt(n,10));
    const last=d[9];
    let sum=0;
    for(let i=0;i<9;i++){
        const tmp=(d[i]+10-(i+1))%10;
        if(tmp===9){ sum+=tmp; }
        else { const mod=(tmp*Math.pow(2,9-i))%9; sum+=mod; }
    }
    const check=(10-(sum%10))%10;
    return last===check;
}
function isValidTCKN(t){
    if(!/^\d{11}$/.test(t)) return false;
    if(!/^[1-9][0-9]{9}[02468]$/.test(t)) return false;
    const d=t.split('').map(n=>parseInt(n,10));
    const o=d[0]+d[2]+d[4]+d[6]+d[8];
    const e=d[1]+d[3]+d[5]+d[7];
    const c1=(10-((o*3+e)%10))%10;
    const c2=(10-((((e+c1)*3)+o)%10))%10;
    return d[9]===c1 && d[10]===c2;
}

// Telefon formatlaması
function formatPhoneTR(value) {
    const raw = (value || '').replace(/\D/g, '');
    let digits = raw;
    if (digits.length === 0) return '';
    if (digits[0] !== '0') digits = '0' + digits;
    digits = digits.slice(0, 11);
    if (digits.length <= 1) return digits;
    if (digits.length <= 4) return digits.slice(0,1) + digits.slice(1);
    if (digits.length <= 7) return digits.slice(0,1) + digits.slice(1,4) + ' ' + digits.slice(4);
    if (digits.length <= 9) return digits.slice(0,1) + digits.slice(1,4) + ' ' + digits.slice(4,7) + ' ' + digits.slice(7);
    return digits.slice(0,1) + digits.slice(1,4) + ' ' + digits.slice(4,7) + ' ' + digits.slice(7,9) + ' ' + digits.slice(9,11);
}

const phoneInput = document.getElementById('phone');
if (phoneInput) {
    phoneInput.addEventListener('input', function(e){ e.target.value = formatPhoneTR(e.target.value); });
    phoneInput.addEventListener('paste', function(e){
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text');
        e.target.value = formatPhoneTR(text);
    });
}

// Submit öncesi kimlik doğrulama (format + algoritma)
const formEl = document.querySelector('form[action*="customers"][method="POST"]');
if (formEl) {
    formEl.addEventListener('submit', function(e){
        const idEl = document.getElementById('customer_identity_number');
        const v = (idEl && idEl.value || '').trim();
        if (!v) return;
        if (v.length===10){
            if(!/^\d{10}$/.test(v) || !isValidVKN(v)) { e.preventDefault(); alert('Vergi No geçersiz. Lütfen kontrol edin.'); }
        } else if (v.length===11){
            if(!/^[1-9][0-9]{9}[02468]$/.test(v) || !isValidTCKN(v)) { e.preventDefault(); alert('TCKN geçersiz. Lütfen kontrol edin.'); }
        } else {
            e.preventDefault(); alert('TC için 11 hane, VKN için 10 hane girilmelidir.');
        }
    });
}
</script>
@endpush
