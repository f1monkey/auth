<?php
declare(strict_types=1);

namespace App\Controller\V1;

use App\Dto\Api\V1\Response\ErrorResponse;
use App\Dto\Api\V1\Response\SessionListResponse;
use App\Dto\Api\V1\Response\SessionResponse;
use App\Entity\User;
use App\Enum\RegexEnum;
use App\Exception\Entity\EntityNotFoundException;
use App\Factory\Api\V1\SessionResponseFactoryInterface;
use App\Service\User\UserSessionManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthController
 *
 * @package App\Controller\V1
 *
 * @Route("/sessions", name="sessions_")
 */
class SessionController
{
    use ResponseSerializeTrait;

    /**
     * @var UserSessionManagerInterface
     */
    protected UserSessionManagerInterface $sessionManager;

    /**
     * @var SessionResponseFactoryInterface
     */
    protected SessionResponseFactoryInterface $responseFactory;

    /**
     * SessionController constructor.
     *
     * @param UserSessionManagerInterface     $sessionManager
     * @param SessionResponseFactoryInterface $responseFactory
     */
    public function __construct(
        UserSessionManagerInterface $sessionManager,
        SessionResponseFactoryInterface $responseFactory
    )
    {
        $this->sessionManager  = $sessionManager;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Get active sessions
     *
     * @Route("", name="list", methods={Request::METHOD_GET})
     *
     * @SWG\Parameter(
     *     in="header",
     *     name="Authorization",
     *     description="Authorization header",
     *     type="string",
     *     required=true
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="List of active user sessions",
     *     @Model(type=SessionListResponse::class)
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
     * @SWG\Tag(name="sessions")
     *
     * @param UserInterface $user
     *
     * @return JsonResponse
     */
    public function sessionListAction(UserInterface $user): JsonResponse
    {
        /** @var User $user */
        $items = $this->sessionManager->getByUser($user);

        return $this->createJsonResponse(
            $this->responseFactory->createSessionListResponse($items)
        );
    }

    /**
     * Invalidate active session
     *
     * @Route("/{id}", name="invalidate", methods={Request::METHOD_DELETE}, requirements={"id":RegexEnum::UUID_V4})
     *
     * @SWG\Parameter(
     *     in="header",
     *     name="Authorization",
     *     description="Authorization header",
     *     type="string",
     *     required=true
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description="Invalidated session",
     *     @Model(type=SessionResponse::class)
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
     *     response=Response::HTTP_NOT_FOUND,
     *     description="Not found",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Response(
     *     response=Response::HTTP_INTERNAL_SERVER_ERROR,
     *     description="Internal server error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @SWG\Tag(name="sessions")
     *
     * @param UserInterface $user
     * @param string        $id
     *
     * @return JsonResponse
     * @throws NotFoundHttpException
     */
    public function invalidateSessionAction(UserInterface $user, string $id): JsonResponse
    {
        try {
            /** @var User $user */
            $refreshToken = $this->sessionManager->getById($user, $id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException('');
        }

        $response = $this->responseFactory->createSessionResponse($refreshToken);
        $this->sessionManager->delete($refreshToken);

        return $this->createJsonResponse($response);
    }
}