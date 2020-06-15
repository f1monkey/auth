<?php
declare(strict_types=1);

namespace App\Tests\_support\Helper;

use Codeception\Module\Symfony;

/**
 * Trait ParameterGrabberTrait
 *
 * @package App\Tests\_support\Helper
 */
trait ParameterGrabberTrait
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function grabParameter(string $name)
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');

        return $symfony->_getContainer()->getParameter($name);
    }
}
