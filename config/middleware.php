<?php

use App\Middleware\LoggerMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $api) {
    // Ajout d'un middleware pour analyser: json, form data et xml
    $api->addBodyParsingMiddleware();

    $api->add(TwigMiddleware::class);

    // Ajoute la configuration d'un chemin de base
    $api->add(LoggerMiddleware::class);

    // Permettre les CORS
    $api->add(\App\Middleware\CorsMiddleware::class);

    // Ajoute le middleware natif de SLIM pour le routage
    $api->addRoutingMiddleware();

    // Ajoute la configuration d'un chemin de base
    $api->add(BasePathMiddleware::class);
    
    // Permet de récupérer les érreurs et exceptions
    // !!!! DOIT TOUJOURS ÊTRE LE DERNIER MIDDLEWARE !!!! \\
    $api->add(ErrorMiddleware::class);
};
