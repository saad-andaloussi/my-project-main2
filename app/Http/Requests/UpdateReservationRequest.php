<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->id === $this->reservation->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'reason' => 'sometimes|nullable|string|min:3|max:500',
            'start_time' => 'sometimes|date_format:Y-m-d\TH:i|after_or_equal:now',
            'end_time' => 'sometimes|date_format:Y-m-d\TH:i|after:start_time',
            'status' => 'sometimes|in:pending,approved,declined,cancelled,active,completed',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'reason.min' => 'La raison doit contenir au moins 10 caractères.',
            'quantity.min' => 'La quantité doit être au moins 1.',
            'status.in' => 'Le statut sélectionné est invalide.',
        ];
    }
}
