<?php

use PHPUnit\Framework\TestCase;
use RecipeCalculator\FileReader;

class FileReaderTest extends TestCase
{
    public function testFileReaderCanRead()
    {
        $mock = $this->getMockBuilder(SplFileObject::class)
            ->setConstructorArgs(['php://memory'])
            ->getMock();

        $mock->expects($this->exactly(2))
            ->method('eof')
            ->will($this->onConsecutiveCalls(false, true));

        $mock->expects($this->any())
            ->method('fread')
            ->will($this->onConsecutiveCalls('foo'));

        $fileReader = new FileReader($mock);
        foreach ($fileReader->read() as $data) {
            $this->assertEquals('foo', $data);
        }
    }
}