<?php
declare(strict_types=1);

namespace App\Service\Request;

use App\Exception\Api\V1\InvalidJsonException;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\Exception\RuntimeException as JMSRuntimeException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestDeserializer
 *
 * @package App\Service\Request
 */
class RequestDeserializer implements RequestDeserializerInterface
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
     * RequestDeserializer constructor.
     *
     * @param ArrayTransformerInterface $arrayTransformer
     * @param SerializerInterface       $serializer
     */
    public function __construct(ArrayTransformerInterface $arrayTransformer, SerializerInterface $serializer)
    {
        $this->arrayTransformer = $arrayTransformer;
        $this->serializer       = $serializer;
    }

    /**
     * @param Request $request
     * @param string  $type
     *
     * @return object
     * @throws InvalidJsonException
     * @throws BadRequestException
     */
    public function deserializeRequest(Request $request, string $type): object
    {
        try {
            if ($request->isMethodSafe()) {
                $result = $this->arrayTransformer->fromArray($request->query->all(), $type);
            } else {
                $result = $this->serializer->deserialize(
                    $request->getContent(),
                    $type,
                    'json'
                );
            }

            return $result;
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (JMSRuntimeException $e) {
            throw new InvalidJsonException($e->getMessage());
        }
    }
}