@extends('layouts.app')

@section('title', 'Poliçe Türü Düzenle')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-0 text-dark fw-normal">
            <span class="text-muted me-2" style="font-size: 24px;">✏️</span>
            Poliçe Türü Düzenle
        </h1>
        <p class="text-muted mb-0 mt-1 small">{{ $policyType->name }} poliçe türünü düzenleyin</p>
    </div>
            <a href="{{ route('settings.policy-types.index') }}" class="btn btn-outline-secondary">
            <span class="me-1" style="font-size: 16px;">⬅️</span>
            Geri Dön
        </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2" style="font-size: 18px;">🏷️</span>
                    <h5 class="mb-0 fw-normal text-dark">Poliçe Türü Bilgileri</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.policy-types.update', $policyType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-normal text-secondary">
                                    <span class="text-muted me-1" style="font-size: 14px;">🏷️</span>
                                    Poliçe Türü Adı <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $policyType->name) }}" 
                                       placeholder="Örn: Zorunlu Trafik Sigortası"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Poliçe türünün tam adını girin</div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label fw-normal text-secondary">
                                    <span class="text-muted me-1" style="font-size: 14px;">📋</span>
                                    Sıralama
                                </label>
                                <input type="number" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $policyType->sort_order) }}" 
                                       min="0"
                                       placeholder="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Listede görünecek sıra</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label fw-normal text-secondary">
                            <span class="text-muted me-1" style="font-size: 14px;">📝</span>
                            Açıklama
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Bu poliçe türü hakkında açıklama...">{{ old('description', $policyType->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">İsteğe bağlı açıklama ekleyin</div>
                    </div>

                    @if($policyType->policies()->count() > 0)
                        <div class="alert alert-info">
                            <span class="me-2" style="font-size: 18px;">ℹ️</span>
                            <strong>Dikkat:</strong> Bu poliçe türü {{ $policyType->name }} poliçede kullanılıyor. 
                            İsim değişikliği yaparsanız, mevcut poliçelerde de güncellenecektir.
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('settings.policy-types.index') }}" class="btn btn-outline-secondary">
                            <i data-lucide="x" class="me-1" style="width: 16px; height: 16px;"></i>
                            İptal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" class="me-1" style="width: 16px; height: 16px;"></i>
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
