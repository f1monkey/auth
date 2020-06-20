<?php

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * @param User $user
     */
    public function amJwtAuthorizedAs(User $user)
    {
        /** @var JWTTokenManagerInterface $manager */
        $manager = $this->grabService('test.app.jwt_manager');
        $token   = $manager->create($user);
        $this->haveHttpHeader('Authorization', sprintf('Bearer %s', $token));
    }
}
