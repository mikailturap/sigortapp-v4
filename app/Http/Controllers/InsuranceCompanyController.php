<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InsuranceCompanyController extends Controller
{
    /**
     * Sigorta şirketlerini listele
     */
    public function index()
    {
        $insuranceCompanies = InsuranceCompany::ordered()->get();
        
        return view('settings.insurance-companies.index', compact('insuranceCompanies'));
    }

    /**
     * Sigorta şirketi ekleme formu
     */
    public function create()
    {
        return view('settings.insurance-companies.create');
    }

    /**
     * Yeni sigorta şirketi kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:insurance_companies,name',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ], [
            'name.required' => 'Ad alanı zorunludur.',
            'name.unique' => 'Bu ad zaten kullanılıyor.',
            'name.max' => 'Ad en fazla 255 karakter olmalıdır.',
            'description.max' => 'Açıklama en fazla 1000 karakter olmalıdır.'
        ]);

        $insuranceCompany = InsuranceCompany::create([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? InsuranceCompany::max('sort_order') + 1,
            'is_active' => $request->is_active ?? true
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sigorta şirketi başarıyla eklendi.',
                'data' => $insuranceCompany
            ]);
        }

        return redirect()->route('settings.insurance-companies.index')
            ->with('success', 'Sigorta şirketi başarıyla eklendi.');
    }

    /**
     * Sigorta şirketi düzenleme formu
     */
    public function edit(InsuranceCompany $insuranceCompany)
    {
        return view('settings.insurance-companies.edit', compact('insuranceCompany'));
    }

    /**
     * Sigorta şirketi güncelle
     */
    public function update(Request $request, InsuranceCompany $insuranceCompany)
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
                'name' => 'sometimes|required|string|max:255|unique:insurance_companies,name,' . $insuranceCompany->id,
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

        $oldName = $insuranceCompany->name;
        
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
            $insuranceCompany->update($updateData);
        }

        // Eğer isim değiştiyse, mevcut poliçelerde güncelle
        if ($request->has('name') && $oldName !== $request->name) {
            \App\Models\Policy::where('policy_company', $oldName)
                ->update(['policy_company' => $request->name]);
        }

        // AJAX isteği ise JSON döndür
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sigorta şirketi başarıyla güncellendi.'
            ]);
        }

        return redirect()->route('settings.insurance-companies.index')
            ->with('success', 'Sigorta şirketi başarıyla güncellendi.');
    }

    /**
     * Sigorta şirketi sil
     */
    public function destroy(InsuranceCompany $insuranceCompany)
    {
        if (!$insuranceCompany->can_delete) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu sigorta şirketi kullanımda olduğu için silinemez.'
                ]);
            }
            return back()->with('error', 'Bu sigorta şirketi kullanımda olduğu için silinemez.');
        }

        $insuranceCompany->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sigorta şirketi başarıyla silindi.'
            ]);
        }

        return redirect()->route('settings.insurance-companies.index')
            ->with('success', 'Sigorta şirketi başarıyla silindi.');
    }

    /**
     * Sigorta şirketi pasife al
     */
    public function deactivate(InsuranceCompany $insuranceCompany)
    {
        if ($insuranceCompany->can_deactivate) {
            $insuranceCompany->deactivate();
            return back()->with('success', 'Sigorta şirketi pasife alındı.');
        }

        return back()->with('error', 'Bu sigorta şirketi pasife alınamaz.');
    }

    /**
     * Sigorta şirketi aktifleştir
     */
    public function activate(InsuranceCompany $insuranceCompany)
    {
        $insuranceCompany->activate();
        return back()->with('success', 'Sigorta şirketi aktifleştirildi.');
    }

    /**
     * Sıralama güncelle
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:insurance_companies,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            InsuranceCompany::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * API: Aktif sigorta şirketlerini getir
     */
    public function getActive(): JsonResponse
    {
        $insuranceCompanies = InsuranceCompany::active()->ordered()->pluck('name');
        
        return response()->json($insuranceCompanies);
    }
}
