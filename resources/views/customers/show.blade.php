@extends('layouts.app')

@section('title', $customer->customer_title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i class="fas fa-user text-muted me-2"></i>
                {{ $customer->customer_title }}
            </h1>
            <p class="text-muted mb-0 mt-1 small">{{ $customer->customer_type }} müşteri</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Geri
            </a>
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Düzenle
            </a>
        </div>
    </div>

    <!-- Müşteri Bilgileri -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Müşteri Bilgileri</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Ünvan:</strong></td>
                            <td>{{ $customer->customer_title }}</td>
                        </tr>
                        <tr>
                            <td><strong>TC/Vergi No:</strong></td>
                            <td><code>{{ $customer->customer_identity_number }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Telefon:</strong></td>
                            <td>
                                @if($customer->phone)
                                    <div class="d-flex gap-2 align-items-center">
                                        <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i>{{ $customer->phone }}
                                        </a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}?text=Merhaba {{ $customer->customer_title }}, size nasıl yardımcı olabilirim?" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>E-posta:</strong></td>
                            <td>
                                @if($customer->email)
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                        {{ $customer->email }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Adres:</strong></td>
                            <td>{{ $customer->address ?? 'Adres girilmemiş' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Müşteri Türü:</strong></td>
                            <td><span class="badge bg-info">{{ ucfirst($customer->customer_type) }}</span></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Finansal Durum</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h4 text-primary mb-1">{{ $stats['total_policies'] }}</div>
                            <small class="text-muted">Toplam Poliçe</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-success mb-1">{{ $stats['active_policies'] }}</div>
                            <small class="text-muted">Aktif Poliçe</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-warning mb-1">{{ $stats['pending_amount'] > 0 ? '₺' . number_format($stats['pending_amount'], 2, ',', '.') : '₺0,00' }}</div>
                            <small class="text-muted">Bekleyen Ödeme</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-danger mb-1">{{ $stats['overdue_amount'] > 0 ? '₺' . number_format($stats['overdue_amount'], 2, ',', '.') : '₺0,00' }}</div>
                            <small class="text-muted">Gecikmiş Ödeme</small>
                        </div>
                    </div>
                    <hr>

                </div>
            </div>
        </div>
    </div>

    <!-- Poliçeler -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Poliçeler ({{ $customer->policies->count() }})</h5>
                <a href="{{ route('policies.create') }}?customer_id={{ $customer->id }}&customer_title={{ urlencode($customer->customer_title) }}&customer_identity_number={{ urlencode($customer->customer_identity_number) }}&customer_phone={{ urlencode($customer->phone ?? '') }}&customer_address={{ urlencode($customer->address ?? '') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Yeni Poliçe
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($customer->policies->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Poliçe No</th>
                                <th>Tür</th>
                                <th>Şirket</th>
                                <th>Başlangıç</th>
                                <th>Bitiş</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->policies as $policy)
                                <tr>
                                    <td><code>{{ $policy->policy_number }}</code></td>
                                    <td>{{ $policy->policy_type }}</td>
                                    <td>{{ $policy->policy_company }}</td>
                                    <td>{{ $policy->start_date->format('d.m.Y') }}</td>
                                    <td>{{ $policy->end_date->format('d.m.Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $policy->status === 'aktif' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($policy->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('policies.show', $policy) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('policies.edit', $policy) }}" class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-file-contract fa-2x mb-2"></i>
                        <p>Henüz poliçe bulunmuyor</p>
                        <a href="{{ route('policies.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>İlk Poliçeyi Ekle
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Ödeme Planları -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ödeme Planları ({{ $customer->paymentSchedules->count() }})</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentScheduleModal">
                    <i class="fas fa-plus me-2"></i>Ödeme Planı Ekle
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($customer->paymentSchedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Açıklama</th>
                                <th>Tutar</th>
                                <th>Vade Tarihi</th>
                                <th>Tür</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->paymentSchedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->description }}</td>
                                    <td>{{ $schedule->formatted_amount }}</td>
                                    <td>
                                        <div>{{ $schedule->due_date->format('d.m.Y') }}</div>
                                        <small class="text-muted">
                                            @if($schedule->is_overdue)
                                                <span class="text-danger">{{ $schedule->days_overdue }} gün gecikmiş</span>
                                            @elseif($schedule->days_until_due > 0)
                                                <span class="text-warning">{{ $schedule->days_until_due }} gün kaldı</span>
                                            @else
                                                <span class="text-success">Bugün vade</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{ ucfirst($schedule->payment_type) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $schedule->status_color }}">
                                            {{ $schedule->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($schedule->status === 'bekliyor')
                                                <button type="button" class="btn btn-outline-success" 
                                                        onclick="markAsPaid('{{ $schedule->id }}')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteSchedule('{{ $schedule->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        <p>Henüz ödeme planı bulunmuyor</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentScheduleModal">
                            <i class="fas fa-plus me-2"></i>İlk Ödeme Planını Ekle
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Ödemeler -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ödeme Geçmişi ({{ $customer->payments->count() }})</h5>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                    <i class="fas fa-plus me-2"></i>Ödeme Ekle
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            @if($customer->payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ödeme No</th>
                                <th>Tutar</th>
                                <th>Yöntem</th>
                                <th>Tarih</th>
                                <th>Durum</th>
                                <th>Notlar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->payments as $payment)
                                <tr>
                                    <td><code>{{ $payment->payment_number }}</code></td>
                                    <td>{{ $payment->formatted_amount }}</td>
                                    <td>
                                        <i class="{{ $payment->method_icon }} me-2"></i>
                                        {{ ucfirst($payment->payment_method) }}
                                    </td>
                                    <td>{{ $payment->payment_date->format('d.m.Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status_color }}">
                                            {{ $payment->status_text }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                        <p>Henüz ödeme bulunmuyor</p>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                            <i class="fas fa-plus me-2"></i>İlk Ödemeyi Ekle
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Ödeme Planı Ekleme Modal -->
    <div class="modal fade" id="addPaymentScheduleModal" tabindex="-1" aria-labelledby="addPaymentScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentScheduleModalLabel">Yeni Ödeme Planı Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customers.payment-schedule', $customer) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="policy_id" class="form-label">Poliçe (Opsiyonel)</label>
                            <select class="form-select" id="policy_id" name="policy_id">
                                <option value="">Poliçe seçiniz</option>
                                @foreach($customer->policies as $policy)
                                    <option value="{{ $policy->id }}">
                                        {{ $policy->policy_number }} - {{ $policy->policy_type }} 
                                        ({{ $policy->start_date->format('d.m.Y') }} - {{ $policy->end_date->format('d.m.Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Tutar (₺)</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Vade Tarihi</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_type" class="form-label">Ödeme Türü</label>
                            <select class="form-select" id="payment_type" name="payment_type" required>
                                <option value="taksit">Taksit</option>
                                <option value="peşin">Peşin</option>
                                <option value="ek ödeme">Ek Ödeme</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ödeme Ekleme Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Yeni Ödeme Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customers.payment', $customer) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="payment_policy_id" class="form-label">Poliçe (Opsiyonel)</label>
                            <select class="form-select" id="payment_policy_id" name="policy_id">
                                <option value="">Poliçe seçiniz</option>
                                @foreach($customer->policies as $policy)
                                    <option value="{{ $policy->id }}">
                                        {{ $policy->policy_number }} - {{ $policy->policy_type }} 
                                        ({{ $policy->start_date->format('d.m.Y') }} - {{ $policy->end_date->format('d.m.Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_schedule_id" class="form-label">Ödeme Planı (Opsiyonel)</label>
                            <select class="form-select" id="payment_schedule_id" name="payment_schedule_id">
                                <option value="">Ödeme planı seçiniz</option>
                                @foreach($customer->paymentSchedules->where('status', 'bekliyor') as $schedule)
                                    <option value="{{ $schedule->id }}" data-amount="{{ $schedule->amount }}">
                                        {{ $schedule->description }} - ₺{{ number_format($schedule->amount, 2) }} 
                                        (Vade: {{ $schedule->due_date->format('d.m.Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_amount" class="form-label">Tutar (₺)</label>
                            <input type="number" class="form-control" id="payment_amount" name="amount" step="0.01" min="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Ödeme Yöntemi</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="nakit">Nakit</option>
                                <option value="kredi_kartı">Kredi Kartı</option>
                                <option value="banka_havalesi">Banka Havalesi</option>
                                <option value="çek">Çek</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Ödeme Tarihi</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_notes" class="form-label">Notlar</label>
                            <textarea class="form-control" id="payment_notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Ödeme planı seçildiğinde tutarı otomatik doldur
document.getElementById('payment_schedule_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const amountInput = document.getElementById('payment_amount');
    
    if (selectedOption.value && selectedOption.dataset.amount) {
        amountInput.value = selectedOption.dataset.amount;
    } else {
        amountInput.value = '';
    }
});

function markAsPaid(scheduleId) {
    if (confirm('Bu ödeme planını ödendi olarak işaretlemek istediğinizden emin misiniz?')) {
        // CSRF token'ı al
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', token);
        console.log('Schedule ID:', scheduleId);
        console.log('Customer ID:', '{{ $customer->id }}');
        
        const url = `/customers/{{ $customer->id }}/payment-schedule/${scheduleId}/mark-paid`;
        console.log('Request URL:', url);
        
        // PATCH request gönder
        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu');
        });
    }
}

function deleteSchedule(scheduleId) {
    if (confirm('Bu ödeme planını silmek istediğinizden emin misiniz?')) {
        // CSRF token'ı al
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', token);
        console.log('Schedule ID:', scheduleId);
        console.log('Customer ID:', '{{ $customer->id }}');
        
        const url = `/customers/{{ $customer->id }}/payment-schedule/${scheduleId}`;
        console.log('Request URL:', url);
        
        // DELETE request gönder
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Hata: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu');
        });
    }
}
</script>
@endpush
