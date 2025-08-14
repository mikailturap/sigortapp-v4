@extends('layouts.app')

@section('title', 'Maliyet Analizi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i class="fas fa-chart-pie text-muted me-2"></i>
                Maliyet Analizi
            </h1>
            <p class="text-muted mb-0 mt-1 small">Poliçe maliyetleri, kar marjları ve karlılık analizi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('revenue.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Geri
            </a>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label for="analysis_period" class="form-label">Analiz Periyodu</label>
                    <select class="form-select" id="analysis_period" name="analysis_period">
                        <option value="">Tümü</option>
                        <option value="aylık" {{ request('analysis_period') == 'aylık' ? 'selected' : '' }}>Aylık</option>
                        <option value="yıllık" {{ request('analysis_period') == 'yıllık' ? 'selected' : '' }}>Yıllık</option>
                        <option value="poliçe bazlı" {{ request('analysis_period') == 'poliçe bazlı' ? 'selected' : '' }}>Poliçe Bazlı</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Başlangıç</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Bitiş</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="min_profit" class="form-label">Min. Kar Marjı (%)</label>
                    <input type="number" class="form-control" id="min_profit" name="min_profit" 
                           value="{{ request('min_profit') }}" step="0.01" min="0" max="100">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filtrele
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Maliyet Analizi Listesi -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Maliyet Analizi ({{ $costAnalysis->count() }})</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="exportToPdf()">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Poliçe</th>
                            <th>Müşteri</th>
                            <th>Analiz Tarihi</th>
                            <th>Brüt Gelir</th>
                            <th>Toplam Maliyet</th>
                            <th>Net Gelir</th>
                            <th>Kar Marjı</th>
                            <th>Karlılık (%)</th>
                            <th>Rezervler</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costAnalysis as $analysis)
                            <tr>
                                <td>
                                    @if($analysis->policy)
                                        <div class="fw-semibold">{{ $analysis->policy->policy_number }}</div>
                                        <small class="text-muted">{{ $analysis->policy->policy_type }}</small>
                                    @else
                                        <span class="text-muted">Poliçe bulunamadı</span>
                                    @endif
                                </td>
                                <td>
                                    @if($analysis->policy)
                                        <div>{{ $analysis->policy->customer_title }}</div>
                                        <small class="text-muted">{{ $analysis->policy->customer_identity_number }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $analysis->analysis_date->format('d.m.Y') }}</div>
                                    <small class="text-muted">{{ $analysis->analysis_period }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-primary">
                                        ₺{{ number_format($analysis->gross_revenue ?? 0, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-danger">
                                        ₺{{ number_format($analysis->total_cost ?? 0, 2) }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $analysis->insurance_company_cost ? 'Sigorta: ' . number_format($analysis->insurance_company_cost, 2) : '' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-success">
                                        ₺{{ number_format($analysis->net_revenue ?? 0, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold {{ ($analysis->profit_margin ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                                        ₺{{ number_format($analysis->profit_margin ?? 0, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold {{ ($analysis->profit_margin_percentage ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                                        %{{ number_format($analysis->profit_margin_percentage ?? 0, 2) }}
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-{{ ($analysis->profit_margin_percentage ?? 0) > 0 ? 'success' : 'danger' }}" 
                                             style="width: {{ min(abs($analysis->profit_margin_percentage ?? 0), 100) }}%"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        @if($analysis->risk_reserve)
                                            <div>Risk: ₺{{ number_format($analysis->risk_reserve, 2) }}</div>
                                        @endif
                                        @if($analysis->commission_reserve)
                                            <div>Komisyon: ₺{{ number_format($analysis->commission_reserve, 2) }}</div>
                                        @endif
                                        @if($analysis->tax_reserve)
                                            <div>Vergi: ₺{{ number_format($analysis->tax_reserve, 2) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="showAnalysisDetails('{{ $analysis->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($analysis->policy)
                                            <a href="{{ route('revenue.show', $analysis->policy) }}" 
                                               class="btn btn-outline-info">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-chart-pie fa-2x mb-2"></i>
                                        <p>Henüz maliyet analizi bulunmuyor</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($costAnalysis->count() > 0)
        <div class="d-flex justify-content-center mt-4">
            <div class="text-muted">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <p>Maliyet analizi verileri gösteriliyor</p>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
function showAnalysisDetails(analysisId) {
    // Analiz detay modal'ı burada açılacak
    alert('Analiz detayları yakında eklenecek - ID: ' + analysisId);
}

function exportToExcel() {
    alert('Excel export özelliği yakında eklenecek');
}

function exportToPdf() {
    alert('PDF export özelliği yakında eklenecek');
}
</script>
@endpush
