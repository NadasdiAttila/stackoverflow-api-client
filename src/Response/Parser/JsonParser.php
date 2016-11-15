<?php

namespace StackoverflowApiClient\Response\Parser;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
class JsonParser implements ParserInterface
{
    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function parse(ResponseInterface $response)
    {
        return \json_decode($response->getBody()->getContents(), true);
    }
}
