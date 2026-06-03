<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'            => 'required|string|max:255',
            'email'             => 'required|email|unique:usuarios,email',
            'password'          => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'   => 'El nombre es obligatorio.',
            'email.required'    => 'El email es obligatorio.',
            'email.unique'      => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error de validación.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
