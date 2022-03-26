<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserReaderRepository;
use App\Exception\ValidationException;

use function PHPUnit\Framework\isEmpty;

/**
 * Service.
 */
final class UserReader
{
    /**
     * @var UserReaderRepository
     */
    private $repository;

    /**
     * Le constructeur.
     *
     * @param UserReaderRepository $repository Le "respository"
     */
    public function __construct(UserReaderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a user
     *
     * @param int $usagerId L'id de l'usager
     *
     * @return array Les données sortantes de l'usager
     */
    public function selectUser(int $usagerId): array
    {
        // Selection de l'usager
        $resultat = $this->repository->selectUser($usagerId);

        // Validation des sorties
        $resultat = $this->validateUserSelection($resultat);

        // Logging here: User selected successfully
        //$this->logger->info(sprintf('User selected successfully: %s', $usagerId));

        return $resultat;
    }


    /**
     * Lecture d'usageers avec body en entré
     *
     * @param onbject $requestBody Le body de le requete
     *
     * @return array Les données sortantes de l'usager
     */
    public function selectUsers(object $requestBody): array
    {
        // Selection de l'usager
        $resultat = $this->repository->selectUsers($requestBody);

        // Validation des sorties
        $resultat = $this->validateUsersSelection($resultat);
        return $resultat;
    }


    /**
     * Validation d'entrées.
     *
     * @param array $data L'array de données
     *
     * @return array
     */
    private function validateUserSelection(array $data): array
    {
        
        $errors = [];
        $rqstErrors = null;

        if (empty($data) ) {
           $rqstErrors['errorDescription'] = "Échec de la selection de l'usager";
           $rqstErrors['user/{id}'] = 'Aucun usager associé à cet identifiant';
           $errors['notFound-errors'] =  $rqstErrors;
        }
        // throw new ValidationException('Please check your input', $errors);
        
        return $rqstErrors ? $errors : $data;
   }


   /**
     * Validation d'entrées.
     *
     * @param array $data L'array de données
     *
     * @return array
     */
    private function validateUsersSelection(array $data): array
    {
        
        $errors = [];
        $rqstErrors = null;

        if (empty($data) ) {
           $rqstErrors['errorDescription'] = "Échec de la selection d'usagers";
           $rqstErrors['body'] = 'Aucun usager trouvé selon ces critères';
           $errors['notFound-errors'] =  $rqstErrors;
        }
        // throw new ValidationException('Please check your input', $errors);
        
        return $rqstErrors ? $errors : $data;
   }
}


