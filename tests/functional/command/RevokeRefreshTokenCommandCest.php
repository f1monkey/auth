<?php
declare(strict_types=1);

namespace App\Tests\functional\command;

use App\DataFixtures\UserFixtures;
use DateTime;
use FunctionalTester;
use Gesdinet\JWTRefreshTokenBundle\Command\ClearInvalidRefreshTokensCommand;

/**
 * Class RevokeRefreshTokenCommandCest
 *
 * @package App\Tests\functional\command
 */
class RevokeRefreshTokenCommandCest
{
    /**
     * @param FunctionalTester $I
     */
    public function canRevokeOutdatedRefreshTokens(FunctionalTester $I)
    {
        $I->loadFixtures(UserFixtures::class);
        $token = $I->createRefreshToken(UserFixtures::USER_1_USERNAME);
        $token->setValid(new DateTime('-1 minute'));
        $I->haveInRepository($token);

        $I->runSymfonyConsoleCommand(ClearInvalidRefreshTokensCommand::getDefaultName());
        $I->dontSeeInDatabase('refresh_token', ['username' => UserFixtures::USER_1_USERNAME]);
    }
}