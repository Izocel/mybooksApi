<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use App\Domain\User\Service\UserUpdater;
use App\Factory\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

final class UserUpdateAction
{
    private $userUpdater;
    private $userReader;
    private $logger;

    public function __construct(
        UserUpdater $userUpdater, UserReader $userReader,
        LoggerFactory $loggerFactory)
    {
        $this->userUpdater = $userUpdater;
        $this->userReader = $userReader;
        $this-> logger = $loggerFactory
        ->addFileHandler('userLog.log')
        ->createLogger('updateUser');
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {
        // Collecte les données à partir de la requête HTTP
        $data = (array)$request->getParsedBody();
        $data['id'] = (int)$request->getAttribute('id') ?? 0;

        // Invoque le Domaine avec les données en entrée et retourne le résultat
        $updateResult = $this->userReader->selectUser($data['id']);
        

        // Si l'usager à été trouver -> Update
        if( ! $updateResult['notFound-errors'] )
            $updateResult = $this->userUpdater->updateUser($data);


        // Si l'usager à été mise à jour -> Select
        if( ! $updateResult['validation-errors'] )
            $updateResult = $this->userReader->selectUser($data['id']);


        // Construit la réponse Http selon des "codes" d'erreurs retournés précédemment
        return $this->respondWithFormat($updateResult, $response);
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

        $jsonData = json_encode($data);

        // Envoit les résultats dans le body de la réponse
        $response->getBody()->write((string)$jsonData);


        if($validationErrors){
            // Logging here: User creation failed
            //$this->logger->info(sprintf('User was not Updated: %s', $resultat));
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
        }

        if($notFoundErrors){
            // Logging here: User creation failed
            //$this->logger->info(sprintf('User was not Updated: %s', $resultat));
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404);
        }

        // Logging here: User Updated successfully
        $this->logger->info(
            sprintf("L'usager %s a été modifié: %s",
                $data['username'], $jsonData)
        );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
