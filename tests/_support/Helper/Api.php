<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Tests\_support\Helper\RepositoryTrait;
use App\Tests\_support\Helper\UserCreateTrait;

class Api extends \Codeception\Module
{
    use RepositoryTrait;
    use UserCreateTrait;
}
