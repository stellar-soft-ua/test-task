<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\VerificationResult;
use App\Exceptions\MissedJsonStructureException;
use App\Exceptions\MissedJsonStructureValidationMethodException;
use App\Http\Requests\Api\Domain\Json\IssuerRequest;
use App\Http\Requests\Api\Domain\Json\RecipientRequest;
use App\Interfaces\VerifyService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DomainService
{
    private array $jsonStructure;

    /**
     * List of JSON structure validation methods
     */
    private array $validateJsonMethods = [
        'validateRecipient',
        'validateIssuer',
        'validateSignature',
    ];

    private array $response = [
        'data' => [
            'issuer' => '',
            'result' => '',
        ],
    ];

    public function __construct(public VerifyService $verifyService) {}

    public function setJsonStructure($jsonStructure): void
    {
        try {
            $this->jsonStructure = json_decode($jsonStructure, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            throw new MissedJsonStructureException('Invalid JSON structure.');
        }
    }

    /**
     * Validate JSON structure by call method described on validateJsonMethods
     *
     * @return array|\string[][]
     *
     * @throws MissedJsonStructureValidationMethodException
     */
    public function validateStrucrure(): array
    {

        foreach ($this->validateJsonMethods as $method) {

            try {

                if ($this->{$method}() === false) {
                    return $this->response;
                }

            } catch (\Exception $e) {

                print_r($e->getTraceAsString());

                throw new MissedJsonStructureValidationMethodException('Missed JSON structure validation method.');
            }

        }

        $this->response['data']['result'] = Str::snake(VerificationResult::Verified->name);

        return $this->response;
    }

    private function validateRecipient(): bool
    {

        $validator = Validator::make($this->jsonStructure, (new RecipientRequest)->rules());

        if ($validator->fails()) {
            $this->response['data'] = [
                'result' => Str::snake(VerificationResult::InvalidRecipient->name),
            ];

            return false;
        }

        return true;
    }

    private function validateIssuer(): bool
    {

        $validator = Validator::make($this->jsonStructure, (new IssuerRequest)->rules());

        if ($validator->valid()) {
            $rawDnsData = $this->verifyService->lookupDns($this->jsonStructure['data']['issuer']['identityProof']['location']);

            $checkDnsRecord = collect($rawDnsData)->filter(function ($value) {
                return DomainService::checkDnsRecord($value['data'] ?? '', $this->jsonStructure['data']['issuer']['identityProof']['key'] ?? '');
            })->count();

            if ($checkDnsRecord) {
                $this->response['data']['issuer'] = $this->jsonStructure['data']['issuer']['name'];

                return true;
            }
        }

        $this->response['data'] = [
            'result' => Str::snake(VerificationResult::InvalidIssuer->name),
        ];

        return false;
    }

    public static function checkDnsRecord(string $dnsRecord, string $key)
    {
        if ($dnsRecord !== '' && str_contains($dnsRecord, $key)) {
            return true;
        }

        return false;
    }

    private function validateSignature(): bool
    {

        $dataHash = [];

        $dotNotationData = arrayToDotNotation($this->jsonStructure['data']);

        foreach ($dotNotationData as $dataLine) {
            $dataHash[] = hash('sha256', $dataLine);
        }

        sort($dataHash, SORT_STRING);

        $calculatedSignature = hash('sha256', implode('', $dataHash));

        if ($calculatedSignature === $this->jsonStructure['signature']['targetHash']) {
            return true;
        } else {
            $this->response['data'] = [
                'result' => Str::snake(VerificationResult::InvalidSignature->name),
            ];

            return false;
        }
    }
}
