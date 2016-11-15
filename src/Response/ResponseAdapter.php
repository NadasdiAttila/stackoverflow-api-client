<?php

namespace StackoverflowApiClient\Response;

use Psr\Http\Message\ResponseInterface;
use StackoverflowApiClient\Response\Parser\ParserInterface;
use StackoverflowApiClient\Response\Visitor\VisitableInterface;
use StackoverflowApiClient\Response\Visitor\VisitorInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
class ResponseAdapter implements VisitableInterface
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * ResponseAdapter constructor.
     * @param ResponseInterface $response
     * @param ParserInterface $parser
     */
    public function __construct(ResponseInterface $response, ParserInterface $parser)
    {
        $this->response = $response;
        $this->parser = $parser;
    }

    /**
     * @return mixed
     */
    public function getParsedResponse()
    {
        return $this->parser->parse($this->response);
    }

    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visit($this);
    }
}
