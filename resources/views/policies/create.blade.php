@extends('layouts.app')

@section('title', 'Yeni Poliçe Oluştur')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="h4 font-weight-bold">
            Yeni Poliçe Oluştur
        </h2>
    </div>

    <form action="{{ route('policies.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Müşteri Bilgileri</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_title" class="form-label">Müşteri Ünvan</label>
                            <input type="text" class="form-control" id="customer_title" name="customer_title" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_identity_number" class="form-label">TC/Vergi No</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="customer_identity_number" name="customer_identity_number" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="checkCustomerIdentity()">
                                            <i class="fa-solid fa-search"></i>
                                        </button>
                                    </div>
                                    <div id="identityCheckResult" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Müşteri Telefon</label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_birth_date" class="form-label">Doğum Tarihi</label>
                                    <input type="date" class="form-control" id="customer_birth_date" name="customer_birth_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Adres</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Sigorta Ettiren Bilgileri <small class="text-muted">(boş bırakılırsa Müşteri bilgileri kullanılır)</small></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="insured_name" class="form-label">Sigorta Ettiren Ünvan</label>
                            <input type="text" class="form-control" id="insured_name" name="insured_name">
                        </div>
                        <div class="mb-3">
                            <label for="insured_phone" class="form-label">Sigorta Ettiren Telefon</label>
                            <input type="text" class="form-control" id="insured_phone" name="insured_phone">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Poliçe Detayları</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_type" class="form-label">Poliçe Türü</label>
                                    <select class="form-select" id="policy_type" name="policy_type" required>
                                        <option value="Zorunlu Trafik Sigortası">Zorunlu Trafik Sigortası</option>
                                        <option value="Kasko">Kasko</option>
                                        <option value="DASK">DASK</option>
                                        <option value="Konut Sigortası">Konut Sigortası</option>
                                        <option value="Sağlık Sigortası">Sağlık Sigortası</option>
                                        <option value="TARSİM">TARSİM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_number" class="form-label">Poliçe No</label>
                                    <input type="text" class="form-control" id="policy_number" name="policy_number" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="policy_company" class="form-label">Poliçe Şirketi</label>
                            <input type="text" class="form-control" id="policy_company" name="policy_company" 
                                   placeholder="Sigorta şirketi adı...">
                        </div>
                        <div class="mb-3">
                            <label for="plate_or_other" class="form-label">
                                <i data-lucide="car" class="text-muted me-1" style="width: 16px; height: 16px;"></i>
                                Plaka/Diğer
                            </label>
                            <input type="text" class="form-control" id="plate_or_other" name="plate_or_other" 
                                   placeholder="Araç plakası veya diğer tanımlayıcı bilgi...">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label">Tanzim Tarihi</label>
                                    <input type="date" class="form-control" id="issue_date" name="issue_date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Bitiş Tarihi</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="document_info" class="form-label">Belge Seri/Diğer/UAVT</label>
                            <input type="text" class="form-control" id="document_info" name="document_info">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">TARSİM Bilgileri <small class="text-muted">(Poliçe Türü TARSİM ise doldurun)</small></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tarsim_business_number" class="form-label">TARSİM İşletme No</label>
                                    <input type="text" class="form-control" id="tarsim_business_number" name="tarsim_business_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tarsim_animal_number" class="form-label">TARSİM Hayvan No</label>
                                    <input type="text" class="form-control" id="tarsim_animal_number" name="tarsim_animal_number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gelir Yönetimi Alanları -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-money-bill-wave me-2"></i>Gelir Yönetimi
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="policy_premium" class="form-label">Poliçe Primi (₺)</label>
                                    <input type="number" class="form-control" id="policy_premium" name="policy_premium" 
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="commission_rate" class="form-label">Komisyon Oranı (%)</label>
                                    <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                           step="0.01" min="0" max="100" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="payment_due_date" class="form-label">Ödeme Vade Tarihi</label>
                                    <input type="date" class="form-control" id="payment_due_date" name="payment_due_date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="tax_rate" class="form-label">Vergi Oranı (%)</label>
                                    <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                           step="0.01" min="0" max="100" value="18.00">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_notes" class="form-label">Ödeme Notları</label>
                                    <textarea class="form-control" id="payment_notes" name="payment_notes" 
                                              rows="2" placeholder="Ödeme ile ilgili notlar..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_number" class="form-label">Fatura Numarası</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" 
                                           placeholder="Fatura numarası...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('policies.index') }}" class="btn btn-secondary me-2">İptal</a>
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
function checkCustomerIdentity() {
    const identityNumber = document.getElementById('customer_identity_number').value.trim();
    if (!identityNumber) {
        alert('Lütfen TC/Vergi no girin');
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const resultDiv = document.getElementById('identityCheckResult');

    fetch('/policies/check-customer-identity', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ customer_identity_number: identityNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.exists) {
                resultDiv.innerHTML = `
                    <div class="alert alert-warning alert-sm">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        <strong>Dikkat!</strong> Bu TC/Vergi no ile kayıtlı müşteri bulundu: <strong>${data.customer.customer_title}</strong>
                        <br><small class="text-muted">Müşteri bilgileri otomatik olarak güncellenecek.</small>
                    </div>
                `;
                
                // Müşteri bilgilerini otomatik doldur
                document.getElementById('customer_title').value = data.customer.customer_title;
                if (data.customer.phone) {
                    document.getElementById('customer_phone').value = data.customer.phone;
                }
                if (data.customer.address) {
                    document.getElementById('customer_address').value = data.customer.address;
                }
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-info alert-sm">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        <strong>Bilgi:</strong> Bu TC/Vergi no ile kayıtlı müşteri bulunamadı. Yeni müşteri oluşturulacak.
                    </div>
                `;
            }
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger alert-sm">
                    <i class="fa-solid fa-times-circle me-2"></i>
                    <strong>Hata:</strong> ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = `
            <div class="alert alert-danger alert-sm">
                <i class="fa-solid fa-times-circle me-2"></i>
                <strong>Hata:</strong> Bir hata oluştu
            </div>
        `;
    });
}

// TC/Vergi no alanında Enter tuşu ile kontrol
document.addEventListener('DOMContentLoaded', function() {
    const identityInput = document.getElementById('customer_identity_number');
    if (identityInput) {
        identityInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                checkCustomerIdentity();
            }
        });
    }
    
    // URL parametrelerinden müşteri bilgilerini doldur
    fillCustomerInfoFromURL();
});

// URL parametrelerinden müşteri bilgilerini doldur
function fillCustomerInfoFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Müşteri bilgilerini doldur
    if (urlParams.has('customer_title')) {
        document.getElementById('customer_title').value = urlParams.get('customer_title');
    }
    
    if (urlParams.has('customer_identity_number')) {
        document.getElementById('customer_identity_number').value = urlParams.get('customer_identity_number');
    }
    
    if (urlParams.has('customer_phone')) {
        document.getElementById('customer_phone').value = urlParams.get('customer_phone');
    }
    
    if (urlParams.has('customer_address')) {
        document.getElementById('customer_address').value = urlParams.get('customer_address');
    }
    
    // Sigorta ettiren bilgilerini de doldur (eğer boşsa)
    if (urlParams.has('customer_title') && !document.getElementById('insured_name').value) {
        document.getElementById('insured_name').value = urlParams.get('customer_title');
    }
    
    if (urlParams.has('customer_phone') && !document.getElementById('insured_phone').value) {
        document.getElementById('insured_phone').value = urlParams.get('customer_phone');
    }
    
    // Eğer müşteri bilgileri doldurulduysa, TC/Vergi no kontrolü yap
    if (urlParams.has('customer_identity_number')) {
        setTimeout(() => {
            checkCustomerIdentity();
        }, 500);
    }
}
</script>
@endpush
