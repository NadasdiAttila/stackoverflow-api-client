<?php

namespace StackoverflowApiClient\Response\Parser;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.15.
 */
class JsonParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \StackoverflowApiClient\Response\Parser\JsonParser::parse
     */
    public function testParse()
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $stream = $this->getMockBuilder(StreamInterface::class)->getMock();

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn('{"items": [{"question_id": "12345678"}]}');

        $parser = new JsonParser();
        $this->assertEquals(['items' => [['question_id' => '12345678']]], $parser->parse($response));
    }
}
