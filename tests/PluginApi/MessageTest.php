<?php

namespace FwolfTest\Tools\TtSync\PluginApi;

use Fwolf\Tools\TtSync\PluginApi\Attachment;
use Fwolf\Tools\TtSync\PluginApi\Message;
use org\bovigo\vfs\vfsStream;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
class MessageTest extends PHPUnitTestCase
{
    /**
     * @param   string[] $methods
     * @return  MockObject | Message
     */
    protected function buildMock(array $methods = null)
    {
        $mock = $this->getMockBuilder(Message::class)
            ->setMethods($methods)
            ->getMock();

        return $mock;
    }


    public function testDumpAndLoad()
    {
        $message = (new Message())
            ->setId('dummyId')
            ->setContent('dummyContent')
            ->setUpdateTime('2017-09-10T16:31:13+08:00');

        $root = vfsStream::setup();
        $sourceDir = vfsStream::newDirectory('source')->at($root);
        $attachment = vfsStream::newFile('attach.png')->at($sourceDir);
        $message->addAttachment(new Attachment($attachment->url()));
        $attachment = vfsStream::newFile('attach')->at($sourceDir);
        $message->addAttachment(new Attachment($attachment->url()));

        $storageDir = vfsStream::newDirectory('dump')->at($root);
        $message->dump($storageDir->url() . '/');

        $this->assertTrue($storageDir->hasChild('2017/dummyId.json'));
        $this->assertTrue($storageDir->hasChild('2017/dummyId-1.png'));
        $this->assertTrue($storageDir->hasChild('2017/dummyId-2'));


        // Load
        $message = (new Message())
            ->load($storageDir->getChild('2017/dummyId.json')->url());
        $this->assertEquals('dummyContent', $message->getContent());
        $this->assertEquals(
            'dummyId-1.png',
            basename($message->getAttachments()[0]->getPath())
        );
        $this->assertEquals(
            'dummyId-2',
            basename($message->getAttachments()[1]->getPath())
        );
    }


    public function testIsEarlierThan()
    {
        // Compare by update time
        $message1 = (new Message())->setId(1)
            ->setUpdateTime('2016-09-10T16:31:13+08:00');
        $message2 = (new Message())->setId(2)
            ->setUpdateTime('2017-09-10T16:31:13+08:00');
        $this->assertTrue($message1->isEarlierThan($message2));

        $message1->setUpdateTime('2017-09-10T16:31:13+08:00');
        $message2->setUpdateTime('2017-09-10T16:31:13+00:00');
        $this->assertTrue($message1->isEarlierThan($message2));

        // Compare by id
        $message1 = (new Message())->setId(1)
            ->setUpdateTime('2017-09-10T16:31:13+08:00');
        $message2 = (new Message())->setId(2)
            ->setUpdateTime('2017-09-10T16:31:13+08:00');
        $this->assertTrue($message1->isEarlierThan($message2));
    }
}
