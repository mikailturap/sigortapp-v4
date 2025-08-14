@extends('layouts.app')

@section('title', 'Gelir Yönetimi - Poliçe Detayı')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-eye me-2"></i>Gelir Yönetimi - Poliçe Detayı
                </h1>
                <div>
                    <a href="{{ route('revenue.policies') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Geri
                    </a>
                    <a href="{{ route('revenue.edit', $policy) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Düzenle
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
                            <label class="form-label fw-semibold text-muted">Müşteri Adı</label>
                            <p class="form-control-plaintext fw-semibold">{{ $policy->customer_title }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Poliçe Numarası</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-light text-dark fs-6">{{ $policy->policy_number }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Poliçe Türü</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info-subtle text-info-emphasis">{{ $policy->policy_type }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Sigorta Şirketi</label>
                            <p class="form-control-plaintext fw-semibold">{{ $policy->policy_company }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Başlangıç Tarihi</label>
                            <p class="form-control-plaintext">{{ $policy->start_date->format('d.m.Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Bitiş Tarihi</label>
                            <p class="form-control-plaintext">{{ $policy->end_date->format('d.m.Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Müşteri Telefonu</label>
                            <p class="form-control-plaintext">{{ $policy->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Müşteri Adresi</label>
                            <p class="form-control-plaintext">{{ $policy->customer_address }}</p>
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
                        <span class="fw-semibold fs-5">₺{{ number_format($policy->policy_premium ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Komisyon (%{{ $policy->commission_rate ?? 0 }}):</span>
                        <span class="text-info fw-semibold">₺{{ number_format($policy->commission_amount ?? 0, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Net Gelir:</span>
                        <span class="text-success fw-bold fs-5">₺{{ number_format($policy->net_revenue ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Vergi (%{{ $policy->tax_rate ?? 18 }}):</span>
                        <span class="text-warning fw-semibold">₺{{ number_format($policy->tax_amount ?? 0, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Toplam:</span>
                        <span class="fw-bold text-primary fs-4">₺{{ number_format($policy->total_amount ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ödeme Bilgileri -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>Ödeme Bilgileri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Ödeme Durumu</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $policy->getPaymentStatusColor() }} fs-6">
                                    {{ $policy->getPaymentStatusText() }}
                                </span>
                                @if($policy->isPaymentOverdue())
                                    <br><small class="text-danger">Gecikmiş Ödeme</small>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Ödeme Vade Tarihi</label>
                            <p class="form-control-plaintext">
                                @if($policy->payment_due_date)
                                    <span class="{{ $policy->isPaymentOverdue() ? 'text-danger fw-bold' : 'text-muted' }}">
                                        {{ $policy->payment_due_date->format('d.m.Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Ödeme Tarihi</label>
                            <p class="form-control-plaintext">
                                @if($policy->payment_date)
                                    {{ $policy->payment_date->format('d.m.Y') }}
                                @else
                                    <span class="text-muted">Henüz ödenmedi</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Ödeme Yöntemi</label>
                            <p class="form-control-plaintext">
                                @if($policy->payment_method)
                                    {{ $policy->payment_method }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Fatura Numarası</label>
                            <p class="form-control-plaintext">
                                @if($policy->invoice_number)
                                    {{ $policy->invoice_number }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted">Ödeme Notları</label>
                            <p class="form-control-plaintext">
                                @if($policy->payment_notes)
                                    {{ $policy->payment_notes }}
                                @else
                                    <span class="text-muted">Not bulunmuyor</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ek Bilgiler -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Ek Bilgiler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Sigorta Ettiren</label>
                            <p class="form-control-plaintext">
                                @if($policy->insured_name)
                                    {{ $policy->insured_name }}
                                    @if($policy->insured_phone)
                                        <br><small class="text-muted">{{ $policy->insured_phone }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Müşteri ile aynı</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Plaka/Diğer</label>
                            <p class="form-control-plaintext">
                                @if($policy->plate_or_other)
                                    {{ $policy->plate_or_other }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">TARSİM İş No</label>
                            <p class="form-control-plaintext">
                                @if($policy->tarsim_business_number)
                                    {{ $policy->tarsim_business_number }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">TARSİM Hayvan No</label>
                            <p class="form-control-plaintext">
                                @if($policy->tarsim_animal_number)
                                    {{ $policy->tarsim_animal_number }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted">Belge Bilgileri</label>
                            <p class="form-control-plaintext">
                                @if($policy->document_info)
                                    {{ $policy->document_info }}
                                @else
                                    <span class="text-muted">Belirtilmemiş</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı İşlemler -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-bolt me-2"></i>Hızlı İşlemler
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="https://wa.me/{{ $policy->customer_phone }}?text=Sayın {{ $policy->customer_title }}, {{ $policy->policy_number }} numaralı poliçeniz hakkında bilgi almak istiyorum." target="_blank" class="btn btn-success w-100">
                        <i class="fab fa-whatsapp me-1"></i>WhatsApp
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="tel:{{ $policy->customer_phone }}" class="btn btn-primary w-100">
                        <i class="fas fa-phone me-1"></i>Ara
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('policies.downloadPdf', $policy) }}" class="btn btn-outline-danger w-100">
                        <i class="fas fa-file-pdf me-1"></i>PDF İndir
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('revenue.edit', $policy) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-edit me-1"></i>Düzenle
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
