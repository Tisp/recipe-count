<?php

use PHPUnit\Framework\TestCase;
use RecipeCalculator\FileReader;
use RecipeCalculator\JsonParser;

class JsonParserTest extends TestCase
{
    protected $fileReaderMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fileReaderMock = $this->getMockBuilder(FileReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fileReaderMock->expects($this->exactly(1))
            ->method('read')
            ->will($this->returnCallback(function () {
                yield '[{"foo": "bar"}]';
            }));
    }

    public function testParseJsonDefaultOutput()
    {
        $jsonParser = new JsonParser($this->fileReaderMock);

        foreach ($jsonParser as $jsonBuffer) {
            foreach ($jsonBuffer as $json) {
                $this->assertEquals(["foo" => "bar"], $json);
            }
        }
    }
}