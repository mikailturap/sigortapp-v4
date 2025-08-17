@extends('layouts.app')

@section('title', 'Poliçe Detayı')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i data-lucide="shield-check" class="text-muted me-2" style="width: 24px; height: 24px;"></i>
                Poliçe Detayı: {{ $policy->policy_number }}
            </h1>
            <p class="text-muted mb-0 mt-1 small">Poliçe bilgilerini görüntüleyin ve yönetin</p>
        </div>
        <a href="{{ route('policies.index') }}" class="btn btn-outline-secondary">
            <i data-lucide="arrow-left" class="me-2" style="width: 16px; height: 16px;"></i>Geri Dön
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Müşteri Bilgileri</div>
        <div class="card-body">
            <p><strong>Müşteri Ünvan:</strong> {{ $policy->customer_title }}</p>
            <p><strong>TC/Vergi No:</strong> {{ $policy->customer_identity_number }}</p>
            <p><strong>Müşteri Telefon:</strong> {{ $policy->customer_phone }}</p>
            <p><strong>Doğum Tarihi:</strong> {{ $policy->customer_birth_date }}</p>
            <p><strong>Adres:</strong> {{ $policy->customer_address }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Sigorta Ettiren Bilgileri</div>
        <div class="card-body">
            <p><strong>Sigorta Ettiren Ünvan:</strong> {{ $policy->insured_name ?? 'Müşteri ile aynı' }}</p>
            <p><strong>Sigorta Ettiren Telefon:</strong> {{ $policy->insured_phone ?? 'Müşteri ile aynı' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Poliçe Detayları</div>
        <div class="card-body">
            <p><strong>Poliçe Türü:</strong> {{ $policy->policy_type }}</p>
            <p><strong>Poliçe Şirketi:</strong> {{ $policy->policy_company ?? '-' }}</p>
            <p><strong>Poliçe No:</strong> {{ $policy->policy_number }}</p>
            <p><strong>Plaka/Diğer:</strong> {{ $policy->plate_or_other ?? '-' }}</p>
            <p><strong>Tanzim Tarihi:</strong> {{ $policy->issue_date }}</p>
            <p><strong>Başlangıç Tarihi:</strong> {{ $policy->start_date }}</p>
            <p><strong>Bitiş Tarihi:</strong> {{ $policy->end_date }}</p>
            <p><strong>Belge Seri/Diğer/UAVT:</strong> {{ $policy->document_info ?? '-' }}</p>
        </div>
    </div>

    @if($policy->policy_type == 'TARSİM')
        <div class="card mb-4">
            <div class="card-header">TARSİM Bilgileri</div>
            <div class="card-body">
                <p><strong>TARSİM İşletme No:</strong> {{ $policy->tarsim_business_number ?? '-' }}</p>
                <p><strong>TARSİM Hayvan No:</strong> {{ $policy->tarsim_animal_number ?? '-' }}</p>
            </div>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">Durum</div>
        <div class="card-body">
            <p><strong>Durum:</strong>
                @if($policy->status == 'aktif')
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Pasif</span>
                @endif
            </p>
            <form action="{{ route('policies.toggleStatus', $policy) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-outline-secondary mt-2">
                    <i data-lucide="toggle-right" class="me-1" style="width: 14px; height: 14px;"></i>Durum Değiştir
                </button>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('policies.edit', $policy) }}" class="btn btn-sm btn-outline-secondary me-2">
            <i data-lucide="edit-3" class="me-1" style="width: 14px; height: 14px;"></i>Düzenle
        </a>
        <a href="{{ route('policies.downloadPdf', $policy) }}" class="btn btn-sm btn-outline-secondary me-2">
            <i data-lucide="download" class="me-1" style="width: 14px; height: 14px;"></i>PDF İndir
        </a>
        <form action="{{ route('policies.destroy', $policy) }}" method="POST" onsubmit="return confirm('Bu poliçeyi silmek istediğinizden emin misiniz?');" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-secondary">
                <i data-lucide="trash-2" class="me-1" style="width: 14px; height: 14px;"></i>Sil
            </button>
        </form>
    </div>
@endsection
