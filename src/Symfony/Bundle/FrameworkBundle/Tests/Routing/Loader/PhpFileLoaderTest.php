<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Tests\Routing\Loader;

use Symfony\Bundle\FrameworkBundle\Routing\Loader\PhpFileLoader;
use Symfony\Component\Config\Loader\LoaderInterface;

class PhpFileLoaderTest extends AbstractLoaderTest
{
    protected function getLoader(): LoaderInterface
    {
        return new PhpFileLoader($this->getLocator());
    }

    protected function getType(): string
    {
        return 'php';
    }
}
