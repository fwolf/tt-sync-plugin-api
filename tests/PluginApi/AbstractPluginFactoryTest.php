<?php

namespace FwolfTest\Tools\TtSync\PluginApi;

use Fwolf\Tools\TtSync\PluginApi\AbstractPlugin;
use Fwolf\Tools\TtSync\PluginApi\AbstractPluginFactory;
use Fwolf\Tools\TtSync\PluginApi\MessageInterface;
use Fwolf\Tools\TtSync\PluginApi\PluginFactoryInterface;
use Fwolf\Tools\TtSync\PluginApi\PluginInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
class AbstractPluginFactoryTest extends PHPUnitTestCase
{
    /**
     * @param   string[] $methods
     * @return  MockObject | AbstractPluginFactory
     */
    protected function buildMock(array $methods = null)
    {
        $mock = $this->getMockBuilder(AbstractPluginFactory::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();

        return $mock;
    }


    /**
     * @return  PluginInterface
     */
    protected function createPluginClass()
    {
        $class = new class extends AbstractPlugin
        {
            /**
             * @inheritdoc
             */
            public function getAllMessages(): array
            {
                return [];
            }


            /**
             * @inheritdoc
             */
            public function getRecentMessages(
                int $count = self::RECENT_COUNT
            ): array {
                false && $count;

                return [];
            }


            /**
             * @inheritdoc
             */
            public function postMessage(
                MessageInterface $message
            ): PluginInterface {
                return $this;
            }


            /**
             * @inheritdoc
             */
            public function postMessages(array $messages): PluginInterface
            {
                return $this;
            }


            /**
             * @inheritdoc
             */
            public function validate(string $profile): bool
            {
                return true;
            }

        };

        return $class;
    }


    /**
     * @return  PluginFactoryInterface
     */
    protected function createPluginFactoryClass()
    {
        $class = new class extends AbstractPluginFactory
        {
        };

        return $class;
    }


    public function testCreatePlugin()
    {
        $pluginClass = $this->createPluginClass();

        $mock = $this->buildMock(['getPluginClass']);
        $mock->expects($this->once())
            ->method('getPluginClass')
            ->willReturn(get_class($pluginClass));

        $instance = $mock->createPlugin();
        $this->assertInstanceOf(PluginInterface::class, $instance);
    }


    public function testGetInstance()
    {
        $factoryClass = $this->createPluginFactoryClass();

        $factory = $factoryClass::getInstance();

        $this->assertInstanceOf(PluginFactoryInterface::class, $factory);
    }


    public function testGetPluginClass()
    {
        $mock = $this->buildMock();

        $this->assertEmpty($mock->getPluginClass());
    }
}
