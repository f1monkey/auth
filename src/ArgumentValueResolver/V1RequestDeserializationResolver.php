<?php
declare(strict_types=1);

namespace App\ArgumentValueResolver;

use App\Dto\Api\V1\Request\V1RequestInterface;
use App\Exception\Api\V1\InvalidJsonException;
use App\Service\Request\RequestDeserializerInterface;
use Generator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class V1RequestDeserializationResolver
 *
 * @package App\ArgumentValueResolver
 */
class V1RequestDeserializationResolver implements ArgumentValueResolverInterface
{
    /**
     * @var RequestDeserializerInterface
     */
    protected RequestDeserializerInterface $requestDeserializer;

    /**
     * V1RequestDeserializationResolver constructor.
     *
     * @param RequestDeserializerInterface $requestDeserializer
     */
    public function __construct(RequestDeserializerInterface $requestDeserializer)
    {
        $this->requestDeserializer = $requestDeserializer;
    }

    /**
     * Whether this resolver can resolve the value for the given ArgumentMetadata.
     *
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return is_a($argument->getType(), V1RequestInterface::class, true);
    }

    /**
     * Returns the possible value(s).
     *
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator|object[]
     * @throws InvalidJsonException
     * @throws BadRequestException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this->requestDeserializer->deserializeRequest($request, $argument->getType());
    }
}