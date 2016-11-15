<?php

namespace StackoverflowApiClient\Response\Visitor;

use StackoverflowApiClient\Response\ResponseAdapter;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
class AnswerOwnerUserIdVisitor implements VisitorInterface
{
    /**
     * @var array
     */
    protected $visited = [];

    /**
     * @return array
     */
    public function getVisited()
    {
        return $this->visited;
    }

    /**
     * @param $response
     */
    public function visit(ResponseAdapter $response)
    {
        $parsedResponse = $response->getParsedResponse();

        if (isset($parsedResponse['items'])) {
            foreach ($parsedResponse['items'] as $item) {
                if (isset($item['owner']['user_id'])) {
                    $this->visited[] = $item['owner']['user_id'];
                }
            }
        }
    }
}
