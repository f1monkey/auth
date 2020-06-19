<?php
declare(strict_types=1);

namespace App\Factory\Api\V1;

use App\Dto\Api\V1\Response\SessionListResponse;
use App\Dto\Api\V1\Response\SessionResponse;
use App\Dto\Api\V1\Response\SessionUserData;
use App\Entity\RefreshToken;
use Doctrine\Common\Collections\Collection;
use WhichBrowser\Parser;

/**
 * Class SessionResponseFactory
 *
 * @package App\Factory\Api\V1
 */
class SessionResponseFactory implements SessionResponseFactoryInterface
{
    /**
     * @param Collection|RefreshToken[] $refreshTokens
     *
     * @return SessionListResponse
     */
    public function createSessionListResponse(Collection $refreshTokens): SessionListResponse
    {
        $response = new SessionListResponse();
        foreach ($refreshTokens as $refreshToken) {
            $response->getItems()->add($this->createSessionResponse($refreshToken));
        }

        return $response;
    }

    /**
     * @param RefreshToken $refreshToken
     *
     * @return SessionResponse
     */
    public function createSessionResponse(RefreshToken $refreshToken): SessionResponse
    {
        $result = new SessionResponse();
        $result->setId($refreshToken->getId())
               ->setCreatedAt($refreshToken->getCreatedAt())
               ->setUserData($this->createUserData($refreshToken));

        return $result;
    }

    /**
     * @param RefreshToken $refreshToken
     *
     * @return SessionUserData
     */
    protected function createUserData(RefreshToken $refreshToken): SessionUserData
    {
        $whichBrowser=  new Parser($refreshToken->getUserAgent());

        $result = new SessionUserData();
        $result->setIp($refreshToken->getIp())
               ->setBrowser($whichBrowser->engine->toString())
               ->setPlatform($whichBrowser->os->getName());

        return $result;
    }
}