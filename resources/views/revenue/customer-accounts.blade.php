@extends('layouts.app')

@section('title', 'Müşteri Cari Hesapları')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i class="fas fa-users text-muted me-2"></i>
                Müşteri Cari Hesapları
            </h1>
            <p class="text-muted mb-0 mt-1 small">Müşteri bakiyeleri, risk seviyeleri ve cari hesap takibi</p>
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
                <div class="col-md-4">
                    <label for="balance_min" class="form-label">Min. Bakiye</label>
                    <input type="number" class="form-control" id="balance_min" name="balance_min" 
                           value="{{ request('balance_min') }}" step="0.01" min="0">
                </div>
                <div class="col-md-4">
                    <label for="balance_max" class="form-label">Max. Bakiye</label>
                    <input type="number" class="form-control" id="balance_max" name="balance_max" 
                           value="{{ request('balance_max') }}" step="0.01" min="0">
                </div>
                <div class="col-md-4">
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

    <!-- Müşteri Listesi -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Müşteri Cari Hesapları ({{ $customers->total() }})</h5>
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
                            <th>Müşteri</th>
                            <th>TC/Vergi No</th>
                            <th>Telefon</th>
                            <th>Bakiye</th>
                            <th>Gecikme</th>
                            <th>Son Aktivite</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $customer->customer_title }}</div>
                                        <small class="text-muted">{{ $customer->email ?? 'E-posta yok' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $customer->customer_identity_number }}</code>
                                </td>
                                <td>
                                    @if($customer->phone)
                                        <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                            {{ $customer->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold {{ $customer->current_balance > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $customer->formatted_balance }}
                                    </div>
                                    @if($customer->current_balance > 0)
                                        <small class="text-muted">{{ $customer->payment_terms_days }} gün vade</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $customer->formatted_credit_limit }}</div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-{{ $customer->risk_color }}" 
                                             style="width: {{ $customer->credit_utilization }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $customer->credit_utilization }}% kullanım</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $customer->risk_color }}">
                                        {{ $customer->risk_text }}
                                    </span>
                                </td>
                                <td>
                                    @if($customer->days_overdue > 0)
                                        <span class="badge bg-danger">
                                            {{ $customer->days_overdue }} gün
                                        </span>
                                    @else
                                        <span class="badge bg-success">Güncel</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->last_activity_date)
                                        <div>{{ $customer->last_activity_date->format('d.m.Y') }}</div>
                                        <small class="text-muted">{{ $customer->last_activity_date->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="showCustomerDetails('{{ $customer->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-success" 
                                                onclick="showPaymentForm('{{ $customer->id }}')">
                                            <i class="fas fa-credit-card"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" 
                                                onclick="showReminderForm('{{ $customer->id }}')">
                                            <i class="fas fa-bell"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <p>Henüz müşteri cari hesabı bulunmuyor</p>
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
    @if($customers->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $customers->links() }}
        </div>
    @endif

    <!-- Müşteri Detay Modal -->
    <div class="modal fade" id="customerDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Müşteri Detayları</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Müşteri Bilgileri</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Ünvan:</strong></td><td id="modal-customer-title"></td></tr>
                                <tr><td><strong>TC/Vergi No:</strong></td><td id="modal-customer-identity"></td></tr>
                                <tr><td><strong>Telefon:</strong></td><td id="modal-customer-phone"></td></tr>
                                <tr><td><strong>E-posta:</strong></td><td id="modal-customer-email"></td></tr>
                                <tr><td><strong>Adres:</strong></td><td id="modal-customer-address"></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Finansal Durum</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Mevcut Bakiye:</strong></td><td id="modal-customer-balance"></td></tr>
                                <tr><td><strong>Gecikme Günü:</strong></td><td id="modal-customer-overdue"></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ödeme Formu Modal -->
    <div class="modal fade" id="paymentFormModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ödeme Formu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">Müşteri</label>
                            <input type="text" class="form-control" id="payment-customer-name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mevcut Bakiye</label>
                            <input type="text" class="form-control" id="payment-current-balance" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="payment-amount" class="form-label">Ödeme Tutarı (₺)</label>
                            <input type="number" class="form-control" id="payment-amount" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment-method" class="form-label">Ödeme Yöntemi</label>
                            <select class="form-select" id="payment-method" required>
                                <option value="">Seçiniz</option>
                                <option value="nakit">Nakit</option>
                                <option value="kredi kartı">Kredi Kartı</option>
                                <option value="banka havalesi">Banka Havalesi</option>
                                <option value="çek">Çek</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment-notes" class="form-label">Notlar</label>
                            <textarea class="form-control" id="payment-notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" onclick="submitPayment()">Ödemeyi Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hatırlatma Formu Modal -->
    <div class="modal fade" id="reminderFormModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hatırlatma Gönder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reminderForm">
                        <div class="mb-3">
                            <label class="form-label">Müşteri</label>
                            <input type="text" class="form-control" id="reminder-customer-name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mevcut Bakiye</label>
                            <input type="text" class="form-control" id="reminder-current-balance" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gecikme Durumu</label>
                            <input type="text" class="form-control" id="reminder-overdue-days" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="reminder-method" class="form-label">Hatırlatma Yöntemi</label>
                            <select class="form-select" id="reminder-method" required>
                                <option value="">Seçiniz</option>
                                <option value="sms">SMS</option>
                                <option value="email">E-posta</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="telefon">Telefon</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reminder-message" class="form-label">Mesaj</label>
                            <textarea class="form-control" id="reminder-message" rows="4" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-warning" onclick="sendReminder()">Hatırlatma Gönder</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function showCustomerDetails(customerId) {
    // Modal verilerini doldur
    document.getElementById('modal-customer-title').textContent = 'Müşteri ID: ' + customerId;
    document.getElementById('modal-customer-identity').textContent = 'ID: ' + customerId;
    document.getElementById('modal-customer-phone').textContent = '-';
    document.getElementById('modal-customer-email').textContent = '-';
    document.getElementById('modal-customer-address').textContent = '-';
    document.getElementById('modal-customer-balance').textContent = '₺0,00';
    document.getElementById('modal-customer-overdue').textContent = 'Gecikme yok';
    
    // Modal'ı aç
    new bootstrap.Modal(document.getElementById('customerDetailsModal')).show();
}

function showPaymentForm(customerId) {
    // Modal verilerini doldur
    document.getElementById('payment-customer-name').value = 'Müşteri ID: ' + customerId;
    document.getElementById('payment-current-balance').value = '₺0,00';
    document.getElementById('payment-amount').value = '';
    document.getElementById('payment-method').value = '';
    document.getElementById('payment-notes').value = '';
    
    // Modal'ı aç
    new bootstrap.Modal(document.getElementById('paymentFormModal')).show();
}

function showReminderForm(customerId) {
    // Modal verilerini doldur
    document.getElementById('reminder-customer-name').value = 'Müşteri ID: ' + customerId;
    document.getElementById('reminder-current-balance').value = '₺0,00';
    document.getElementById('reminder-overdue-days').value = 'Gecikme yok';
    document.getElementById('reminder-method').value = '';
    document.getElementById('reminder-message').value = 'Sayın müşteri, ödemenizin vadesi yaklaşmaktadır.';
    
    // Modal'ı aç
    new bootstrap.Modal(document.getElementById('reminderFormModal')).show();
}



function submitPayment() {
    const amount = document.getElementById('payment-amount').value;
    const method = document.getElementById('payment-method').value;
    const notes = document.getElementById('payment-notes').value;
    
    if (!amount || !method) {
        alert('Lütfen tüm gerekli alanları doldurunuz.');
        return;
    }
    
    // Burada gerçek ödeme işlemi yapılacak
    alert(`Ödeme kaydedildi:\nTutar: ₺${amount}\nYöntem: ${method}\nNotlar: ${notes}`);
    
    // Modal'ı kapat
    bootstrap.Modal.getInstance(document.getElementById('paymentFormModal')).hide();
}

function sendReminder() {
    const method = document.getElementById('reminder-method').value;
    const message = document.getElementById('reminder-message').value;
    
    if (!method || !message) {
        alert('Lütfen tüm gerekli alanları doldurunuz.');
        return;
    }
    
    // Burada gerçek hatırlatma işlemi yapılacak
    alert(`Hatırlatma gönderildi:\nYöntem: ${method}\nMesaj: ${message}`);
    
    // Modal'ı kapat
    bootstrap.Modal.getInstance(document.getElementById('reminderFormModal')).hide();
}

function exportToExcel() {
    alert('Excel export özelliği yakında eklenecek');
}

function exportToPdf() {
    alert('PDF export özelliği yakında eklenecek');
}
</script>
@endpush
