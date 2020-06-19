<?php
declare(strict_types=1);

namespace App\Controller\V1;

use App\Dto\Api\V1\Response\ErrorResponse;
use App\Dto\Api\V1\Response\SessionListResponse;
use App\Entity\User;
use App\Factory\Api\V1\SessionResponseFactoryInterface;
use App\Service\User\UserSessionManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param UserInterface                   $user
     * @param UserSessionManagerInterface     $sessionManager
     * @param SessionResponseFactoryInterface $responseFactory
     *
     * @return JsonResponse
     */
    public function sessionListAction(
        UserInterface $user,
        UserSessionManagerInterface $sessionManager,
        SessionResponseFactoryInterface $responseFactory
    ): JsonResponse
    {
        /** @var User $user */
        $items = $sessionManager->getByUser($user);

        return $this->createJsonResponse(
            $responseFactory->createSessionListResponse($items)
        );
    }
}