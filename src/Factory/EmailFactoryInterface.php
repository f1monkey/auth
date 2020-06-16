<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\AuthCode;
use App\Entity\User;
use Symfony\Component\Mime\Email;

/**
 * Interface EmailFactoryInterface
 *
 * @package App\Factory
 */
interface EmailFactoryInterface
{
    /**
     * @param User     $user
     * @param AuthCode $authCode
     *
     * @return Email
     */
    public function createAuthCodeEmail(User $user, AuthCode $authCode): Email;
}