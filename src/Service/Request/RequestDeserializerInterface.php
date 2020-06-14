<?php
declare(strict_types=1);

namespace App\Service\Request;

use App\Exception\Api\V1\InvalidJsonException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestDeserializer
 *
 * @package App\Service\Request
 */
interface RequestDeserializerInterface
{
    /**
     * @param Request $request
     * @param string  $type
     *
     * @return object
     * @throws InvalidJsonException
     * @throws BadRequestException
     */
    public function deserializeRequest(Request $request, string $type): object;
}