<?php

namespace StackoverflowApiClient\Request\Decorator;

use Psr\Http\Message\RequestInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
interface DecoratorInterface
{
    /**
     * @param RequestInterface $request
     * @param array $response
     * @return RequestInterface
     */
    public function decorate(RequestInterface $request, array $response = []);
}
