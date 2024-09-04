<?php

namespace App\Http\Requests\Api\Login;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'AuthenticateRequest',
    required: ['email', 'password'],
    properties: [
        new Property(property: 'email', type: 'string', format: 'email'),
        new Property(property: 'password', type: 'string'),
    ]
)]
class AuthenticateRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }
}
