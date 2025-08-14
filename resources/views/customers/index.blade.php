@extends('layouts.app')

@section('title', 'Müşteriler')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i data-lucide="users" class="text-muted me-2" style="width: 24px; height: 24px;"></i>
                Müşteriler
            </h1>
            <p class="text-muted mb-0 mt-1 small">Müşteri bilgilerini yönetin ve takip edin</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn btn-outline-primary">
            <i data-lucide="plus" class="me-2" style="width: 16px; height: 16px;"></i>
            Yeni Müşteri
        </a>
    </div>

    

    

    <!-- Modern Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex align-items-center">
                <i data-lucide="search" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                <h5 class="mb-0 fw-normal text-dark">Global Müşteri Arama</h5>
                <button class="btn btn-link text-decoration-none ms-auto text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false">
                    <i data-lucide="chevron-down" style="width: 16px; height: 16px;"></i>
                </button>
            </div>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body pt-0">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <label for="liveSearch" class="form-label fw-normal text-secondary">
                            <i data-lucide="search" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Arama
                        </label>
                        <input type="text" id="liveSearch" class="form-control" placeholder="Müşteri adı, TC/Vergi no, telefon, e-posta veya adres ile arama yapın...">
                    </div>
                    <div class="col-lg-2">
                        <label for="typeFilter" class="form-label fw-normal text-secondary">
                            <i data-lucide="tag" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Müşteri Türü
                        </label>
                        <select id="typeFilter" class="form-select" onchange="filterCustomers()">
                            <option value="">Tüm Türler</option>
                            <option value="bireysel">👤 Bireysel</option>
                            <option value="kurumsal">🏢 Kurumsal</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fw-normal text-secondary">&nbsp;</label>
                        <button type="button" id="clearSearch" class="btn btn-outline-secondary w-100" onclick="clearSearch()">
                            <i data-lucide="x" class="me-2" style="width: 14px; height: 14px;"></i>Temizle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Müşteri Listesi -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-normal text-dark">
                <i data-lucide="users" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                Müşteri Listesi
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'customer_title', 'order' => request('sort') == 'customer_title' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="text-decoration-none text-dark d-flex align-items-center justify-content-between fw-normal">
                                <i data-lucide="user" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                                Müşteri
                                <i data-lucide="arrow-up-down" class="text-muted ms-1" style="width: 14px; height: 14px;"></i>
                            </a>
                        </th>
                        <th class="py-3">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'customer_identity_number', 'order' => request('sort') == 'customer_identity_number' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="text-decoration-none text-dark d-flex align-items-center justify-content-between fw-normal">
                                <i data-lucide="id-card" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                                TC/Vergi No
                                <i data-lucide="arrow-up-down" class="text-muted ms-1" style="width: 14px; height: 14px;"></i>
                            </a>
                        </th>
                        <th class="py-3">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'phone', 'order' => request('sort') == 'phone' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                               class="text-decoration-none text-dark d-flex align-items-center justify-content-between fw-normal">
                                <i data-lucide="phone" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                                İletişim
                                <i data-lucide="arrow-up-down" class="text-muted ms-1" style="width: 14px; height: 14px;"></i>
                            </a>
                        </th>
                        <th class="py-3">
                            <i data-lucide="shield-check" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Poliçe Sayısı
                        </th>
                        <th class="py-3">
                            <i data-lucide="credit-card" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            Bekleyen Ödeme
                        </th>
                        <th class="py-3">
                            <i data-lucide="settings" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody id="customersTableBody">
                    @forelse($customers as $customer)
                        <tr class="customer-row" 
                            data-title="{{ strtolower($customer->customer_title) }}"
                            data-identity="{{ strtolower($customer->customer_identity_number) }}"
                            data-phone="{{ strtolower($customer->phone ?? '') }}"
                            data-email="{{ strtolower($customer->email ?? '') }}"
                            data-address="{{ strtolower($customer->address ?? '') }}"
                            data-type="{{ $customer->customer_type }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fa-solid fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $customer->customer_title }}</h6>
                                        <small class="text-muted">{{ $customer->customer_type }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $customer->customer_identity_number }}</span>
                            </td>
                            <td>
                                <div>
                                    @if($customer->phone)
                                        <div><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $customer->phone }}</div>
                                    @endif
                                    @if($customer->email)
                                        <div><i class="fa-solid fa-envelope me-1 text-muted"></i>{{ $customer->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $customer->policies_count }}</span>
                            </td>
                            <td>
                                @if($customer->pending_payments > 0)
                                    <span class="badge bg-warning">{{ $customer->pending_payments }}</span>
                                    <br><small class="text-muted">₺{{ number_format($customer->total_scheduled, 2) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('customers.show', $customer) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" 
                                       class="btn btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteCustomer('{{ $customer->id }}', '{{ $customer->customer_title }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <p>Henüz müşteri bulunmuyor</p>
                                    </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i data-lucide="info" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                        Toplam {{ $customers->total() }} müşteri, {{ $customers->currentPage() }}. sayfa
                        (Sayfa başına {{ $customers->perPage() }} kayıt)
                    </div>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
.sortable-header {
    cursor: pointer;
    transition: all 0.2s ease;
}

.sortable-header:hover {
    background-color: #f8f9fa;
    color: #0d6efd !important;
}

.sortable-header:hover i {
    color: #0d6efd !important;
}

.sort-icon {
    font-size: 0.75rem;
    opacity: 0.6;
}

.sort-icon.active {
    opacity: 1;
    color: #0d6efd !important;
}
</style>
@endpush

@push('scripts')
<script>


function deleteCustomer(customerId, customerName) {
    if (confirm(`"${customerName}" müşterisini silmek istediğinizden emin misiniz?`)) {
        // CSRF token'ı al
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // DELETE request gönder
        fetch(`/customers/${customerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Müşteri silinirken bir hata oluştu: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Bir hata oluştu');
        });
    }
}

function exportToExcel() {
    alert('Excel export özelliği yakında eklenecek');
}

function exportToPdf() {
    alert('PDF export özelliği yakında eklenecek');
}

// Live Arama ve Filtreleme Fonksiyonları
function filterCustomers() {
    const searchTerm = document.getElementById('liveSearch').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    
    const rows = document.querySelectorAll('.customer-row');
    
    rows.forEach(row => {
        const title = row.getAttribute('data-title');
        const identity = row.getAttribute('data-identity');
        const phone = row.getAttribute('data-phone');
        const email = row.getAttribute('data-email');
        const address = row.getAttribute('data-address');
        const type = row.getAttribute('data-type');
        
        // Arama filtresi
        const matchesSearch = !searchTerm || 
            title.includes(searchTerm) || 
            identity.includes(searchTerm) || 
            phone.includes(searchTerm) || 
            email.includes(searchTerm) || 
            address.includes(searchTerm);
        
        // Tür filtresi
        const matchesType = !typeFilter || type === typeFilter;
        
        // Tüm filtreleri geçiyorsa göster
        if (matchesSearch && matchesType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateResultsCount();
}

function clearSearch() {
    document.getElementById('liveSearch').value = '';
    document.getElementById('typeFilter').value = '';
    filterCustomers();
}

function updateResultsCount() {
    const visibleRows = document.querySelectorAll('.customer-row:not([style*="display: none"])');
    const totalRows = document.querySelectorAll('.customer-row').length;
    
    // Sonuç sayısını güncelle (opsiyonel)
    console.log(`Gösterilen: ${visibleRows.length} / Toplam: ${totalRows}`);
}

// Live arama için event listener
document.addEventListener('DOMContentLoaded', function() {
    const liveSearchInput = document.getElementById('liveSearch');
    if (liveSearchInput) {
        liveSearchInput.addEventListener('input', filterCustomers);
        liveSearchInput.addEventListener('keyup', filterCustomers);
    }
});
</script>
@endpush
