<?php

namespace App\Http\Requests\Api\Domain\Json;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'RecipientRequest',
    required: ['data'],

)]
class RecipientRequest extends FormRequest
{
    #[Property(property: 'data', type: 'array', items: new Items(properties: [
        new Property(property: 'recipient', type: 'array', items: new Items(properties: [
            new Property(property: 'name', type: 'string'),
            new Property(property: 'email', type: 'string', format: 'email'),
        ])),
    ]))]
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
            'data.recipient.name' => 'required',
            'data.recipient.email' => ['required', 'email'],
        ];
    }
}
