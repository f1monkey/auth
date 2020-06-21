<?php
declare(strict_types=1);

namespace App\Service\Security;

use App\Dto\Api\V1\Request\LoginRequest;
use App\Entity\User;
use App\Exception\Api\V1\GenericForbiddenHttpException;
use App\Exception\Api\V1\UnauthorizedHttpException;
use App\Exception\AuthCode\TooManyAuthCodesException;
use App\Service\AuthCode\AuthCodeManager;
use F1Monkey\RequestHandleBundle\Exception\InvalidRequestBodyException;
use F1Monkey\RequestHandleBundle\Exception\Validation\RequestValidationException;
use F1Monkey\RequestHandleBundle\Service\RequestDeserializerInterface;
use F1Monkey\RequestHandleBundle\Service\RequestValidatorInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class UsernameOrEmailAuthenticator
 *
 * @package App\Service\Security
 */
class UsernameOrEmailAuthenticator extends AbstractGuardAuthenticator
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
     * UsernameOrEmailAuthenticator constructor.
     *
     * @param RequestDeserializerInterface $requestDeserializer
     * @param RequestValidatorInterface    $requestValidator
     * @param AuthCodeManager              $authCodeManager
     */
    public function __construct(
        RequestDeserializerInterface $requestDeserializer,
        RequestValidatorInterface $requestValidator,
        AuthCodeManager $authCodeManager
    )
    {
        $this->requestDeserializer = $requestDeserializer;
        $this->requestValidator    = $requestValidator;
        $this->authCodeManager     = $authCodeManager;
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @return Response|null
     */
    public function start(Request $request, AuthenticationException $authException = null): ?Response
    {
        return null;
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
     *
     * @throws BadRequestException
     * @throws InvalidRequestBodyException
     * @throws RequestValidationException
     */
    public function getCredentials(Request $request): LoginRequest
    {
        /** @var LoginRequest $result */
        $result = $this->requestDeserializer->deserializeRequest($request, LoginRequest::class);
        $this->requestValidator->validateRequest($result);

        return $result;
    }

    /**
     * @param LoginRequest          $credentials
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
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse
     * @throws UnauthorizedHttpException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        throw new UnauthorizedHttpException('No users found with such username or e-mail address.');
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return Response|null
     * @throws GenericForbiddenHttpException
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        /** @var User $user */
        $user = $token->getUser();

        try {
            $this->authCodeManager->createForUser($user);
        } catch (TooManyAuthCodesException $e) {
            throw new GenericForbiddenHttpException('user.auth.too_many_codes');
        }

        return null;
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}