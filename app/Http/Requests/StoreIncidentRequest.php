<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'resource_id' => 'required|exists:resources,id|numeric',
            'title' => 'required|string|min:5|max:100',
            'description' => 'required|string|min:10|max:1000',
            'severity' => 'required|in:low,medium,high,critical',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'resource_id.required' => 'Veuillez sélectionner une ressource.',
            'title.required' => 'Veuillez fournir un titre pour l\'incident.',
            'title.min' => 'Le titre doit contenir au moins 5 caractères.',
            'description.required' => 'Veuillez décrire l\'incident en détail.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'severity.required' => 'Veuillez indiquer le niveau de gravité.',
        ];
    }
}
