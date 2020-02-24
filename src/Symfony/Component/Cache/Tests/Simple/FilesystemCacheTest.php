<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Cache\Tests\Simple;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * @group time-sensitive
 * @group legacy
 */
class FilesystemCacheTest extends CacheTestCase
{
    public function createSimpleCache(int $defaultLifetime = 0): CacheInterface
    {
        return new FilesystemCache('', $defaultLifetime);
    }

    protected function isPruned(CacheInterface $cache, string $name): bool
    {
        $getFileMethod = (new \ReflectionObject($cache))->getMethod('getFile');
        $getFileMethod->setAccessible(true);

        return !file_exists($getFileMethod->invoke($cache, $name));
    }
}
