<?php

namespace App\Http\Controllers;

use App\Models\PolicyType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PolicyTypeController extends Controller
{
    /**
     * Poliçe türlerini listele
     */
    public function index()
    {
        $policyTypes = PolicyType::ordered()->get();
        
        return view('settings.policy-types.index', compact('policyTypes'));
    }

    /**
     * Poliçe türü ekleme formu
     */
    public function create()
    {
        return view('settings.policy-types.create');
    }

    /**
     * Yeni poliçe türü kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:policy_types,name',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Ad alanı zorunludur.',
            'name.unique' => 'Bu ad zaten kullanılıyor.',
            'name.max' => 'Ad en fazla 255 karakter olmalıdır.',
            'description.max' => 'Açıklama en fazla 1000 karakter olmalıdır.'
        ]);

        $newPolicyType = PolicyType::create([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? PolicyType::max('sort_order') + 1,
            'is_active' => $request->is_active ?? true
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Poliçe türü başarıyla eklendi.',
                'data' => $newPolicyType
            ]);
        }

        return redirect()->route('settings.policy-types.index')
            ->with('success', 'Poliçe türü başarıyla eklendi.');
    }

    /**
     * Poliçe türü düzenleme formu
     */
    public function edit(PolicyType $policyType)
    {
        return view('settings.policy-types.edit', compact('policyType'));
    }

    /**
     * Poliçe türü güncelle
     */
    public function update(Request $request, PolicyType $policyType)
    {
        $isStatusToggle = $request->expectsJson()
            && $request->has('is_active')
            && !$request->has('name')
            && !$request->has('description')
            && !$request->has('sort_order');

        $rules = $isStatusToggle
            ? [
                'is_active' => 'required|in:0,1,true,false,on,yes'
            ]
            : [
                'name' => 'sometimes|required|string|max:255|unique:policy_types,name,' . $policyType->id,
                'description' => 'sometimes|nullable|string|max:1000',
                'sort_order' => 'sometimes|nullable|integer|min:0',
                'is_active' => 'sometimes|in:0,1,true,false,on,yes'
            ];

        $request->validate($rules, [
            'name.required' => 'Ad alanı zorunludur.',
            'name.unique' => 'Bu ad zaten kullanılıyor.',
            'name.max' => 'Ad en fazla 255 karakter olmalıdır.',
            'description.max' => 'Açıklama en fazla 1000 karakter olmalıdır.',
            'is_active.in' => 'Durum değeri geçersiz.'
        ]);

        $oldName = $policyType->name;
        
        $updateData = [];
        
        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->has('description')) {
            $updateData['description'] = $request->description;
        }
        if ($request->has('sort_order')) {
            $updateData['sort_order'] = $request->sort_order;
        }
        
        // is_active alanı gönderildiyse güncelle
        if ($request->has('is_active')) {
            $value = $request->is_active;
            if (is_string($value)) {
                $updateData['is_active'] = in_array(strtolower($value), ['1', 'true', 'on', 'yes']);
            } else {
                $updateData['is_active'] = (bool) $value;
            }
        }
        
        if (!empty($updateData)) {
            $policyType->update($updateData);
        }

        // Eğer isim değiştiyse, mevcut poliçelerde güncelle
        if ($request->has('name') && $oldName !== $request->name) {
            \App\Models\Policy::where('policy_type', $oldName)
                ->update(['policy_type' => $request->name]);
        }

        // AJAX isteği ise JSON döndür
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Poliçe türü başarıyla güncellendi.'
            ]);
        }

        return redirect()->route('settings.policy-types.index')
            ->with('success', 'Poliçe türü başarıyla güncellendi.');
    }

    /**
     * Poliçe türü sil
     */
    public function destroy(PolicyType $policyType)
    {
        if (!$policyType->can_delete) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu poliçe türü kullanımda olduğu için silinemez.'
                ]);
            }
            return back()->with('error', 'Bu poliçe türü kullanımda olduğu için silinemez.');
        }

        $policyType->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Poliçe türü başarıyla silindi.'
            ]);
        }

        return redirect()->route('settings.policy-types.index')
            ->with('success', 'Poliçe türü başarıyla silindi.');
    }

    /**
     * Poliçe türü pasife al
     */
    public function deactivate(PolicyType $policyType)
    {
        if ($policyType->can_deactivate) {
            $policyType->deactivate();
            return back()->with('success', 'Poliçe türü pasife alındı.');
        }

        return back()->with('error', 'Bu poliçe türü pasife alınamaz.');
    }

    /**
     * Poliçe türü aktifleştir
     */
    public function activate(PolicyType $policyType)
    {
        $policyType->activate();
        return back()->with('success', 'Poliçe türü aktifleştirildi.');
    }

    /**
     * Sıralama güncelle
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:policy_types,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            PolicyType::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * API: Aktif poliçe türlerini getir
     */
    public function getActive(): JsonResponse
    {
        $policyTypes = PolicyType::active()->ordered()->pluck('name');
        
        return response()->json($policyTypes);
    }
}
