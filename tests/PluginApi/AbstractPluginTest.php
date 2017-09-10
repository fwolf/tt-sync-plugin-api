<?php

namespace FwolfTest\Tools\TtSync\PluginApi;

use Fwolf\Tools\TtSync\PluginApi\AbstractPlugin;
use Fwolf\Tools\TtSync\PluginApi\Message;
use Fwolf\Tools\TtSync\PluginApi\MessageInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
class AbstractPluginTest extends PHPUnitTestCase
{
    /**
     * @param   string[] $methods
     * @return  MockObject | AbstractPlugin
     */
    protected function buildMock(array $methods = null)
    {
        $mock = $this->getMockBuilder(AbstractPlugin::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();

        return $mock;
    }


    public function testSort()
    {
        $messages = [
            (new Message())->setId(3),
            (new Message())->setId(1),
            (new Message())->setId(2),
        ];

        $closure = function (array $messages) {
            /** @noinspection PhpUndefinedMethodInspection */
            return $this->sortMessages($messages);
        };
        $mock = $this->buildMock([]);
        /** @var MessageInterface[] $messages */
        $messages = $closure->call($mock, $messages);

        $this->assertEquals(1, $messages[0]->getId());
        $this->assertEquals(2, $messages[1]->getId());
        $this->assertEquals(3, $messages[2]->getId());
    }
}
