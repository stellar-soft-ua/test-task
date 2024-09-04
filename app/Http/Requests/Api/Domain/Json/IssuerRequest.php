<?php

namespace App\Http\Requests\Api\Domain\Json;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'IssuerRequest',
    required: ['data'],

)]

class IssuerRequest extends FormRequest
{

    #[Property(property: 'data', type: 'array', items: new Items(properties: [
        new Property(property: 'issuer', type: 'array', items: new Items(properties: [
            new Property(property: 'name', type: 'string'),
            new Property(property: 'identityProof', type: 'array', items: new Items(properties: [
                new Property(property: 'type', type: 'string'),
                new Property(property: 'key', type: 'string'),
                new Property(property: 'location', type: 'string'),
            ])),
        ])),
    ]))]    /**
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
            'data.issuer.name' => 'required',
            'data.issuer.identityProof' => ['required', 'array:type,key,location'],
        ];
    }
}
