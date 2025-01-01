<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'year' => 'required|integer|min:2000|max:'.now()->addYear()->year,
            'month' => 'required|integer|between:1,12',
            'payment_method' => 'required|in:bank,cash',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string|max:500'
        ];
    }
}
