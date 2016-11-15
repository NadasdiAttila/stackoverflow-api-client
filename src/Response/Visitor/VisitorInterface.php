<?php

namespace StackoverflowApiClient\Response\Visitor;

use StackoverflowApiClient\Response\ResponseAdapter;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
interface VisitorInterface
{
    /**
     * @param ResponseAdapter $response
     */
    public function visit(ResponseAdapter $response);
}
