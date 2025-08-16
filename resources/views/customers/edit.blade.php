@extends('layouts.app')

@section('title', 'Müşteri Düzenle - ' . $customer->customer_title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i class="fas fa-user-edit text-muted me-2"></i>
                Müşteri Düzenle
            </h1>
            <p class="text-muted mb-0 mt-1 small">{{ $customer->customer_title }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-secondary">
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
                    <form action="{{ route('customers.update', $customer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_title" class="form-label">Müşteri Ünvanı *</label>
                                <input type="text" class="form-control @error('customer_title') is-invalid @enderror" 
                                       id="customer_title" name="customer_title" value="{{ old('customer_title', $customer->customer_title) }}" required>
                                @error('customer_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_identity_number" class="form-label">TC/Vergi No *</label>
                                <input type="text" class="form-control @error('customer_identity_number') is-invalid @enderror" 
                                       id="customer_identity_number" name="customer_identity_number" value="{{ old('customer_identity_number', $customer->customer_identity_number) }}" required>
                                @error('customer_identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="0___ ___ __ __">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Doğum Tarihi</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date', optional($customer->birth_date)->format('Y-m-d')) }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adres</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_type" class="form-label">Müşteri Türü *</label>
                            <select class="form-select @error('customer_type') is-invalid @enderror" 
                                    id="customer_type" name="customer_type" required>
                                <option value="">Seçiniz</option>
                                <option value="bireysel" {{ old('customer_type', $customer->customer_type) == 'bireysel' ? 'selected' : '' }}>Bireysel</option>
                                <option value="kurumsal" {{ old('customer_type', $customer->customer_type) == 'kurumsal' ? 'selected' : '' }}>Kurumsal</option>
                            </select>
                            @error('customer_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Değişiklikleri Kaydet
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

// Telefon formatlaması
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value.length <= 3) {
            e.target.value = value;
        } else if (value.length <= 6) {
            e.target.value = value.slice(0, 3) + ' ' + value.slice(3);
        } else if (value.length <= 8) {
            e.target.value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
        } else {
            e.target.value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 8) + ' ' + value.slice(8, 10);
        }
    }
});
</script>
@endpush
