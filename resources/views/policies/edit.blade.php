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

    <form action="{{ route('policies.update', $policy) }}" method="POST">
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
                            <input type="text" class="form-control" id="customer_title" name="customer_title" value="{{ old('customer_title', $policy->customer_title) }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_identity_number" class="form-label fw-normal text-secondary">TC/Vergi No</label>
                                    <input type="text" class="form-control" id="customer_identity_number" name="customer_identity_number" value="{{ old('customer_identity_number', $policy->customer_identity_number) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label fw-normal text-secondary">Müşteri Telefon</label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $policy->customer_phone) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_birth_date" class="form-label fw-normal text-secondary">Doğum Tarihi</label>
                                    <input type="date" class="form-control" id="customer_birth_date" name="customer_birth_date" value="{{ old('customer_birth_date', $policy->customer_birth_date ? $policy->customer_birth_date->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label fw-normal text-secondary">Adres</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required>{{ old('customer_address', $policy->customer_address) }}</textarea>
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
                            <input type="text" class="form-control" id="insured_phone" name="insured_phone" value="{{ old('insured_phone', $policy->insured_phone) }}">
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


