<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Tests\_support\Helper\AuthCodeCreateTrait;
use App\Tests\_support\Helper\FixtureLoadTrait;
use App\Tests\_support\Helper\ParameterGrabberTrait;
use App\Tests\_support\Helper\RefreshTokenCreateTrait;
use App\Tests\_support\Helper\RepositoryTrait;
use App\Tests\_support\Helper\UserCreateTrait;

class Integration extends \Codeception\Module
{
    use RepositoryTrait;
    use UserCreateTrait;
    use FixtureLoadTrait;
    use RefreshTokenCreateTrait;
    use ParameterGrabberTrait;
    use AuthCodeCreateTrait;
}
