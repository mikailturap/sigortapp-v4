@extends('layouts.app')

@section('title', 'Poliçe Düzenle')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-dark fw-normal">
                <i data-lucide="edit-3" class="text-muted me-2" style="width: 24px; height: 24px;"></i>
                Poliçeyi Düzenle
            </h1>
            <p class="text-muted mb-0 mt-1 small">Poliçe bilgilerini güncelleyin</p>
        </div>
        <a href="{{ route('policies.index') }}" class="btn btn-outline-secondary">
            <i data-lucide="arrow-left" class="me-2" style="width: 16px; height: 16px;"></i>
            Tüm Poliçeler
        </a>
    </div>

    <form action="{{ route('policies.update', $policy) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="user" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Müşteri Bilgileri</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_title" class="form-label fw-normal text-secondary">Müşteri Ünvan</label>
                            <input type="text" class="form-control" id="customer_title" value="{{ $policy->customer_title }}" readonly disabled>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_identity_number" class="form-label fw-normal text-secondary">TC/Vergi No</label>
                                    <input type="text" class="form-control" id="customer_identity_number" value="{{ $policy->customer_identity_number }}" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label fw-normal text-secondary">Müşteri Telefon</label>
                                    <input type="text" class="form-control" id="customer_phone" value="{{ $policy->customer_phone }}" readonly disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_birth_date" class="form-label fw-normal text-secondary">Doğum Tarihi</label>
                                    <input type="date" class="form-control" id="customer_birth_date" value="{{ $policy->customer_birth_date ? $policy->customer_birth_date->format('Y-m-d') : '' }}" readonly disabled>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label fw-normal text-secondary">Adres</label>
                            <textarea class="form-control" id="customer_address" rows="3" readonly disabled>{{ $policy->customer_address }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="shield" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Sigorta Ettiren Bilgileri</h5>
                            <small class="text-muted ms-2">(boş bırakılırsa Müşteri bilgileri kullanılır)</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="insured_name" class="form-label fw-normal text-secondary">Sigorta Ettiren Ünvan</label>
                            <input type="text" class="form-control" id="insured_name" name="insured_name" value="{{ old('insured_name', $policy->insured_name) }}">
                        </div>
                        <div class="mb-3">
                            <label for="insured_phone" class="form-label fw-normal text-secondary">Sigorta Ettiren Telefon</label>
                            <input type="text" class="form-control" id="insured_phone" name="insured_phone" placeholder="0XXX XXX XX XX" value="{{ old('insured_phone', $policy->insured_phone) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="file-text" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Poliçe Detayları</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_type" class="form-label fw-normal text-secondary">Poliçe Türü</label>
                                    <select class="form-select" id="policy_type" name="policy_type" required>
                                        <option value="">Poliçe türü seçin...</option>
                                        @foreach(\App\Models\PolicyType::active()->ordered()->get() as $policyType)
                                            <option value="{{ $policyType->name }}" {{ $policy->policy_type == $policyType->name ? 'selected' : '' }}>
                                                {{ $policyType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="policy_number" class="form-label fw-normal text-secondary">Poliçe No</label>
                                    <input type="text" class="form-control" id="policy_number" name="policy_number" value="{{ old('policy_number', $policy->policy_number) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="policy_company" class="form-label fw-normal text-secondary">Poliçe Şirketi</label>
                            <select class="form-select" id="policy_company" name="policy_company">
                                <option value="">Sigorta şirketi seçin...</option>
                                @foreach(\App\Models\InsuranceCompany::active()->ordered()->get() as $company)
                                    <option value="{{ $company->name }}" {{ $policy->policy_company == $company->name ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="plate_or_other" class="form-label">
                                <i data-lucide="car" class="text-muted me-1" style="width: 16px; height: 16px;"></i>
                                Plaka/Diğer
                            </label>
                            <input type="text" class="form-control" id="plate_or_other" name="plate_or_other" 
                                   value="{{ old('plate_or_other', $policy->plate_or_other) }}" 
                                   placeholder="Araç plakası veya diğer tanımlayıcı bilgi...">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="issue_date" class="form-label fw-normal text-secondary">Tanzim Tarihi</label>
                                    <input type="date" class="form-control" id="issue_date" name="issue_date" value="{{ old('issue_date', $policy->issue_date ? $policy->issue_date->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label fw-normal text-secondary">Başlangıç Tarihi</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $policy->start_date ? $policy->start_date->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label fw-normal text-secondary">Bitiş Tarihi</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $policy->end_date ? $policy->end_date->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="document_info" class="form-label fw-normal text-secondary">Belge Seri/Diğer/UAVT</label>
                            <input type="text" class="form-control" id="document_info" name="document_info" value="{{ old('document_info', $policy->document_info) }}">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="leaf" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">TARSİM Bilgileri</h5>
                            <small class="text-muted ms-2">(Poliçe Türü TARSİM ise doldurun)</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tarsim_business_number" class="form-label fw-normal text-secondary">TARSİM İşletme No</label>
                                    <input type="text" class="form-control" id="tarsim_business_number" name="tarsim_business_number" value="{{ old('tarsim_business_number', $policy->tarsim_business_number) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tarsim_animal_number" class="form-label fw-normal text-secondary">TARSİM Hayvan No</label>
                                    <input type="text" class="form-control" id="tarsim_animal_number" name="tarsim_animal_number" value="{{ old('tarsim_animal_number', $policy->tarsim_animal_number) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="paperclip" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
                            <h5 class="mb-0 fw-normal text-dark">Dosyalar</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 text-muted small">Yeni dosya ekleyebilirsiniz (toplamda en fazla 4 dosya, her biri 5 MB). Mevcut dosyalar aşağıda listelenir.</div>
                        <input type="file" name="files[]" id="files_input" class="form-control mb-3" multiple accept=".pdf,.csv,.xlsx,.xls,.doc,.docx,.jpg,.jpeg,.png,.rar">
                        <ul id="selected_files_list" class="list-group"></ul>

                        @if($policy->files && $policy->files->count())
                            <ul class="list-group existing-files-list">
                                @foreach($policy->files as $file)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <i data-lucide="file" class="text-muted" style="width: 16px; height: 16px;"></i>
                                            <span>{{ $file->original_name }}</span>
                                            <small class="text-muted">({{ number_format(($file->size ?? 0) / 1024, 1) }} KB)</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ route('policies.files.preview', [$policy, $file]) }}">Önizle</a>
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('policies.files.download', [$policy, $file]) }}">İndir</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-file-btn" 
                                                data-policy-id="{{ $policy->id }}" 
                                                data-file-id="{{ $file->id }}"
                                                data-csrf-token="{{ csrf_token() }}">Sil</button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">Bu poliçe için dosya yok.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="{{ route('policies.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="x" class="me-1" style="width: 16px; height: 16px;"></i>
                İptal
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>
                Güncelle
            </button>
        </div>
    </form>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sigorta ettiren telefon formatı 0XXX XXX XX XX
    (function(){
        const el = document.getElementById('insured_phone');
        if (!el) return;
        function fmt(v){
            let s = (v||'').replace(/\D/g,'').slice(0,11);
            if (!s) return '';
            if (s[0] !== '0') s = '0' + s.slice(0,10);
            const first = s.slice(0,4);
            const mid = s.slice(4,7);
            const part3 = s.slice(7,9);
            const part4 = s.slice(9,11);
            let out = first;
            if (mid) out += ' ' + mid;
            if (part3) out += ' ' + part3;
            if (part4) out += ' ' + part4;
            return out;
        }
        el.addEventListener('input', e=>{ e.target.value = fmt(e.target.value); });
        el.addEventListener('paste', e=>{ e.preventDefault(); e.target.value = fmt((e.clipboardData||window.clipboardData).getData('text')); });
        if (el.value) el.value = fmt(el.value);
    })();
    // Yeni dosya seçim UI (listele + kaldır)
    const fileInput = document.getElementById('files_input');
    const listEl = document.getElementById('selected_files_list');
    const maxTotal = 4;
    const existingCount = {{ (int) ($policy->files->count() ?? 0) }};
    let selectedFiles = [];

    function syncInputFiles() {
        if (!fileInput) return;
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function renderSelectedList() {
        if (!listEl) return;
        listEl.innerHTML = '';
        if (selectedFiles.length === 0) return;
        selectedFiles.forEach((file, idx) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            const left = document.createElement('div');
            left.className = 'd-flex align-items-center gap-2';
            left.innerHTML = '<i data-lucide="file" class="text-muted" style="width:16px;height:16px;"></i>'
                + '<span>' + file.name + '</span>'
                + '<small class="text-muted">(' + (file.size/1024).toFixed(1) + ' KB)</small>';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-outline-danger';
            removeBtn.textContent = 'Kaldır';
            removeBtn.addEventListener('click', function(){
                selectedFiles.splice(idx, 1);
                syncInputFiles();
                renderSelectedList();
            });
            li.appendChild(left);
            li.appendChild(removeBtn);
            listEl.appendChild(li);
        });
        if (window.lucide) { window.lucide.createIcons(); }
    }

    if (fileInput && listEl) {
        fileInput.addEventListener('change', function(){
            const incoming = Array.from(fileInput.files || []);
            for (const file of incoming) {
                if ((existingCount + selectedFiles.length) >= maxTotal) {
                    alert('Toplamda en fazla 4 dosya yükleyebilirsiniz. Mevcut dosyalarla birlikte sınır aşıldı.');
                    break;
                }
                if (file.size > 5 * 1024 * 1024) {
                    alert(file.name + ' dosyası çok büyük. Maksimum 5MB olmalıdır.');
                    continue;
                }
                selectedFiles.push(file);
            }
            syncInputFiles();
            renderSelectedList();
        });
    }

    // Dosya silme butonları
    const deleteButtons = document.querySelectorAll('.delete-file-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('Bu dosyayı silmek istediğinizden emin misiniz?')) {
                return;
            }
            
            const policyId = this.dataset.policyId;
            const fileId = this.dataset.fileId;
            const csrfToken = this.dataset.csrfToken;
            
            fetch(`/policies/${policyId}/files/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Dosya listesinden kaldır
                    this.closest('li').remove();
                    
                    // Liste boş kaldıysa mesaj göster
                    const filesList = document.querySelector('.existing-files-list');
                    if (filesList && filesList.children.length === 0) {
                        filesList.innerHTML = '<div class="text-muted">Bu poliçe için dosya yok.</div>';
                    }
                } else {
                    alert('Dosya silinirken hata oluştu: ' + (data.message || 'Bilinmeyen hata'));
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Dosya silinirken hata oluştu.');
            });
        });
    });
});
</script>
@endpush

