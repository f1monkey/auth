<?php
declare(strict_types=1);

namespace App\EventListener\RefreshToken;

use App\Exception\Entity\EntityNotFoundException;
use App\Service\User\UserSessionManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SetUserInfoListener
 *
 * @package App\EventListener\RefreshToken
 */
class SetUserInfoOnCreateListener
{
    /**
     * @var UserSessionManagerInterface
     */
    protected UserSessionManagerInterface $userSessionManager;

    /**
     * @var RequestStack
     */
    protected RequestStack $requestStack;

    /**
     * @var string
     */
    protected string $refreshTokenField;

    /**
     * SetUserInfoOnCreateListener constructor.
     *
     * @param UserSessionManagerInterface $userSessionManager
     * @param RequestStack                $requestStack
     * @param string                      $refreshTokenField
     */
    public function __construct(
        UserSessionManagerInterface $userSessionManager,
        RequestStack $requestStack,
        string $refreshTokenField
    )
    {
        $this->userSessionManager = $userSessionManager;
        $this->requestStack       = $requestStack;
        $this->refreshTokenField  = $refreshTokenField;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     *
     * @throws EntityNotFoundException
     */
    public function setUserInfo(AuthenticationSuccessEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return;
        }

        $data         = $event->getData();
        $refreshToken = $data[$this->refreshTokenField] ?? null;

        $token = $this->userSessionManager->getByTokenValue($refreshToken);
        $token->setIp($request->server->get('REMOTE_ADDR'))
              ->setUserAgent($request->server->get('HTTP_USER_AGENT'));
        $this->userSessionManager->save($token);
    }
}