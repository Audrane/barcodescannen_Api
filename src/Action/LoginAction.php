<?php

namespace App\Action;

use App\Domain\User\Service\UserCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LoginAction
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
       // $body = $request->getBoy();
        $data = (array)$request->getParsedBody();

        if($data['firmen_name'] === null || empty($data['firmen_name'])|| $data['passwort'] === null || empty( $data['passwort']))
        {
            $result = [
                'firmenname' => $data['firmen_name'],
                'message' => 'Firmen name or passwort is null',
                'token' => null,
                'password' => $data['passwort']
            ];
        }
        else
      { 
          $result = $this->userCreator->loginUser($data);

       
        $result = [
            'firmenname' => $data['firmen_name'],
            'message' => $result['succeed'] ?  'login succeed' : 'username or password not correct',
            'token' =>  $result['token']
        ];
    }

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));
       
        $response = $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:8100');
        $response = $response->withHeader('Access-Control-Allow-Methods', $request->getHeaderLine('Access-Control-Request-Method'));
        $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeaderLine('Access-Control-Request-Headers'));
    $response =    $response->withHeader('Content-Type', 'application/json');
    $response =    $response->withStatus(200);
        

        return $response;

    }
}