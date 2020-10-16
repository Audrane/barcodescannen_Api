<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

return function (App $app) {

    $app->post('/signup', \App\Action\SignupAction::class)->setName('signup');
    $app->options('/login', \App\Action\LoginAction::class)->setName('login');

    $app->get('/', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $response->getBody()->write('Hello, World!');

        return $response;
    });
    

/*
    $app->post('/signup', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $body = $request->getParsedBody();
        $response->getBody()->write($body['username']);

        return $response;
    });
    */
};