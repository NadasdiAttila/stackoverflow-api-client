<?php

namespace StackoverflowApiClient\Request;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use StackoverflowApiClient\Request\Decorator\DecoratorInterface;
use StackoverflowApiClient\Response\Parser\ParserInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
class MediatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mediator
     */
    protected $mediator;

    protected function setUp()
    {
        $this->mediator = new Mediator();
    }

    /**
     * @covers \StackoverflowApiClient\Request\Mediator::setClient
     */
    public function testSetClient()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertSame($this->mediator, $this->mediator->setClient($client));
        $this->assertSame($client, $this->readAttribute($this->mediator, 'client'));
    }

    /**
     * @covers \StackoverflowApiClient\Request\Mediator::setParser
     */
    public function testSetParser()
    {
        $parser = $this->getMockBuilder(ParserInterface::class)
            ->getMock();
        $this->assertSame($this->mediator, $this->mediator->setParser($parser));
        $this->assertSame($parser, $this->readAttribute($this->mediator, 'parser'));
    }

    /**
     * @covers \StackoverflowApiClient\Request\Mediator::addDecorator
     */
    public function testAddDecorator()
    {
        $decorator = $this->getMockBuilder(DecoratorInterface::class)
            ->getMock();
        $this->assertSame($this->mediator, $this->mediator->addDecorator($decorator));
        $this->assertSame([$decorator], $this->readAttribute($this->mediator, 'decoratorQueue'));
    }

    /**
     * @covers \StackoverflowApiClient\Request\Mediator::addRequest
     */
    public function testAddRequest()
    {
        $request = $this->getMockBuilder(RequestInterface::class)
            ->getMock();
        $this->assertSame($this->mediator, $this->mediator->addRequest($request));
        $this->assertSame([$request], $this->readAttribute($this->mediator, 'requestQueue'));
    }

    /**
     * @param array $requestQueue
     * @param array $decoratorQueue
     * @param mixed $response
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedCount $sendInvokedCount
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedCount $parserInvokedCount
     * @dataProvider sendTestDataProvider
     */
    public function testSend(
        array $requestQueue,
        array $decoratorQueue,
        $response,
        \PHPUnit_Framework_MockObject_Matcher_InvokedCount $sendInvokedCount,
        \PHPUnit_Framework_MockObject_Matcher_InvokedCount $parserInvokedCount
    ) {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parser = $this->getMockBuilder(ParserInterface::class)->getMock();

        $client->expects($sendInvokedCount)
            ->method('send')
            ->withConsecutive($requestQueue)
            ->willReturn($response);
        $parser->expects($parserInvokedCount)
            ->method('parse')
            ->willReturn([]);

        $reflectionClass = new \ReflectionClass(Mediator::class);
        $mediatorRequestQueue = $reflectionClass->getProperty('requestQueue');
        $mediatorRequestQueue->setAccessible(true);
        $mediatorRequestQueue->setValue($this->mediator, $requestQueue);
        $mediatorDecoratorQueue = $reflectionClass->getProperty('decoratorQueue');
        $mediatorDecoratorQueue->setAccessible(true);
        $mediatorDecoratorQueue->setValue($this->mediator, $decoratorQueue);

        $this->assertSame(
            $response,
            $this->mediator
                ->setClient($client)
                ->setParser($parser)
                ->send()
        );
    }

    /**
     * @return array
     */
    public function sendTestDataProvider()
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $decorator = $this->getMockBuilder(DecoratorInterface::class)->getMock();
        $decorator->expects($this->once())
            ->method('decorate')
            ->willReturnMap([
                [$request, [], $request]
            ]);

        return [
            [[], [], null, $this->never(), $this->never()],
            [[$request], [], $response, $this->once(), $this->once()],
            [[], [$decorator], null, $this->never(), $this->never()],
            [[$request], [$decorator], $response, $this->once(), $this->once()]
        ];
    }
}
