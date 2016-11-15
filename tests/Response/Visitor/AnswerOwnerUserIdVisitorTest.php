<?php

namespace StackoverflowApiClient\Response\Visitor;

use StackoverflowApiClient\Response\ResponseAdapter;

/**
 * @author Nádasdi Attila
 * @since 2016.11.15.
 */
class AnswerOwnerUserIdVisitorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AnswerOwnerUserIdVisitor
     */
    protected $visitor;

    protected function setUp()
    {
        $this->visitor = new AnswerOwnerUserIdVisitor();
    }

    /**
     * @covers \StackoverflowApiClient\Response\Visitor\AnswerOwnerUserIdVisitor::visit
     * @covers \StackoverflowApiClient\Response\Visitor\AnswerOwnerUserIdVisitor::getVisited
     * @dataProvider visitTestDataProvider
     * @param array $parsedResponse
     * @param array $visited
     */
    public function testVisit($parsedResponse, $visited)
    {
        $responseAdapter = $this->getMockBuilder(ResponseAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $responseAdapter->expects($this->once())
            ->method('getParsedResponse')
            ->willReturn($parsedResponse);

        $this->visitor->visit($responseAdapter);
        $this->assertSame($visited, $this->visitor->getVisited());
    }

    /**
     * @return array
     */
    public function visitTestDataProvider()
    {
        return [
            [[], []],
            [
                ['items' => [
                    ['owner' => ['user_id' => '123']],
                    ['owner' => ['user_id' => '456']]

                ]],
                ['123', '456']
            ]
        ];
    }
}
