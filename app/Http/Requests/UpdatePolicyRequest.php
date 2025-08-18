<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePolicyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Müşteri alanları poliçe düzenlemeden güncellenemez
            'customer_title' => 'prohibited',
            'customer_identity_number' => 'prohibited',
            'customer_phone' => 'prohibited',
            'customer_birth_date' => 'prohibited',
            'customer_address' => 'prohibited',
            'insured_name' => 'nullable|string|max:255',
            'insured_phone' => ['nullable','string','max:20','regex:/^0\d{3} \d{3} \d{2} \d{2}$/'],
            'policy_type' => 'required|string|max:255',
            'policy_company' => 'nullable|string|max:255',
            'policy_number' => ['required', 'string', 'max:255', Rule::unique('policies')->ignore($this->policy)],
            'plate_or_other' => 'nullable|string|max:255',
            'issue_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'document_info' => 'nullable|string|max:255',
            'tarsim_business_number' => 'nullable|string|max:255',
            'tarsim_animal_number' => 'nullable|string|max:255',
            // Gelir Yönetimi Alanları
            'policy_premium' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'payment_due_date' => 'nullable|date',
            'payment_notes' => 'nullable|string',
            'invoice_number' => 'nullable|string|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            // Dosyalar
            'files' => 'nullable|array|max:4',
            'files.*' => 'file|max:5120|mimes:pdf,csv,xlsx,xls,doc,docx,jpg,jpeg,png,rar',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'insured_phone.regex' => 'Sigorta Ettiren Telefon formatı geçersiz. Örnek: 0XXX XXX XX XX',
            'end_date.after_or_equal' => 'Bitiş Tarihi, Başlangıç Tarihinden sonra veya eşit olmalıdır.',
        ];
    }
}

