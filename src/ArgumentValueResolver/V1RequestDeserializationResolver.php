<?php
declare(strict_types=1);

namespace App\ArgumentValueResolver;

use App\Dto\Api\V1\Request\V1RequestInterface;
use Generator;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\Exception\RuntimeException as JMSRuntimeException;
use JMS\Serializer\SerializerInterface;
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
     * @var ArrayTransformerInterface
     */
    protected ArrayTransformerInterface $arrayTransformer;

    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * V1RequestDeserializationResolver constructor.
     *
     * @param ArrayTransformerInterface $arrayTransformer
     * @param SerializerInterface       $serializer
     */
    public function __construct(ArrayTransformerInterface $arrayTransformer, SerializerInterface $serializer)
    {
        $this->arrayTransformer = $arrayTransformer;
        $this->serializer = $serializer;
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
     * @throws BadRequestException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        try {
            if ($request->isMethodSafe()) {
                $result = $this->arrayTransformer->fromArray($request->query->all(), $argument->getType());
            } else {
                $result = $this->serializer->deserialize(
                    $request->getContent(),
                    $argument->getType(),
                    'json'
                );
            }

            yield $result;
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (JMSRuntimeException $e) {
            throw new BadRequestException($e->getMessage());
        }
    }
}