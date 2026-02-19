<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100|unique:resources',
            'resource_category_id' => 'required|exists:resource_categories,id|numeric',
            'serial_number' => 'required|string|unique:resources|min:3|max:50',
            'description' => 'nullable|string|max:1000',
            'cpu_cores' => 'nullable|integer|min:1|max:1000',
            'ram_gb' => 'nullable|integer|min:1|max:10000',
            'storage_gb' => 'nullable|integer|min:1|max:1000000',
            'purchase_price' => 'required|numeric|min:0',
            'price_per_hour' => 'required|numeric|min:0.01',
            'status' => 'required|in:available,in_use,maintenance,inactive',
            'location' => 'nullable|string|max:100',
            'bandwidth_gbps' => 'nullable|numeric|min:0',
            'storage_type' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la ressource est requis.',
            'name.unique' => 'Une ressource avec ce nom existe déjà.',
            'serial_number.unique' => 'Un numéro de série unique est requis.',
            'resource_category_id.required' => 'Veuillez sélectionner une catégorie.',
            'purchase_price.required' => 'Le prix d\'achat est requis.',
            'price_per_hour.required' => 'Le tarif horaire est requis.',
        ];
    }
}
