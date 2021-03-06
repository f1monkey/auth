<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\RefreshToken;
use App\Entity\User;
use App\Exception\Entity\EntityNotFoundException;
use Doctrine\Common\Collections\Collection;

/**
 * Interface UserSessionManagerInterface
 *
 * @package App\Service\User
 */
interface UserSessionManagerInterface
{
    /**
     * @param User $user
     *
     * @return Collection<int, RefreshToken>
     */
    public function getByUser(User $user): Collection;

    /**
     * @param User   $user
     * @param string $id
     *
     * @return RefreshToken
     * @throws EntityNotFoundException
     */
    public function getById(User $user, string $id): RefreshToken;

    /**
     * @param string $token
     *
     * @return RefreshToken
     * @throws EntityNotFoundException
     */
    public function getByTokenValue(string $token): RefreshToken;

    /**
     * @param RefreshToken $refreshToken
     */
    public function save(RefreshToken $refreshToken): void;

    /**
     * @param RefreshToken $refreshToken
     */
    public function delete(RefreshToken $refreshToken): void;
}