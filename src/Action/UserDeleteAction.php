<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use App\Domain\User\Service\UserDeleter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

final class UserDeleteAction
{
    private $userDeleter;
    private $userReader;

    public function __construct(
        UserDeleter $userDeleter,
        UserReader $userReader)
    {
        $this->userDeleter = $userDeleter;
        $this->userReader = $userReader;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {

        // Collecte les données à partir de la requête HTTP
        $data = (array)$request->getParsedBody();
        $data['id'] = (int)$request->getAttribute('id') ?? 00;

        // Invoque le Domaine avec les données en entrée et retourne le résultat
        $selectResult = $this->userReader->selectUser($data['id']);

        // Si l'usager à été trouver -> Delete
        if( ! $selectResult['notFound-errors'] )
            $deleteResult = $this->userDeleter->deleteUser($data);

        // Si l'usager à été supprimé -> Select
        if( ! $deleteResult['validation-errors'] )
            $deleteResult = $selectResult;

        // Construit la réponse Http selon des "codes" d'erreurs retournés précédemment
        return $this->respondWithFormat($deleteResult, $response);
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
            //$this->logger->info(sprintf('User was not Deleted: %s', $resultat));
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404);
        }

        if($notFoundErrors){
            // Logging here: User creation failed
            //$this->logger->info(sprintf('User was not Deleted: %s', $resultat));
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404);
        }


        // Logging here: User Deleted successfully
        //$this->logger->info(sprintf('User Deleted successfully: %s', $resultat));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
