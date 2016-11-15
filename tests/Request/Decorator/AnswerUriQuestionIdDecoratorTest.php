<?php

namespace StackoverflowApiClient\Request\Decorator;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
class AnswerUriQuestionIdDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AnswerUriQuestionIdDecorator
     */
    protected $decorator;

    protected function setUp()
    {
        $this->decorator = new AnswerUriQuestionIdDecorator();
    }

    /**
     * @covers \StackoverflowApiClient\Request\Decorator\AnswerUriQuestionIdDecorator::decorate
     * @covers \StackoverflowApiClient\Request\Decorator\AnswerUriQuestionIdDecorator::setUriQuestionId
     * @dataProvider decorateTestDataProvider
     * @param string $uriPath
     * @param array $response
     * @param string $decoratedUriPath
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedCount $requestGetUriInvokedCount
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedCount $requestWithUriInvokedCount
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedCount $uriGetPathInvokedCount
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedCount $uriWithPathInvokedCount
     */
    public function testDecorate(
        $uriPath,
        array $response,
        $decoratedUriPath,
        \PHPUnit_Framework_MockObject_Matcher_InvokedCount $requestGetUriInvokedCount,
        \PHPUnit_Framework_MockObject_Matcher_InvokedCount $requestWithUriInvokedCount,
        \PHPUnit_Framework_MockObject_Matcher_InvokedCount $uriGetPathInvokedCount,
        \PHPUnit_Framework_MockObject_Matcher_InvokedCount $uriWithPathInvokedCount
    ) {
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $uri = $this->getMockBuilder(Uri::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clonedRequest = clone $request;
        $clonedUri = clone $uri;

        $request->expects($requestGetUriInvokedCount)
            ->method('getUri')
            ->willReturn($uri);
        $request->expects($requestWithUriInvokedCount)
            ->method('withUri')
            ->willReturn($clonedRequest);

        $uri->expects($uriGetPathInvokedCount)
            ->method('getPath')
            ->willReturn($uriPath);
        $uri->expects($uriWithPathInvokedCount)
            ->method('withPath')
            ->with($decoratedUriPath)
            ->willReturn($clonedUri);

        $expected = $uriPath == $decoratedUriPath ? $request : $clonedRequest;

        $this->assertSame($expected, $this->decorator->decorate($request, $response));
    }

    /**
     * @return array
     */
    public function decorateTestDataProvider()
    {
        $response = [
            'items' => [
                ['question_id' => '123456789']
            ]
        ];
        return [
            [
                'questions/%25question_id%25/answers',
                $response,
                'questions/123456789/answers',
                $this->exactly(2),
                $this->once(),
                $this->exactly(2),
                $this->once()
            ],
            [
                'questions/%25question_id%25/answers',
                [],
                'questions/%25question_id%25/answers',
                $this->never(),
                $this->never(),
                $this->never(),
                $this->never()
            ],
            [
                'questions/featured',
                $response,
                'questions/featured',
                $this->once(),
                $this->never(),
                $this->once(),
                $this->never()
            ],
            [
                'questions/featured',
                [],
                'questions/featured',
                $this->never(),
                $this->never(),
                $this->never(),
                $this->never()
            ],
        ];
    }
}
