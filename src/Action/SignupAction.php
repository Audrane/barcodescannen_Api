<?php

namespace App\Action;

use App\Domain\User\Service\UserCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SignupAction
{

    private $userCreator;

    public function __construct(UserCreator $_userCreator)
    {
        $this->userCreator = $_userCreator;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {

        $data = (array)$request->getParsedBody();

       
       $token = $this->userCreator->createUser($data);
        $result = [
            'firmenname' => $data['firmen_name'],
            'token' =>  $token
        ];


        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            
            ->withStatus(200);

        return $response;

    }
}