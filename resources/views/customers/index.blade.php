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
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
            <i data-lucide="plus" class="me-2" style="width: 16px; height: 16px;"></i>
            Yeni Müşteri
        </button>
    </div>

    

    

    

    <!-- Yeni Müşteri Modal -->
    <div class="modal fade" id="newCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Müşteri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <form id="newCustomerForm" action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="customerModalStatus" class="mb-2"></div>
                        <div class="mb-3">
                            <label class="form-label">TC/Vergi No <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="modal_identity" name="customer_identity_number" required>
                                <button class="btn btn-outline-secondary" type="button" id="btnCheckIdentity">
                                    Kontrol Et
                                </button>
                            </div>
                            <div class="form-text">Önce TC/Vergi No girip kontrol edin. Diğer alanlar kontrol sonrası açılır.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Müşteri Ünvanı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_title" name="customer_title" required disabled>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="modal_phone" name="phone" placeholder="0___ ___ __ __" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="modal_email" name="email" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adres</label>
                            <textarea class="form-control" id="modal_address" name="address" rows="2" disabled></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Müşteri Türü <span class="text-danger">*</span></label>
                            <select class="form-select" id="modal_type" name="customer_type" required disabled>
                                <option value="">Seçiniz</option>
                                <option value="bireysel" selected>Bireysel</option>
                                <option value="kurumsal">Kurumsal</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Notlar</label>
                            <textarea class="form-control" id="modal_notes" name="notes" rows="2" disabled></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kapat</button>
                        <button type="submit" id="btnSaveCustomer" class="btn btn-primary" disabled>Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Müşteri Listesi -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-normal text-dark">
                    <i data-lucide="users" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                    Müşteri Listesi
                </h5>
                <div class="d-flex align-items-center">
                    <a href="{{ route('customers.export') }}" class="btn btn-outline-success btn-sm me-3" title="Excel İndir">
                        <i data-lucide="download" class="me-1" style="width: 14px; height: 14px;"></i>
                        Excel İndir
                    </a>
                    <label for="liveSearch" class="form-label me-2 mb-0 text-secondary fw-normal">
                        <i data-lucide="search" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                        Arama:
                    </label>
                    <div class="input-group input-group-sm" style="width: 350px;">
                        <input type="text" id="liveSearch" class="form-control" placeholder="Müşteri, TC/Vergi, telefon, e-posta, adres...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch" title="Aramayı Temizle" onclick="clearSearch()">
                            <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </div>
            </div>
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
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'policies_count', 'order' => request('sort') == 'policies_count' && request('order') == 'asc' ? 'desc' : 'asc']) }}"
                               class="text-decoration-none text-dark d-flex align-items-center justify-content-between fw-normal">
                                <span>
                                    <i data-lucide="shield-check" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                                    Poliçe Sayısı
                                </span>
                                <i data-lucide="arrow-up-down" class="text-muted ms-1" style="width: 14px; height: 14px;"></i>
                            </a>
                        </th>
                        <th class="py-3">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_scheduled', 'order' => request('sort') == 'total_scheduled' && request('order') == 'asc' ? 'desc' : 'asc']) }}"
                               class="text-decoration-none text-dark d-flex align-items-center justify-content-between fw-normal">
                                <span>
                                    <i data-lucide="credit-card" class="text-muted me-1" style="width: 14px; height: 14px;"></i>
                                    Bekleyen Ödeme
                                </span>
                                <i data-lucide="arrow-up-down" class="text-muted ms-1" style="width: 14px; height: 14px;"></i>
                            </a>
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

// Kimlik doğrulama yardımcıları
function isValidVKN(vkn){
    if(!/^\d{10}$/.test(vkn)) return false;
    const d=vkn.split('').map(n=>parseInt(n,10));
    const last=d[9];
    let sum=0;
    for(let i=0;i<9;i++){
        const tmp=(d[i]+10-(i+1))%10;
        if(tmp===9){ sum+=tmp; }
        else { const mod=(tmp*Math.pow(2,9-i))%9; sum+=mod; }
    }
    const check=(10-(sum%10))%10;
    return last===check;
}
function isValidTCKN(t){
    if(!/^\d{11}$/.test(t)) return false;
    if(!/^[1-9][0-9]{9}[02468]$/.test(t)) return false;
    const d=t.split('').map(n=>parseInt(n,10));
    const o=d[0]+d[2]+d[4]+d[6]+d[8];
    const e=d[1]+d[3]+d[5]+d[7];
    const c1=(10-((o*3+e)%10))%10;
    const c2=(10-((((e+c1)*3)+o)%10))%10;
    return d[9]===c1 && d[10]===c2;
}


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
    const typeFilterEl = document.getElementById('typeFilter');
    const typeFilter = typeFilterEl ? typeFilterEl.value : '';
    
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
    const typeFilterEl = document.getElementById('typeFilter');
    if (typeFilterEl) typeFilterEl.value = '';
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
    // Yeni Müşteri Modal akışı
    const idEl = document.getElementById('modal_identity');
    const titleEl = document.getElementById('modal_title');
    const phoneEl = document.getElementById('modal_phone');
    const emailEl = document.getElementById('modal_email');
    const addrEl = document.getElementById('modal_address');
    const typeEl = document.getElementById('modal_type');
    const notesEl = document.getElementById('modal_notes');
    const btnSave = document.getElementById('btnSaveCustomer');
    const btnCheck = document.getElementById('btnCheckIdentity');
    const statusDiv = document.getElementById('customerModalStatus');

    function setDisabledAll(disabled) {
        [titleEl, phoneEl, emailEl, addrEl, typeEl, notesEl, btnSave].forEach(function(el){ if (el) el.disabled = disabled; });
    }
    setDisabledAll(true);

    function showStatus(html) { if (statusDiv) statusDiv.innerHTML = html; }
    function clearStatus() { showStatus(''); }

    function checkIdentity() {
        clearStatus();
        // Her yeni kontrol öncesi modal form alanlarını sıfırla
        if (titleEl) titleEl.value = '';
        if (phoneEl) phoneEl.value = '';
        if (emailEl) emailEl.value = '';
        if (addrEl) addrEl.value = '';
        if (typeEl) typeEl.value = 'bireysel';
        setDisabledAll(true);
        if (idEl) idEl.disabled = false;
        const identity = (idEl && idEl.value || '').trim();
        if (!identity) { showStatus('<div class="alert alert-warning py-2">Lütfen TC/Vergi No girin.</div>'); return; }
        // Önce format ve algoritma doğrulaması
        if (identity.length===10){
            if(!/^\d{10}$/.test(identity)) { showStatus('<div class="alert alert-warning py-2">Vergi No formatı geçersiz.</div>'); return; }
            if(!isValidVKN(identity)) { showStatus('<div class="alert alert-danger py-2">Vergi No algoritma doğrulaması başarısız.</div>'); return; }
        } else if (identity.length===11){
            if(!/^[1-9][0-9]{9}[02468]$/.test(identity)) { showStatus('<div class="alert alert-warning py-2">TCKN formatı geçersiz.</div>'); return; }
            if(!isValidTCKN(identity)) { showStatus('<div class="alert alert-danger py-2">TCKN algoritma doğrulaması başarısız.</div>'); return; }
        } else {
            showStatus('<div class="alert alert-warning py-2">TC için 11, VKN için 10 hane giriniz.</div>');
            return;
        }
        fetch('{{ route('customers.check-identity') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
            body: JSON.stringify({ customer_identity_number: identity })
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.success && data.exists) {
                const c = data.customer || {};
                if (titleEl) { titleEl.value = c.customer_title || ''; }
                if (phoneEl) { phoneEl.value = c.phone || ''; }
                if (emailEl) { emailEl.value = c.email || ''; }
                if (addrEl) { addrEl.value = c.address || ''; }
                if (typeEl) { typeEl.value = c.customer_type || 'bireysel'; }
                setDisabledAll(true);
                if (idEl) idEl.disabled = false;
                showStatus('<div class="alert alert-danger py-2">Bu TC/Vergi no ile kayıtlı müşteri var. Yeni kayıt oluşturamazsınız.</div>');
            } else {
                setDisabledAll(false);
                showStatus('<div class="alert alert-info py-2">Bu TC/Vergi no ile müşteri bulunamadı. Yeni müşteri oluşturabilirsiniz.</div>');
            }
        })
        .catch(err => { console.error(err); showStatus('<div class="alert alert-danger py-2">Kimlik kontrolünde hata oluştu.</div>'); });
    }

    if (btnCheck) btnCheck.addEventListener('click', checkIdentity);
    if (idEl) {
        idEl.addEventListener('keypress', function(e){ if (e.key === 'Enter') { e.preventDefault(); checkIdentity(); } });
        // TC/Vergi yazılırken eski bilgiler kalmasın
        idEl.addEventListener('input', function(){
            clearStatus();
            if (titleEl) titleEl.value = '';
            if (phoneEl) phoneEl.value = '';
            if (emailEl) emailEl.value = '';
            if (addrEl) addrEl.value = '';
            if (typeEl) typeEl.value = 'bireysel';
            setDisabledAll(true);
            idEl.disabled = false;
        });
    }

    // Modal telefon otomatik format
    const modalPhone = document.getElementById('modal_phone');
    function formatPhoneTR(v){
        const raw=(v||'').replace(/\D/g,'');
        let d=raw;
        if (d.length===0) return '';
        if (d[0] !== '0') d='0'+d;
        d=d.slice(0,11);
        if (d.length<=1) return d;
        if (d.length<=4) return d.slice(0,1)+d.slice(1);
        if (d.length<=7) return d.slice(0,1)+d.slice(1,4)+' '+d.slice(4);
        if (d.length<=9) return d.slice(0,1)+d.slice(1,4)+' '+d.slice(4,7)+' '+d.slice(7);
        return d.slice(0,1)+d.slice(1,4)+' '+d.slice(4,7)+' '+d.slice(7,9)+' '+d.slice(9,11);
    }
    if (modalPhone){
        modalPhone.addEventListener('input', function(e){ e.target.value = formatPhoneTR(e.target.value); });
        modalPhone.addEventListener('paste', function(e){ e.preventDefault(); const t=(e.clipboardData||window.clipboardData).getData('text'); e.target.value=formatPhoneTR(t); });
    }
});
</script>
@endpush
