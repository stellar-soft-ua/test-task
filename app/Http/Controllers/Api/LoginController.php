<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Login\AuthenticateRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OAA;

#[
    OAA\Post(
        path: '/api/login',
        summary: 'Returns Auth Details',
        tags: ['Auth'],
    ),
    OAA\RequestBody(
        content: new OAA\JsonContent(ref: '#/components/schemas/AuthenticateRequest'),
    ),
    OAA\Response(
        response: Response::HTTP_OK,
        description: 'Successful operation',
        content: new OAA\JsonContent(
            properties: [
                new OAA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OAA\Items(
                        properties: [
                            new OAA\Property(
                                property: 'token',
                                type: 'string',
                            ),
                        ]
                    )
                ),
            ],
        ),
    ),
    OAA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: 'Not authorized',
        content: new OAA\JsonContent(
            properties: [
                new OAA\Property(
                property: 'message',
                    type: 'string',
                ),
            ],
        ),
    ),
]
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {

            $token = $request->user()->createToken('authToken')->plainTextToken;

            return response()->json([
                'data' => [
                    'token' => $token,
                ],
            ], 200);
        }

        return response()->json(['message' => __('The provided credentials do not match our records.')], 401);
    }
}
