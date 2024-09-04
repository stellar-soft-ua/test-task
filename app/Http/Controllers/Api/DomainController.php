<?php

namespace App\Http\Controllers\Api;

use App\Enums\FileType;
use App\Enums\VerificationResult;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Domain\StoreDomainRequest;
use App\Models\Domain;
use App\Services\DomainService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OpenApi\Attributes as OAA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DomainController extends Controller
{
    public function __construct(public DomainService $domainService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    #[
        OAA\Post(
            path: '/api/store',
            description: 'Store new domain',
            summary: 'Store new domain',
            security: [['bearer_token' => []]],
            tags: ['Domain'],
        ),
        OAA\RequestBody(
            content: new OAA\JsonContent(ref: '#/components/schemas/StoreDomainRequest'),
        ),
        OAA\Response(
            response: ResponseAlias::HTTP_OK,
            description: 'Successful operation',
            content: new OAA\JsonContent(
                properties: [
                    new OAA\Property(
                        property: 'message',
                        type: 'string',
                    ),
                    new OAA\Property(
                        property: 'errors',
                        type: 'string',
                    ),
                    new OAA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OAA\Items(
                            properties: [
                                new OAA\Property(
                                    property: 'issuer',
                                    type: 'string',
                                ),
                                new OAA\Property(
                                    property: 'result',
                                    type: 'string',
                                ),
                            ]
                        )
                    ),
                ],
            ),
        ),
        OAA\Response(
            response: ResponseAlias::HTTP_UNAUTHORIZED,
            description: 'Unauthenticated',
        )
    ]
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), (new StoreDomainRequest)->rules());

        if ($validator->fails()) {
            return response()->json(['message' => __('Uploaded file is missing.'), 'errors' => $validator->errors()], ResponseAlias::HTTP_OK);
        }

        $this->domainService->setJsonStructure($request->file('file')->getContent());
        $result = $this->domainService->validateStrucrure();

        $verificationResult = Str::ucfirst(Str::camel($result['data']['result']));

        Domain::create([
            'user_id' => Auth::user()->id,
            'file_type' => FileType::Json->value,
            'verification_result' => array_search($verificationResult, VerificationResult::toArray()),
        ]);

        return \response()->json($result, ResponseAlias::HTTP_OK);
    }
}
