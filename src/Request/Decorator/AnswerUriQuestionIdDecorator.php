<?php

namespace StackoverflowApiClient\Request\Decorator;

use Psr\Http\Message\RequestInterface;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
class AnswerUriQuestionIdDecorator implements DecoratorInterface
{
    /**
     * @param RequestInterface $request
     * @param array $response
     * @return RequestInterface
     */
    public function decorate(RequestInterface $request, array $response = [])
    {
        if (!$response) {
            return $request;
        }
        if (strpos($request->getUri()->getPath(), 'questions/%25question_id%25/answers') !== false
            && isset($response['items'][0]['question_id'])
        ) {
            return $this->setUriQuestionId($request, $response['items'][0]['question_id']);
        }
        return $request;
    }

    /**
     * @param RequestInterface $request
     * @param int $questionId
     * @return RequestInterface
     */
    protected function setUriQuestionId(RequestInterface $request, $questionId)
    {
        $uri = $request->getUri();

        return $request->withUri(
            $uri->withPath(str_replace('%25question_id%25', $questionId, $uri->getPath()))
        );
    }
}
