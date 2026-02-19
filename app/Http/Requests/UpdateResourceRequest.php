<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && ($this->user()->hasRole('admin') || $this->user()->hasRole('manager'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $resourceId = $this->route('resource')?->id;

        return [
            'name' => 'sometimes|string|min:3|max:100|unique:resources,name,' . $resourceId,
            'description' => 'nullable|string|max:1000',
            'cpu_cores' => 'nullable|integer|min:1|max:1000',
            'ram_gb' => 'nullable|integer|min:1|max:10000',
            'storage_gb' => 'nullable|integer|min:1|max:1000000',
            'price_per_hour' => 'sometimes|numeric|min:0.01',
            'status' => 'sometimes|in:available,in_use,maintenance,inactive',
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
            'name.unique' => 'Une ressource avec ce nom existe déjà.',
            'price_per_hour.numeric' => 'Le tarif horaire doit être un nombre.',
        ];
    }
}
