<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\AuthCode;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

/**
 * Class EmailFactory
 *
 * @package App\Factory
 */
class EmailFactory implements EmailFactoryInterface
{
    /**
     * @var string
     */
    protected string $mailFrom;

    /**
     * EmailFactory constructor.
     *
     * @param string $mailFrom
     */
    public function __construct(string $mailFrom)
    {
        $this->mailFrom = $mailFrom;
    }

    /**
     * @param User     $user
     * @param AuthCode $authCode
     *
     * @return Email
     */
    public function createAuthCodeEmail(User $user, AuthCode $authCode): Email
    {
        $result = new TemplatedEmail();
        $result->from($this->mailFrom)
               ->to($user->getEmail())
               ->subject(
                   sprintf('F1Monkey: authentication code for %s', $user->getUsername())
               )
               ->htmlTemplate('emails/auth_code.html.twig')
               ->context(
                   [
                       'username' => $user->getUsername(),
                       'code'     => $authCode->getCode(),
                   ]
               );

        return $result;
    }
}