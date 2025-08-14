@extends('layouts.app')

@section('title', 'Ödeme İşlemleri')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i class="fas fa-credit-card text-muted me-2"></i>
                Ödeme İşlemleri
            </h1>
            <p class="text-muted mb-0 mt-1 small">Tüm ödeme işlemlerinin detaylı takibi ve raporlanması</p>
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
                    <label for="transaction_type" class="form-label">İşlem Türü</label>
                    <select class="form-select" id="transaction_type" name="transaction_type">
                        <option value="">Tümü</option>
                        <option value="ödeme" {{ request('transaction_type') == 'ödeme' ? 'selected' : '' }}>Ödeme</option>
                        <option value="iade" {{ request('transaction_type') == 'iade' ? 'selected' : '' }}>İade</option>
                        <option value="düzeltme" {{ request('transaction_type') == 'düzeltme' ? 'selected' : '' }}>Düzeltme</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="payment_status" class="form-label">Durum</label>
                    <select class="form-select" id="payment_status" name="payment_status">
                        <option value="">Tümü</option>
                        <option value="başarılı" {{ request('payment_status') == 'başarılı' ? 'selected' : '' }}>Başarılı</option>
                        <option value="bekliyor" {{ request('payment_status') == 'bekliyor' ? 'selected' : '' }}>Bekliyor</option>
                        <option value="başarısız" {{ request('payment_status') == 'başarısız' ? 'selected' : '' }}>Başarısız</option>
                        <option value="iptal" {{ request('payment_status') == 'iptal' ? 'selected' : '' }}>İptal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="payment_method" class="form-label">Ödeme Yöntemi</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                        <option value="">Tümü</option>
                        <option value="nakit" {{ request('payment_method') == 'nakit' ? 'selected' : '' }}>Nakit</option>
                        <option value="kredi kartı" {{ request('payment_method') == 'kredi kartı' ? 'selected' : '' }}>Kredi Kartı</option>
                        <option value="banka havalesi" {{ request('payment_method') == 'banka havalesi' ? 'selected' : '' }}>Banka Havalesi</option>
                        <option value="çek" {{ request('payment_method') == 'çek' ? 'selected' : '' }}>Çek</option>
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

    <!-- Ödeme İşlemleri Listesi -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ödeme İşlemleri ({{ $transactions->total() }})</h5>
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
                            <th>Tarih</th>
                            <th>İşlem No</th>
                            <th>Müşteri</th>
                            <th>Poliçe No</th>
                            <th>Tür</th>
                            <th>Tutar</th>
                            <th>Yöntem</th>
                            <th>Durum</th>
                            <th>Referans</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    @if($transaction->transaction_date)
                                        <div>{{ $transaction->transaction_date->format('d.m.Y') }}</div>
                                        <small class="text-muted">{{ $transaction->transaction_date->format('H:i') }}</small>
                                    @else
                                        <div>{{ $transaction->created_at->format('d.m.Y') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <code>#{{ $transaction->id }}</code>
                                </td>
                                <td>
                                    @if($transaction->policy)
                                        <div class="fw-semibold">{{ $transaction->policy->customer_title }}</div>
                                        <small class="text-muted">{{ $transaction->policy->customer_identity_number }}</small>
                                    @else
                                        <span class="text-muted">Poliçe bulunamadı</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->policy)
                                        <a href="{{ route('revenue.show', $transaction->policy) }}" class="text-decoration-none">
                                            {{ $transaction->policy->policy_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->transaction_type == 'ödeme' ? 'success' : ($transaction->transaction_type == 'iade' ? 'warning' : 'info') }}">
                                        {{ ucfirst($transaction->transaction_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold {{ $transaction->transaction_type == 'iade' ? 'text-success' : 'text-primary' }}">
                                        {{ $transaction->formatted_amount }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $transaction->payment_method }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status_color }}">
                                        {{ $transaction->status_text }}
                                    </span>
                                </td>
                                <td>
                                    @if($transaction->reference_number)
                                        <code>{{ $transaction->reference_number }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="showTransactionDetails('{{ $transaction->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($transaction->payment_status === 'bekliyor')
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="approveTransaction('{{ $transaction->id }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="rejectTransaction('{{ $transaction->id }}')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-credit-card fa-2x mb-2"></i>
                                        <p>Henüz ödeme işlemi bulunmuyor</p>
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
    @if($transactions->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection

@push('scripts')
<script>
function showTransactionDetails(transactionId) {
    // İşlem detay modal'ı burada açılacak
    alert('İşlem detayları yakında eklenecek - ID: ' + transactionId);
}

function approveTransaction(transactionId) {
    if (confirm('Bu işlemi onaylamak istediğinizden emin misiniz?')) {
        // Onay işlemi burada yapılacak
        alert('İşlem onaylandı - ID: ' + transactionId);
    }
}

function rejectTransaction(transactionId) {
    if (confirm('Bu işlemi reddetmek istediğinizden emin misiniz?')) {
        // Red işlemi burada yapılacak
        alert('İşlem reddedildi - ID: ' + transactionId);
    }
}

function exportToExcel() {
    alert('Excel export özelliği yakında eklenecek');
}

function exportToPdf() {
    alert('PDF export özelliği yakında eklenecek');
}
</script>
@endpush
