<?php

require './vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use StackoverflowApiClient\Request\Decorator\AnswerUriQuestionIdDecorator;
use StackoverflowApiClient\Request\Mediator as RequestMediator;
use StackoverflowApiClient\Response\ResponseAdapter;
use StackoverflowApiClient\Response\Visitor\AnswerOwnerUserIdVisitor;
use StackoverflowApiClient\Response\Parser\JsonParser;

$client = new Client([
    'base_uri' => 'https://api.stackexchange.com/2.2',
    'timeout'  => 2.0,
]);
$requestMediator = new RequestMediator();
$parser = new JsonParser();

$response = $requestMediator
    ->setClient($client)
    ->setParser($parser)
    ->addDecorator(new AnswerUriQuestionIdDecorator())
    ->addRequest(new Request('GET', 'questions/featured?order=desc&sort=activity&site=stackoverflow'))
    ->addRequest(new Request('GET', 'questions/%25question_id%25/answers?order=desc&sort=activity&site=stackoverflow'))
    ->send();

$responseAdapter = new ResponseAdapter($response, $parser);
$userIdVisitor = new AnswerOwnerUserIdVisitor();
$responseAdapter->accept($userIdVisitor);

echo 'User IDs: ', join(', ', $userIdVisitor->getVisited());
