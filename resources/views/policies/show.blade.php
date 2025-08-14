@extends('layouts.app')

@section('title', 'Poliçe Detayı')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 font-weight-bold">
            Poliçe Detayı: {{ $policy->policy_number }}
        </h2>
        <a href="{{ route('policies.index') }}" class="btn btn-secondary">Geri Dön</a>
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
                <button type="submit" class="btn btn-sm btn-outline-secondary mt-2">Durum Değiştir</button>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('policies.edit', $policy) }}" class="btn btn-warning me-2">Düzenle</a>
        <a href="{{ route('policies.downloadPdf', $policy) }}" class="btn btn-info me-2">PDF İndir</a>
        <form action="{{ route('policies.sendReminderEmail', $policy) }}" method="POST" class="d-inline me-2">
            @csrf
            <button type="submit" class="btn btn-primary">Hatırlatıcı E-posta Gönder</button>
        </form>
        <form action="{{ route('policies.destroy', $policy) }}" method="POST" onsubmit="return confirm('Bu poliçeyi silmek istediğinizden emin misiniz?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Sil</button>
        </form>
    </div>
@endsection
