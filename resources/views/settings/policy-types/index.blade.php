@extends('layouts.app')

@section('title', 'Poliçe Türleri')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 mb-0 text-dark fw-normal">
            <i data-lucide="tag" class="text-muted me-2" style="width: 24px; height: 24px;"></i>
            Poliçe Türleri
        </h1>
        <p class="text-muted mb-0 mt-1 small">Poliçe türlerini yönetin ve sıralayın</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('settings.index') }}#management" class="btn btn-outline-secondary" onclick="return goToPolicyManagement()">
            <i data-lucide="arrow-left" class="me-1" style="width: 16px; height: 16px;"></i>
            Geri Dön
        </a>
        <a href="{{ route('settings.policy-types.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="me-1" style="width: 16px; height: 16px;"></i>
            Yeni Poliçe Türü
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i data-lucide="check-circle" class="me-2" style="width: 18px; height: 18px;"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i data-lucide="alert-circle" class="me-2" style="width: 18px; height: 18px;"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex align-items-center">
            <i data-lucide="list" class="text-muted me-2" style="width: 18px; height: 18px;"></i>
            <h5 class="mb-0 fw-normal text-dark">Poliçe Türleri Listesi</h5>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0" style="width: 50px;">
                            <i data-lucide="grip-vertical" class="text-muted" style="width: 16px; height: 16px;"></i>
                        </th>
                        <th class="border-0">Poliçe Türü</th>
                        <th class="border-0">Açıklama</th>
                        <th class="border-0 text-center">Sıra</th>
                        <th class="border-0 text-center">Durum</th>
                        <th class="border-0 text-center">Kullanım</th>
                        <th class="border-0 text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody id="sortable-policy-types">
                    @forelse($policyTypes as $policyType)
                    <tr data-id="{{ $policyType->id }}" class="sortable-item">
                        <td class="align-middle">
                            <i data-lucide="grip-vertical" class="text-muted cursor-move" style="width: 16px; height: 16px;"></i>
                        </td>
                        <td class="align-middle">
                            <strong>{{ $policyType->name }}</strong>
                        </td>
                        <td class="align-middle">
                            <span class="text-muted">{{ $policyType->description ?: 'Açıklama yok' }}</span>
                        </td>
                        <td class="align-middle text-center">
                            <span class="badge bg-light text-dark">{{ $policyType->sort_order }}</span>
                        </td>
                        <td class="align-middle text-center">
                            @if($policyType->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <span class="badge bg-info">{{ $policyType->policies()->count() }} poliçe</span>
                        </td>
                        <td class="align-middle text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('settings.policy-types.edit', $policyType) }}" 
                                   class="btn btn-outline-primary btn-sm" title="Düzenle">
                                    <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                                </a>
                                
                                @if($policyType->is_active && $policyType->can_deactivate)
                                    <form action="{{ route('settings.policy-types.deactivate', $policyType) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-warning btn-sm" 
                                                title="Pasife Al" onclick="return confirm('Bu poliçe türünü pasife almak istediğinizden emin misiniz?')">
                                            <i data-lucide="eye-off" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                @elseif(!$policyType->is_active)
                                    <form action="{{ route('settings.policy-types.activate', $policyType) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Aktifleştir">
                                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($policyType->can_delete)
                                    <form action="{{ route('settings.policy-types.destroy', $policyType) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                title="Sil" onclick="return confirm('Bu poliçe türünü silmek istediğinizden emin misiniz?')">
                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm" disabled title="Kullanımda olduğu için silinemez">
                                        <i data-lucide="lock" style="width: 14px; height: 14px;"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i data-lucide="inbox" class="mb-2" style="width: 48px; height: 48px; margin: 0 auto; display: block;"></i>
                                <p class="mb-0">Henüz poliçe türü eklenmemiş</p>
                                <a href="{{ route('settings.policy-types.create') }}" class="btn btn-primary btn-sm mt-2">
                                    İlk Poliçe Türünü Ekle
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Geri tuşu ile Poliçe Yönetimi tabına dön
function goToPolicyManagement() {
    // Local storage'a hangi tabın açık olacağını kaydet
    localStorage.setItem('activeSettingsTab', 'management');
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const sortableList = document.getElementById('sortable-policy-types');
    
    if (sortableList) {
        new Sortable(sortableList, {
            animation: 150,
            handle: '.cursor-move',
            onEnd: function(evt) {
                const items = Array.from(sortableList.querySelectorAll('.sortable-item')).map((item, index) => ({
                    id: item.dataset.id,
                    sort_order: index
                }));
                
                // Sıralama güncelle
                fetch('{{ route("settings.policy-types.update-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Sıra numaralarını güncelle
                        items.forEach((item, index) => {
                            const row = sortableList.querySelector(`[data-id="${item.id}"]`);
                            const badge = row.querySelector('.badge');
                            if (badge) {
                                badge.textContent = index;
                            }
                        });
                    }
                })
                .catch(error => console.error('Sıralama güncellenirken hata:', error));
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.cursor-move {
    cursor: move;
}
.sortable-ghost {
    opacity: 0.5;
    background: #f8f9fa;
}
</style>
@endpush
@endsection
