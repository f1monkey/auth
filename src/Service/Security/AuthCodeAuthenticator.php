<?php
declare(strict_types=1);

namespace App\Service\Security;

use App\Dto\Api\V1\Request\LoginConfirmRequest;
use App\Dto\Api\V1\Request\LoginRequest;
use App\Entity\User;
use App\Exception\Api\V1\UnauthorizedHttpException;
use App\Exception\Entity\EntityNotFoundException;
use App\Service\AuthCode\AuthCodeManager;
use Doctrine\ORM\NonUniqueResultException;
use F1Monkey\RequestHandleBundle\Exception\InvalidRequestBodyException;
use F1Monkey\RequestHandleBundle\Exception\Validation\RequestValidationException;
use F1Monkey\RequestHandleBundle\Service\RequestDeserializerInterface;
use F1Monkey\RequestHandleBundle\Service\RequestValidatorInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class AuthCodeAuthenticator
 *
 * @package App\Service\Security
 */
class AuthCodeAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var RequestDeserializerInterface
     */
    protected RequestDeserializerInterface $requestDeserializer;

    /**
     * @var RequestValidatorInterface
     */
    protected RequestValidatorInterface $requestValidator;

    /**
     * @var AuthCodeManager
     */
    protected AuthCodeManager $authCodeManager;

    /**
     * @var AuthenticationSuccessHandlerInterface
     */
    protected AuthenticationSuccessHandlerInterface $successHandler;

    /**
     * UsernameOrEmailAuthenticator constructor.
     *
     * @param RequestDeserializerInterface          $requestDeserializer
     * @param RequestValidatorInterface             $requestValidator
     * @param AuthCodeManager                       $authCodeManager
     * @param AuthenticationSuccessHandlerInterface $successHandler
     */
    public function __construct(
        RequestDeserializerInterface $requestDeserializer,
        RequestValidatorInterface $requestValidator,
        AuthCodeManager $authCodeManager,
        AuthenticationSuccessHandlerInterface $successHandler
    )
    {
        $this->requestDeserializer = $requestDeserializer;
        $this->requestValidator    = $requestValidator;
        $this->authCodeManager     = $authCodeManager;
        $this->successHandler      = $successHandler;
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @return Response|void
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return true;
    }

    /**
     * @param Request $request
     *
     * @return LoginRequest
     * @throws InvalidRequestBodyException
     * @throws RequestValidationException
     * @throws BadRequestException
     */
    public function getCredentials(Request $request)
    {
        /** @var LoginRequest $result */
        $result = $this->requestDeserializer->deserializeRequest($request, LoginConfirmRequest::class);
        $this->requestValidator->validateRequest($result);

        return $result;
    }

    /**
     * @param LoginConfirmRequest   $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return UserInterface|null
     * @throws UsernameNotFoundException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials->getUsername());
    }

    /**
     * @param LoginConfirmRequest $credentials
     * @param UserInterface       $user
     *
     * @return bool
     *
     * @throws AuthenticationException
     * @throws NonUniqueResultException
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        try {
            /** @var User $user */
            $this->authCodeManager->get($user, $credentials->getAuthCode());
        } catch (EntityNotFoundException $e) {
            throw new AuthenticationException('Invalid auth code');
        }

        return true;
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     * @throws UnauthorizedHttpException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new UnauthorizedHttpException('Invalid username or authorization code');
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        $response = $this->successHandler->onAuthenticationSuccess($request, $token);

        /** @var User $user */
        $user = $token->getUser();
        $this->authCodeManager->deleteByUser($user);

        return $response;
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}