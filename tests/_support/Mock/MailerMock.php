<?php
declare(strict_types=1);

namespace App\Tests\_support\Mock;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

/**
 * Class MailerMock
 *
 * @package App\Tests\_support\Mock
 */
class MailerMock implements MailerInterface
{
    /**
     * @var Collection|Email[]
     */
    protected Collection $emails;

    /**
     * MailerMock constructor.
     */
    public function __construct()
    {
        $this->emails = new ArrayCollection();
    }

    /**
     * @param RawMessage    $message
     * @param Envelope|null $envelope
     */
    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        $this->emails[] = $message;
    }

    /**
     * @return Collection|Email[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }
}