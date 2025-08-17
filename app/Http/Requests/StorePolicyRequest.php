<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePolicyRequest extends FormRequest
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
            'customer_title' => 'required|string|max:255',
            'customer_identity_number' => 'required|string|max:20',
            'customer_phone' => 'required|string|max:20',
            'customer_birth_date' => 'required|date',
            'customer_address' => 'required|string',
            'customer_type' => 'required|in:bireysel,kurumsal',
            'insured_name' => 'nullable|string|max:255',
            'insured_phone' => 'nullable|string|max:20',
            'policy_type' => 'required|string|max:255',
            'policy_company' => 'nullable|string|max:255',
            'policy_number' => 'required|string|max:255|unique:policies,policy_number',
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
        ];
    }
}
