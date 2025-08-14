@extends('layouts.app')

@section('title', 'Gelir Yönetimi - Poliçe Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-edit me-2"></i>Gelir Yönetimi - Poliçe Düzenle
                </h1>
                <div>
                    <a href="{{ route('revenue.policies') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Geri
                    </a>
                    <a href="{{ route('revenue.show', $policy) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-1"></i>Görüntüle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Poliçe Bilgileri -->
        <div class="col-12 col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-contract me-2"></i>Poliçe Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Müşteri Adı</label>
                            <p class="form-control-plaintext">{{ $policy->customer_title }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Poliçe Numarası</label>
                            <p class="form-control-plaintext">{{ $policy->policy_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Poliçe Türü</label>
                            <p class="form-control-plaintext">{{ $policy->policy_type }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sigorta Şirketi</label>
                            <p class="form-control-plaintext">{{ $policy->policy_company }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Başlangıç Tarihi</label>
                            <p class="form-control-plaintext">{{ $policy->start_date->format('d.m.Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bitiş Tarihi</label>
                            <p class="form-control-plaintext">{{ $policy->end_date->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gelir Özeti -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2"></i>Gelir Özeti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Poliçe Primi:</span>
                        <span class="fw-semibold">₺{{ number_format($policy->policy_premium ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Komisyon:</span>
                        <span class="text-info">₺{{ number_format($policy->commission_amount ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Net Gelir:</span>
                        <span class="text-success fw-semibold">₺{{ number_format($policy->net_revenue ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Vergi:</span>
                        <span class="text-warning">₺{{ number_format($policy->tax_amount ?? 0, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Toplam:</span>
                        <span class="fw-bold text-primary">₺{{ number_format($policy->total_amount ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gelir Yönetimi Formu -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-money-bill-wave me-2"></i>Gelir Bilgileri
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('revenue.update', $policy) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <!-- Poliçe Primi -->
                    <div class="col-md-6">
                        <label for="policy_premium" class="form-label">Poliçe Primi (₺)</label>
                        <input type="number" 
                               class="form-control @error('policy_premium') is-invalid @enderror" 
                               id="policy_premium" 
                               name="policy_premium" 
                               value="{{ old('policy_premium', $policy->policy_premium) }}" 
                               step="0.01" 
                               min="0"
                               onchange="calculateRevenue()">
                        @error('policy_premium')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Komisyon Oranı -->
                    <div class="col-md-6">
                        <label for="commission_rate" class="form-label">Komisyon Oranı (%)</label>
                        <input type="number" 
                               class="form-control @error('commission_rate') is-invalid @enderror" 
                               id="commission_rate" 
                               name="commission_rate" 
                               value="{{ old('commission_rate', $policy->commission_rate) }}" 
                               step="0.01" 
                               min="0" 
                               max="100"
                               onchange="calculateRevenue()">
                        @error('commission_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ödeme Durumu -->
                    <div class="col-md-6">
                        <label for="payment_status" class="form-label">Ödeme Durumu</label>
                        <select class="form-select @error('payment_status') is-invalid @enderror" 
                                id="payment_status" 
                                name="payment_status" 
                                onchange="togglePaymentFields()">
                            <option value="bekliyor" {{ old('payment_status', $policy->payment_status) == 'bekliyor' ? 'selected' : '' }}>Bekliyor</option>
                            <option value="ödendi" {{ old('payment_status', $policy->payment_status) == 'ödendi' ? 'selected' : '' }}>Ödendi</option>
                            <option value="gecikmiş" {{ old('payment_status', $policy->payment_status) == 'gecikmiş' ? 'selected' : '' }}>Gecikmiş</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ödeme Vade Tarihi -->
                    <div class="col-md-6">
                        <label for="payment_due_date" class="form-label">Ödeme Vade Tarihi</label>
                        <input type="date" 
                               class="form-control @error('payment_due_date') is-invalid @enderror" 
                               id="payment_due_date" 
                               name="payment_due_date" 
                               value="{{ old('payment_due_date', $policy->payment_due_date?->format('Y-m-d')) }}">
                        @error('payment_due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ödeme Tarihi -->
                    <div class="col-md-6 payment-field">
                        <label for="payment_date" class="form-label">Ödeme Tarihi</label>
                        <input type="date" 
                               class="form-control @error('payment_date') is-invalid @enderror" 
                               id="payment_date" 
                               name="payment_date" 
                               value="{{ old('payment_date', $policy->payment_date?->format('Y-m-d')) }}">
                        @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ödeme Yöntemi -->
                    <div class="col-md-6 payment-field">
                        <label for="payment_method" class="form-label">Ödeme Yöntemi</label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                id="payment_method" 
                                name="payment_method">
                            <option value="">Seçiniz</option>
                            <option value="Nakit" {{ old('payment_method', $policy->payment_method) == 'Nakit' ? 'selected' : '' }}>Nakit</option>
                            <option value="Banka Kartı" {{ old('payment_method', $policy->payment_method) == 'Banka Kartı' ? 'selected' : '' }}>Banka Kartı</option>
                            <option value="Kredi Kartı" {{ old('payment_method', $policy->payment_method) == 'Kredi Kartı' ? 'selected' : '' }}>Kredi Kartı</option>
                            <option value="Banka Transferi" {{ old('payment_method', $policy->payment_method) == 'Banka Transferi' ? 'selected' : '' }}>Banka Transferi</option>
                            <option value="Çek" {{ old('payment_method', $policy->payment_method) == 'Çek' ? 'selected' : '' }}>Çek</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fatura Numarası -->
                    <div class="col-md-6 payment-field">
                        <label for="invoice_number" class="form-label">Fatura Numarası</label>
                        <input type="text" 
                               class="form-control @error('invoice_number') is-invalid @enderror" 
                               id="invoice_number" 
                               name="invoice_number" 
                               value="{{ old('invoice_number', $policy->invoice_number) }}">
                        @error('invoice_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Vergi Oranı -->
                    <div class="col-md-6">
                        <label for="tax_rate" class="form-label">Vergi Oranı (%)</label>
                        <input type="number" 
                               class="form-control @error('tax_rate') is-invalid @enderror" 
                               id="tax_rate" 
                               name="tax_rate" 
                               value="{{ old('tax_rate', $policy->tax_rate ?? 18.00) }}" 
                               step="0.01" 
                               min="0" 
                               max="100"
                               onchange="calculateRevenue()">
                        @error('tax_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ödeme Notları -->
                    <div class="col-12">
                        <label for="payment_notes" class="form-label">Ödeme Notları</label>
                        <textarea class="form-control @error('payment_notes') is-invalid @enderror" 
                                  id="payment_notes" 
                                  name="payment_notes" 
                                  rows="3">{{ old('payment_notes', $policy->payment_notes) }}</textarea>
                        @error('payment_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('revenue.policies') }}" class="btn btn-outline-secondary">İptal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calculateRevenue() {
    const premium = parseFloat(document.getElementById('policy_premium').value) || 0;
    const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;

    // Komisyon hesapla
    const commission = (premium * commissionRate) / 100;
    
    // Net gelir hesapla
    const netRevenue = premium - commission;
    
    // Vergi hesapla
    const tax = (netRevenue * taxRate) / 100;
    
    // Toplam tutar hesapla
    const total = netRevenue + tax;

    // Sonuçları göster (opsiyonel)
    console.log('Komisyon:', commission);
    console.log('Net Gelir:', netRevenue);
    console.log('Vergi:', tax);
    console.log('Toplam:', total);
}

function togglePaymentFields() {
    const paymentStatus = document.getElementById('payment_status').value;
    const paymentFields = document.querySelectorAll('.payment-field');
    
    if (paymentStatus === 'ödendi') {
        paymentFields.forEach(field => field.style.display = 'block');
    } else {
        paymentFields.forEach(field => field.style.display = 'none');
    }
}

// Sayfa yüklendiğinde çalıştır
document.addEventListener('DOMContentLoaded', function() {
    togglePaymentFields();
    calculateRevenue();
});
</script>
@endpush
@endsection
