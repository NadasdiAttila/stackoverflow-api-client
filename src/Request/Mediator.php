<?php

namespace StackoverflowApiClient\Request;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use StackoverflowApiClient\Request\Decorator\DecoratorInterface;
use StackoverflowApiClient\Response\Parser\ParserInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
class Mediator
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var RequestInterface[]
     */
    protected $requestQueue = [];

    /**
     * @var DecoratorInterface[]
     */
    protected $decoratorQueue = [];

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param ParserInterface $parser
     * @return $this
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
        return $this;
    }

    /**
     * @param DecoratorInterface $decorator
     * @return $this
     */
    public function addDecorator(DecoratorInterface $decorator)
    {
        $this->decoratorQueue[] = $decorator;
        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return $this
     */
    public function addRequest(RequestInterface $request)
    {
        $this->requestQueue[] = $request;
        return $this;
    }

    /**
     * @return mixed|null|\Psr\Http\Message\ResponseInterface
     */
    public function send()
    {
        $response = null;
        $parsedResponse = [];

        foreach ($this->requestQueue as $request) {
            foreach ($this->decoratorQueue as $decorator) {
                $request = $decorator->decorate($request, $parsedResponse);
            }
            $response = $this->client->send($request);
            $parsedResponse = $response ? $this->parser->parse($response) : [];
        }
        return $response;
    }
}
