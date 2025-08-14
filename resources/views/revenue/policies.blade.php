@extends('layouts.app')

@section('title', 'Gelir Yönetimi - Poliçeler')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-list me-2"></i>Gelir Yönetimi - Poliçeler
                </h1>
                <div>
                    <a href="{{ route('revenue.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Geri
                    </a>
                    <a href="{{ route('revenue.reports') }}" class="btn btn-outline-info">
                        <i class="fas fa-chart-bar me-1"></i>Raporlar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('revenue.policies') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="payment_status" class="form-label">Ödeme Durumu</label>
                    <select name="payment_status" id="payment_status" class="form-select">
                        <option value="">Tümü</option>
                        <option value="bekliyor" {{ request('payment_status') == 'bekliyor' ? 'selected' : '' }}>Bekliyor</option>
                        <option value="ödendi" {{ request('payment_status') == 'ödendi' ? 'selected' : '' }}>Ödendi</option>
                        <option value="gecikmiş" {{ request('payment_status') == 'gecikmiş' ? 'selected' : '' }}>Gecikmiş</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="policy_type" class="form-label">Poliçe Türü</label>
                    <select name="policy_type" id="policy_type" class="form-select">
                        <option value="">Tümü</option>
                        <option value="Kasko" {{ request('policy_type') == 'Kasko' ? 'selected' : '' }}>Kasko</option>
                        <option value="Trafik" {{ request('policy_type') == 'Trafik' ? 'selected' : '' }}>Trafik</option>
                        <option value="Konut" {{ request('policy_type') == 'Konut' ? 'selected' : '' }}>Konut</option>
                        <option value="İşyeri" {{ request('policy_type') == 'İşyeri' ? 'selected' : '' }}>İşyeri</option>
                        <option value="Sağlık" {{ request('policy_type') == 'Sağlık' ? 'selected' : '' }}>Sağlık</option>
                        <option value="Hayat" {{ request('policy_type') == 'Hayat' ? 'selected' : '' }}>Hayat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filtrele
                    </button>
                    <a href="{{ route('revenue.policies') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-1"></i>Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Poliçe Listesi -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>Poliçe Listesi
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel me-1"></i>Excel
                </button>
                <button class="btn btn-outline-danger" onclick="exportToPdf()">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="revenue-policies-table">
                    <thead class="table-light">
                        <tr>
                            <th>Müşteri</th>
                            <th>Poliçe No</th>
                            <th>Tür</th>
                            <th>Şirket</th>
                            <th>Prim</th>
                            <th>Komisyon</th>
                            <th>Net Gelir</th>
                            <th>Ödeme Durumu</th>
                            <th>Vade Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($policies as $policy)
                        <tr>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $policy->customer_title }}</div>
                                    <small class="text-muted">{{ $policy->customer_phone }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $policy->policy_number }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis">{{ $policy->policy_type }}</span>
                            </td>
                            <td>{{ $policy->policy_company }}</td>
                            <td>
                                @if($policy->policy_premium)
                                    <span class="fw-semibold">₺{{ number_format($policy->policy_premium, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($policy->commission_amount)
                                    <span class="text-info">₺{{ number_format($policy->commission_amount, 2) }}</span>
                                    <br><small class="text-muted">(%{{ $policy->commission_rate }})</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($policy->net_revenue)
                                    <span class="fw-semibold text-success">₺{{ number_format($policy->net_revenue, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $policy->getPaymentStatusColor() }}">
                                    {{ $policy->getPaymentStatusText() }}
                                </span>
                                @if($policy->isPaymentOverdue())
                                    <br><small class="text-danger">Gecikmiş</small>
                                @endif
                            </td>
                            <td>
                                @if($policy->payment_due_date)
                                    <span class="{{ $policy->isPaymentOverdue() ? 'text-danger' : 'text-muted' }}">
                                        {{ $policy->payment_due_date->format('d.m.Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('revenue.show', $policy) }}" class="btn btn-sm btn-outline-info" title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('revenue.edit', $policy) }}" class="btn btn-sm btn-outline-primary" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Henüz poliçe bulunmuyor</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $policies->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportToExcel() {
    // Excel export işlemi
    alert('Excel export özelliği yakında eklenecek');
}

function exportToPdf() {
    // PDF export işlemi
    alert('PDF export özelliği yakında eklenecek');
}

// DataTable özellikleri
$(document).ready(function() {
    $('#revenue-policies-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/tr.json'
        },
        pageLength: 25,
        order: [[1, 'desc']],
        responsive: true
    });
});
</script>
@endpush
@endsection
