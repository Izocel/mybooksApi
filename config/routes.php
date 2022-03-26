<?php

use App\Middleware\BasicAuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

return function (App $api) {

    //Test
    $api->get('/test',  \App\Action\HomeAction::class)->setName('Tests');

    // Documentation de l'api
    $api->get('/docs', \App\Action\Docs\SwaggerUiAction::class);
    $api->get('/', \App\Action\Docs\SwaggerUiAction::class)->setName('Docs');


    /**
     * GET	    Selection dun usager
     * POST	    Insertion d'un usager
     */
    $api->group('/user', function (RouteCollectorProxy $group) {
        $group->post('', \App\Action\UserCreateAction::class);
        $group->get('/{id:[0-9]+}', \App\Action\UserReadAction::class);
        $group->put('/{id:[0-9]+}', \App\Action\UserUpdateAction::class);
        $group->delete('/{id:[0-9]+}', \App\Action\UserDeleteAction::class);
    });

    /**
     * GET	    Selection de plusieurs usagers
     */
    $api->group('/users', function (RouteCollectorProxy $group) {
        $group->get('', \App\Action\UserReadAction::class);
    });

    /**
     * GET	    Selection de plusieurs livre
     */
    $api->group('/books', function (RouteCollectorProxy $group) {
        $group->get('', \App\Action\UserReadAction::class);
    });


};

