<?php

namespace StackoverflowApiClient\Response\Parser;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
interface ParserInterface
{
    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function parse(ResponseInterface $response);
}
