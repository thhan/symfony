<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Messenger\Tests\Transport\Sender;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Tests\Fixtures\DummyMessage;
use Symfony\Component\Messenger\Tests\Fixtures\SecondMessage;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;

class SendersLocatorTest extends TestCase
{
    public function testItReturnsTheSenderBasedOnTheMessageClass()
    {
        $sender = $this->getMockBuilder(SenderInterface::class)->getMock();
        $sendersLocator = $this->createContainer([
            'my_sender' => $sender,
        ]);
        $locator = new SendersLocator([
            DummyMessage::class => ['my_sender'],
        ], $sendersLocator);

        $this->assertSame(['my_sender' => $sender], iterator_to_array($locator->getSenders(new Envelope(new DummyMessage('a')))));
        $this->assertSame([], iterator_to_array($locator->getSenders(new Envelope(new SecondMessage()))));
    }

    /**
     * @group legacy
     */
    public function testItReturnsTheSenderBasedOnTheMessageClassLegacy()
    {
        $sender = $this->getMockBuilder(SenderInterface::class)->getMock();
        $locator = new SendersLocator([
            DummyMessage::class => [$sender],
        ]);
        $this->assertSame([$sender], iterator_to_array($locator->getSenders(new Envelope(new DummyMessage('a')))));
        $this->assertSame([], iterator_to_array($locator->getSenders(new Envelope(new SecondMessage()))));
    }

    /**
     * @group legacy
     */
    public function testItYieldsProvidedSenderAliasAsKeyLegacy()
    {
        $sender = $this->getMockBuilder(SenderInterface::class)->getMock();
        $locator = new SendersLocator([
            DummyMessage::class => ['dummy' => $sender],
        ]);
        $this->assertSame(['dummy' => $sender], iterator_to_array($locator->getSenders(new Envelope(new DummyMessage('a')))));
    }

    private function createContainer(array $senders): ContainerInterface
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->any())
            ->method('has')
            ->willReturnCallback(function ($id) use ($senders) {
                return isset($senders[$id]);
            });
        $container->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($id) use ($senders) {
                return $senders[$id];
            });

        return $container;
    }
}
