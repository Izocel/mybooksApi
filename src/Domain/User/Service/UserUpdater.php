<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserUpdaterRepository;

/**
 * Service.
 */
final class UserUpdater
{
    /**
     * @var UserUpdaterRepository
     */
    private $updateRepository;

    /**
     * Le constructeur.
     *
     * @param UserUpdaterRepository $updateRepository The updateRepository
     */
    public function __construct(
        UserUpdaterRepository $updateRepository)
    {
        $this->updateRepository = $updateRepository;
    }

    /**
     * Update a usager.
     *
     * @param array $data L'array de données
     *
     * @return array L'id de l'usager dans array ou un array d'erreurs
     */
    public function updateUser(array $data): array
    {
        // Validation d'entrées
        $resultat = $this->validateUserInput($data);

        if(! $resultat['validation-errors']){
            // Mise à jour Usager
            $resultat = $this->updateRepository->UpdateUser($data);

            // Validation des sorties
            $resultat =  $this->validateUserUpdateOutput($resultat);
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

        if($data['email']){
            if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
                $inputErrors['email'] = 'Le champ email doit être valide';
            }
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
    private function validateUserUpdateOutput(array $data): array
    {
        $errors = [];
        $outputErrors = null;

        if (empty($data)) {
           $outputErrors['errorDescription'] = "Échec de la mise à jour de l'usager";
           $outputErrors['username'] = "Le champ username n'est pas unique";
           $errors['validation-errors'] =  $outputErrors;
        }
        return $outputErrors ? $errors : $data;
   }
}
