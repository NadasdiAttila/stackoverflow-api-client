<?php

namespace StackoverflowApiClient\Response;

use Psr\Http\Message\ResponseInterface;
use StackoverflowApiClient\Response\Parser\ParserInterface;
use StackoverflowApiClient\Response\Visitor\VisitorInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.15.
 */
class ResponseAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResponseAdapter
     */
    protected $adapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $parser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $response;

    protected function setUp()
    {
        $this->response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->parser = $this->getMockBuilder(ParserInterface::class)->getMock();
        $this->adapter = new ResponseAdapter($this->response, $this->parser);
    }

    /**
     * @covers \StackoverflowApiClient\Response\ResponseAdapter::__construct
     */
    public function testConstruct()
    {
        $this->assertSame($this->response, $this->readAttribute($this->adapter, 'response'));
        $this->assertSame($this->parser, $this->readAttribute($this->adapter, 'parser'));
    }

    /**
     * @covers \StackoverflowApiClient\Response\ResponseAdapter::getParsedResponse
     */
    public function testGetParsedResponse()
    {
        $this->parser->expects($this->once())
            ->method('parse')
            ->with($this->response)
            ->willReturn('abc');

        $this->assertSame('abc', $this->adapter->getParsedResponse());
    }

    /**
     * @covers \StackoverflowApiClient\Response\ResponseAdapter::accept
     */
    public function testAccept()
    {
        $visitor = $this->getMockBuilder(VisitorInterface::class)->getMock();
        $visitor->expects($this->once())
            ->method('visit')
            ->with($this->adapter);

        $this->adapter->accept($visitor);
    }
}
