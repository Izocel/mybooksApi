<?php

namespace App\Action;

use App\Domain\User\Service\UserCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

final class UserCreateAction
{
    private $userCreator;

    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {

        // Collecte les données à partir de la requête HTTP
        $data = (array)$request->getParsedBody();

        // Invoque le Domaine avec les données en entrée et retourne le résultat
        $resultat = $this->userCreator->createUser($data);

        // Construit la réponse HTTP
        return $this->respondWithFormat($resultat, $response);
    }



    /**
     * @param array $data Les données qui seront utilisées pour la réponse
     * 
     * @param Response $response l'objet réponse pour la requête en cours
     * 
     * @return Response $response La réponse fromaté selon l'algorithme
     */
    private function respondWithFormat(array $data, Response $response): Response {
        
        $errors = $data['errors'] ? true : false;
        $notFoundErrors = $data['notFound-errors'] ? true : false;
        $validationErrors = $data['validation-errors'] ? true : false;

        // Envoit les résultats dans le body de la réponse
        $response->getBody()->write((string)json_encode($data)); 


        if($validationErrors){
            // Logging here: User creation failed
            //$this->logger->info(sprintf('User was not created: %s', $resultat));
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
        }


        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $resultat));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
