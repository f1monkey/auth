<?php
declare(strict_types=1);

namespace App\Controller\V1;

use App\Dto\Api\V1\Request\LoginRequest;
use App\Dto\Api\V1\Request\RefreshRequest;
use App\Dto\Api\V1\Response\ErrorResponse;
use App\Dto\Api\V1\Response\TokenResponse;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class AuthController
 *
 * @package App\Controller\V1
 *
 * @Route("/auth", name="v1_auth_")
 */
class AuthController
{
    /**
     * @Route("/login", name="login", methods={Request::METHOD_POST})
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Authorization data",
     *     @Model(type=LoginRequest::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Authorization token",
     *     @Model(type=TokenResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Bad Request",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_UNAUTHORIZED,
     *     description="Unauthorized",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_INTERNAL_SERVER_ERROR,
     *     description="Internal server error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Tag(name="auth")
     *
     * @return JsonResponse
     */
    public function loginAction(): JsonResponse
    {
        return new JsonResponse('');
    }

    /**
     * @Route("/refresh", name="refresh", methods={Request::METHOD_POST})
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Refresh token request",
     *     @Model(type=RefreshRequest::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Authorization token",
     *     @Model(type=TokenResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_BAD_REQUEST,
     *     description="Bad Request",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_UNAUTHORIZED,
     *     description="Unauthorized",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_INTERNAL_SERVER_ERROR,
     *     description="Internal server error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Tag(name="auth")
     *
     * @param Request      $request
     * @param RefreshToken $refreshTokenService
     *
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function refreshAction(Request $request, RefreshToken $refreshTokenService): JsonResponse
    {
        return $refreshTokenService->refresh($request);
    }
}