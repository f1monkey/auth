<?php
declare(strict_types=1);

namespace App\EventListener\AuthCode;

use App\Event\AuthCode\AuthCodeCreateAfterEvent;
use App\Factory\EmailFactoryInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Class AuthCodeCreateListener
 *
 * @package App\EventListener\AuthCode
 */
class AuthCodeCreateListener
{
    /**
     * @var EmailFactoryInterface
     */
    protected EmailFactoryInterface $emailFactory;

    /**
     * @var MailerInterface
     */
    protected MailerInterface $mailer;

    /**
     * AuthCodeCreateListener constructor.
     *
     * @param EmailFactoryInterface $emailFactory
     * @param MailerInterface       $mailer
     */
    public function __construct(EmailFactoryInterface $emailFactory, MailerInterface $mailer)
    {
        $this->emailFactory = $emailFactory;
        $this->mailer       = $mailer;
    }

    /**
     * @param AuthCodeCreateAfterEvent $event
     *
     * @throws TransportExceptionInterface
     */
    public function onAuthCodeCreate(AuthCodeCreateAfterEvent $event)
    {
        $this->mailer->send(
            $this->emailFactory->createAuthCodeEmail(
                $event->getUser(),
                $event->getCode()
            )
        );
    }
}