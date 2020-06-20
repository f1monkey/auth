<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\AuthCode\AuthCodeManagerInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AuthCodeClearCommand
 *
 * @package App\Command
 */
class AuthCodeClearCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected const OPT_DATE = 'from';

    /**
     * @var string
     */
    protected static $defaultName = 'auth-code:clear';

    /**
     * @var AuthCodeManagerInterface
     */
    protected AuthCodeManagerInterface $authCodeManager;

    /**
     * @var DateTimeInterface
     */
    protected DateTimeInterface $from;

    /**
     * AuthCodeClearCommand constructor.
     *
     * @param AuthCodeManagerInterface $authCodeManager
     */
    public function __construct(AuthCodeManagerInterface $authCodeManager)
    {
        parent::__construct();
        $this->authCodeManager = $authCodeManager;
    }

    protected function configure()
    {
        $this->setDescription('Delete outdated authentication codes')
             ->addOption(
                 static::OPT_DATE,
                 'd',
                 InputOption::VALUE_OPTIONAL,
                 sprintf('Date from (i.e. %s)', (new \DateTime())->format(DATE_ATOM))
             );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info(
            'Deleting outdated auth codes...',
            [
                'from' => $this->from->format(DATE_ATOM),
            ]
        );

        $this->authCodeManager->deleteOutdated($this->from);

        $this->logger->info(
            'Deleted outdated auth codes',
            [
                'from' => $this->from->format(DATE_ATOM),
            ]
        );

        return 0;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws RuntimeException
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $fromOpt = $input->getOption(static::OPT_DATE);
        if (!$fromOpt) {
            $this->from = new DateTimeImmutable();
        } else {
            $from = DateTimeImmutable::createFromFormat(DATE_ATOM, $fromOpt);
            if (!$from) {
                throw new RuntimeException(sprintf('Invalid input date %s', $fromOpt));
            }
            $this->from = $from;
        }
    }
}