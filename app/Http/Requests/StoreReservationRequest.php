<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreReservationRequest extends FormRequest
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
            'reason' => 'nullable|string|min:3|max:500',
            'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'resource_id.required' => 'Veuillez sélectionner une ressource.',
            'resource_id.exists' => 'La ressource sélectionnée n\'existe pas.',
            'reason.required' => 'Veuillez fournir une raison pour cette réservation.',
            'reason.min' => 'La raison doit contenir au moins 10 caractères.',
            'quantity.required' => 'Veuillez spécifier la quantité.',
            'quantity.min' => 'La quantité doit être au moins 1.',
            'start_time.required' => 'La date/heure de début est requise.',
            'start_time.after_or_equal' => 'La date de début doit être dans le futur.',
            'end_time.after' => 'La date de fin doit être après la date de début.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('justification')) {
            $data['reason'] = $this->input('justification');
        }

        if ($this->has('start_time')) {
            $data['start_time'] = $this->input('start_time');
        }

        if ($this->has('end_time')) {
            $data['end_time'] = $this->input('end_time');
        }

        // Ensure reason is non-null to satisfy DB constraint
        if (empty($data['reason'])) {
            $data['reason'] = 'Demande sans justification';
        }

        if (!empty($data)) {
            $this->merge($data);
        }
    }
}
