<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserDeleterRepository;

/**
 * Service.
 */
final class UserDeleter
{
    /**
     * @var UserDeleterRepository
     */
    private $deleteRepository;

    /**
     * Le constructeur.
     *
     * @param UserDeleterRepository $deleteRepository The deleteRepository
     */
    public function __construct(
        UserDeleterRepository $deleteRepository)
    {
        $this->deleteRepository = $deleteRepository;
    }

    /**
     * Supprime un usager.
     *
     * @param array $data L'array de données
     *
     * @return array L'id de l'usager dans un array ou un array d'erreurs
     */
    public function deleteUser(array $data): array
    {
        // Validation d'entrées
        $resultat = $this->validateUserInput($data);

        if(! $resultat['validation-errors']){
            // Suppression d'usager
            $resultat = $this->deleteRepository->DeleteUser($data);

            // Validation des sorties
            $resultat =  $this->validateUserDeleteOutput($resultat);
        }
        return $resultat;
    }

    /**
     * Validation d'entrées.
     *
     * @param array $data Les données entrantes à valider
     *
     * @return array Un array contenant les erreurs ou $data
     */
    private function validateUserInput(array $data): array
    {
        $errors = [];
        $inputErrors = null;

        if($data['id'] == 0){
            $inputErrors['user/{id}'] = 'Aucun usager associé à cet identifiant';
            
        }
       
        $inputErrors ? $errors['validation-errors'] =  $inputErrors : null;

        return $errors;
    }
    
    /**
     * Validation de sorties.
     *
     * @param array $data Les données sortantes `à valider
     *
     * @return array Un array contenant les erreurs ou $data
     */
    private function validateUserDeleteOutput(array $data): array
    {
        $errors = [];
        $outputErrors = null;

        if (empty($data)) {
            $outputErrors['errorDescription'] = "Échec de la suppression de l'usager";
            $outputErrors['user/{id}'] = 'Aucun usager associé à cet identifiant';
            $errors['validation-errors'] =  $outputErrors;
        }
        return $outputErrors ? $errors : $data;
   }
}
