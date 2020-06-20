<?php
declare(strict_types=1);

namespace App\Tests\functional\command;

use App\Command\AuthCodeClearCommand;
use DateTimeImmutable;
use FunctionalTester;

/**
 * Class AuthCodeClearCommandCest
 *
 * @package App\Tests\functional\command
 */
class AuthCodeClearCommandCest
{
    /**
     * @param FunctionalTester $I
     */
    public function canRevokeOutdatedRefreshTokens(FunctionalTester $I)
    {
        $user1 = $I->createUser('user1');
        $I->haveInRepository($user1);
        $authCode = $I->createAuthCode($user1);
        $authCode->setInvalidateAt(new DateTimeImmutable('-1 minute'));
        $I->haveInRepository($authCode);

        $user2 = $I->createUser('user2');
        $I->haveInRepository($user2);
        $authCode = $I->createAuthCode($user2);
        $I->haveInRepository($authCode);

        $I->runSymfonyConsoleCommand(AuthCodeClearCommand::getDefaultName());
        $I->dontSeeInDatabase('auth_code', ['parent_user_id' => $user1->getId()]);
        $I->seeInDatabase('auth_code', ['parent_user_id' => $user2->getId()]);
    }
}