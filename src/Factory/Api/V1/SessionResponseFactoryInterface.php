<?php
declare(strict_types=1);

namespace App\Factory\Api\V1;

use App\Dto\Api\V1\Response\SessionListResponse;
use App\Dto\Api\V1\Response\SessionResponse;
use App\Entity\RefreshToken;
use Doctrine\Common\Collections\Collection;

/**
 * Class SessionResponseFactory
 *
 * @package App\Factory\Api\V1
 */
interface SessionResponseFactoryInterface
{
    /**
     * @param Collection<int, RefreshToken> $refreshTokens
     *
     * @return SessionListResponse
     */
    public function createSessionListResponse(Collection $refreshTokens): SessionListResponse;

    /**
     * @param RefreshToken $refreshToken
     *
     * @return SessionResponse
     */
    public function createSessionResponse(RefreshToken $refreshToken): SessionResponse;
}