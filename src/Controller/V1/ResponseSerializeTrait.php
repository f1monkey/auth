<?php
declare(strict_types=1);

namespace App\Controller\V1;

use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Trait ArrayTransformerAwareTrait
 *
 * @package App\Controller\V1
 */
trait ResponseSerializeTrait
{
    /**
     * @var ArrayTransformerInterface
     */
    protected ArrayTransformerInterface $arrayTransformer;

    /**
     * @required
     *
     * @param ArrayTransformerInterface $arrayTransformer
     */
    public function setArrayTransformer(ArrayTransformerInterface $arrayTransformer): void
    {
        $this->arrayTransformer = $arrayTransformer;
    }

    /**
     * @param object $response
     * @param int    $status
     *
     * @return JsonResponse
     */
    public function createJsonResponse(object $response, $status = JsonResponse::HTTP_OK): JsonResponse
    {
        return new JsonResponse(
            $this->arrayTransformer->toArray($response),
            $status
        );
    }
}