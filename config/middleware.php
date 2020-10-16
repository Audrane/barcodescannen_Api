<?php

use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;


return function (App $app) {

    $app->add(\App\Middleware\CorsMiddleware::class);  
    
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

  


    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    $app->add(BasePathMiddleware::class); 

    // Catch exceptions and errors
    $app->add(ErrorMiddleware::class);

    /*
    $app->add(function (Request $request, Response $response, $next) {
        if($request->getMethod() !== 'OPTIONS') {
            return $next($request, $response);
        }
    
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Methods', $request->getHeaderLine('Access-Control-Request-Method'));
        $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeaderLine('Access-Control-Request-Headers'));
    
        return $next($request, $response);
    });
    */


};