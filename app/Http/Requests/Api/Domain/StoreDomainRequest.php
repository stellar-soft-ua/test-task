<?php

namespace App\Http\Requests\Api\Domain;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'StoreDomainRequest',
    required: ['file'],
    properties: [
        new Property(property: 'file', type: 'string', format: 'binary'),
    ]
)]
class StoreDomainRequest extends FormRequest
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
            'file' => ['required', 'file', 'max:2048', 'mimetypes:application/json'],
        ];
    }
}
