<?php
declare(strict_types=1);

namespace App\Service\Security\Authenticator;

use App\Dto\Api\V1\Request\LoginRequest;
use App\Exception\Api\V1\InvalidJsonException;
use App\Exception\Api\V1\RequestValidationException;
use App\Exception\Api\V1\UnauthorizedHttpException;
use App\Service\Request\RequestDeserializerInterface;
use App\Service\Request\RequestValidatorInterface;
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
 * @package App\Security\Authenticator
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
     * UsernameOrEmailAuthenticator constructor.
     *
     * @param RequestDeserializerInterface $requestDeserializer
     * @param RequestValidatorInterface    $requestValidator
     */
    public function __construct(
        RequestDeserializerInterface $requestDeserializer,
        RequestValidatorInterface $requestValidator
    )
    {
        $this->requestDeserializer = $requestDeserializer;
        $this->requestValidator    = $requestValidator;
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
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
     *
     * @throws InvalidJsonException
     * @throws BadRequestException
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
        throw new UnauthorizedHttpException('No users found with such username or e-mail address');
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
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