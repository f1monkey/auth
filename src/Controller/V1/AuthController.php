<?php
declare(strict_types=1);

namespace App\Controller\V1;

use App\Dto\Api\V1\Request\LoginConfirmRequest;
use App\Dto\Api\V1\Request\LoginRequest;
use App\Dto\Api\V1\Request\RefreshRequest;
use App\Dto\Api\V1\Request\RegisterRequest;
use App\Dto\Api\V1\Response\ErrorResponse;
use App\Dto\Api\V1\Response\TokenResponse;
use App\Dto\Api\V1\Response\UserResponse;
use App\Exception\Entity\EntityNotFoundException;
use App\Exception\User\UserAlreadyExistsException;
use App\Factory\Api\V1\UserResponseFactoryInterface;
use App\Service\User\UserManagerInterface;
use App\Service\User\UserRegisterServiceInterface;
use Gesdinet\JWTRefreshTokenBundle\Service\RefreshToken;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthController
 *
 * @package App\Controller\V1
 *
 * @Route("/auth", name="auth_")
 */
class AuthController
{
    use ResponseSerializeTrait;

    /**
     * @var UserResponseFactoryInterface
     */
    protected UserResponseFactoryInterface $responseFactory;

    /**
     * AuthController constructor.
     *
     * @param UserResponseFactoryInterface $responseFactory
     */
    public function __construct(UserResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Request authorization code
     *
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
     *     description="User data",
     *     @Model(type=UserResponse::class)
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
     *     response=Response::HTTP_FORBIDDEN,
     *     description="Forbidden",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_INTERNAL_SERVER_ERROR,
     *     description="Internal server error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Tag(name="auth")
     *
     * @param UserInterface        $currentUser
     * @param UserManagerInterface $userManager
     *
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function loginAction(UserInterface $currentUser, UserManagerInterface $userManager): JsonResponse
    {
        $user = $userManager->getByUsernameOrEmail($currentUser->getUsername());

        return $this->createJsonResponse(
            $this->responseFactory->createUserResponse($user)
        );
    }

    /**
     * Confirm authorization code
     *
     * @Route("/confirm", name="confirm", methods={Request::METHOD_POST})
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Auth code",
     *     @Model(type=LoginConfirmRequest::class)
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
    public function loginConfirmAction(): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * Refresh JWT
     *
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

    /**
     * Register new user
     *
     * @Route("/register", name="register", methods={Request::METHOD_POST})
     *
     * @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     description="Register request",
     *     @Model(type=RegisterRequest::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Registered user",
     *     @Model(type=UserResponse::class)
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
     * @param RegisterRequest              $request
     * @param UserRegisterServiceInterface $userRegisterService
     *
     * @return JsonResponse
     * @throws UserAlreadyExistsException
     */
    public function registerAction(
        RegisterRequest $request,
        UserRegisterServiceInterface $userRegisterService
    ): JsonResponse
    {
        $user     = $userRegisterService->register($request->getUsername(), $request->getEmail());
        $response = $this->responseFactory->createUserResponse($user);

        return $this->createJsonResponse($response);
    }
}