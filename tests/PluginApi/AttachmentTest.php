<?php

namespace FwolfTest\Tools\TtSync\PluginApi;

use Fwolf\Tools\TtSync\PluginApi\Attachment;
use org\bovigo\vfs\vfsStream;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @copyright   Copyright 2017 Fwolf
 * @license     https://opensource.org/licenses/MIT MIT
 */
class AttachmentTest extends PHPUnitTestCase
{
    /**
     * @param   string[] $methods
     * @return  MockObject | Attachment
     */
    protected function buildMock(array $methods = null)
    {
        $mock = $this->getMockBuilder(Attachment::class)
            ->setMethods($methods)
            ->getMock();

        return $mock;
    }


    public function testAutoDownloadTrigger()
    {
        $root = vfsStream::setup();
        $source = vfsStream::newFile('source')->at($root);
        file_put_contents($source->url(), 'blah');
        $tmp = vfsStream::newFile('temp')->at($root);

        /** @var MockObject | Attachment $mock */
        $mock = $this->getMockBuilder(Attachment::class)
            ->setMethods(['getTmpPath'])
            ->setConstructorArgs([$source->url()])
            ->getMock();
        $mock->method('getTmpPath')
            ->willReturn($tmp->url());

        // Fake local path as url, set url to reset downloaded status,
        // Test getPath()
        $mock->setUrl($source->url());
        $this->assertFalse($mock->isDownloaded());
        file_put_contents($tmp->url(), '');
        $this->assertEquals('vfs://root/temp', $mock->getPath());
        $this->assertTrue($mock->isDownloaded());
        $this->assertEquals('blah', file_get_contents($tmp->url()));


        // test getMineType()
        $mock->setUrl($source->url());
        $this->assertFalse($mock->isDownloaded());
        file_put_contents($tmp->url(), '');
        $this->assertEquals('text/plain', $mock->getMimeType());
        $this->assertTrue($mock->isDownloaded());
        $this->assertEquals('blah', file_get_contents($tmp->url()));


        // Test getSize()
        $mock->setUrl($source->url());
        $this->assertFalse($mock->isDownloaded());
        file_put_contents($tmp->url(), '');
        $this->assertEquals(4, $mock->getSize());
        $this->assertTrue($mock->isDownloaded());
        $this->assertEquals('blah', file_get_contents($tmp->url()));
    }


    public function testGetTmpPath()
    {
        $closure = function () {
            /** @noinspection PhpUndefinedMethodInspection */
            return $this->getTmpPath();
        };

        $attach = new Attachment('http://dummy.png');
        $tmpPath = $closure->call($attach);
        $this->assertRegExp('/\.png$/', $tmpPath);


        // Check 2 call with different tail for no ext name
        $attach = new Attachment('http://dummy');
        $tmpPath1 = $closure->call($attach);
        $tmpPath2 = $closure->call($attach);
        $this->assertNotEquals(
            substr($tmpPath1, -4),
            substr($tmpPath2, -4)
        );
    }


    public function testSaveAsRename()
    {
        $root = vfsStream::setup();

        $source = vfsStream::newFile('saved')->at($root);
        file_put_contents($source->url(), 'dummySaved');

        /** @var MockObject | Attachment $mock */
        $mock = $this->getMockBuilder(Attachment::class)
            ->setMethods(['getTmpPath'])
            ->setConstructorArgs([$source->url()])
            ->getMock();
        $mock->expects($this->never())
            ->method('getTmpPath');

        // Already downloaded, will do rename
        $dest = vfsStream::newFile('renamed')->at($root);
        $mock->save($dest->url());

        $this->assertFalse($root->hasChild('saved'));
        $this->assertTrue($root->hasChild('renamed'));
        $this->assertEquals('dummySaved', file_get_contents($dest->url()));
    }


    public function testSaveUrlToTmp()
    {
        $root = vfsStream::setup();

        // Use file to simulate url
        $source = vfsStream::newFile('url')->at($root);
        file_put_contents($source->url(), 'dummyUrlSource');

        /** @var MockObject | Attachment $mock */
        $mock = $this->getMockBuilder(Attachment::class)
            ->setMethods(['getTmpPath'])
            ->setConstructorArgs([$source->url()])
            ->getMock();

        $dest = vfsStream::newFile('downloaded')->at($root);
        $mock->method('getTmpPath')
            ->willReturn($dest->url());

        // Assign url manually, as constructor may not treat it as url
        $mock->setUrl($source->url())
            ->save();

        $this->assertEquals(
            'dummyUrlSource',
            file_get_contents($dest->url())
        );
    }


    public function testSaveWithDownloaded()
    {
        $root = vfsStream::setup();

        $source = vfsStream::newFile('saved')->at($root);
        file_put_contents($source->url(), 'dummySaved');

        /** @var MockObject | Attachment $mock */
        $mock = $this->getMockBuilder(Attachment::class)
            ->setMethods(['getTmpPath'])
            ->setConstructorArgs([$source->url()])
            ->getMock();
        $mock->expects($this->never())
            ->method('getTmpPath');

        // Already downloaded, this should do nothing
        $mock->save();

        $this->assertEquals('dummySaved', file_get_contents($source->url()));
    }


    public function testSetUrlOrPath()
    {
        $attach = new Attachment('http://dummy');

        $this->assertFalse($attach->isDownloaded());
        $this->assertEquals('http://dummy', $attach->getUrl());


        $root = vfsStream::setup();
        $path = vfsStream::newFile('path')->at($root);
        file_put_contents($path->url(), 'blah');
        $attach = new Attachment($path->url());

        $this->assertTrue($attach->isDownloaded());
        $this->assertEquals('vfs://root/path', $attach->getPath());
    }
}
