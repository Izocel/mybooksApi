<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserCreatorRepository;

/**
 * Service.
 */
final class UserCreator
{
    /**
     * @var UserCreatorRepository
     */
    private $repository;

    /**
     * Le constructeur.
     *
     * @param UserCreatorRepository $repository Le "respository"
     */
    public function __construct(UserCreatorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Crée un nouveau usager.
     *
     * @param array $data L'array de données
     *
     * @return array L'id de l'usager dans un array ou un array d'erreurs
     */
    public function createUser(array $data): array
    {
        // Validation d'entrées
        $resultat = $this->validateNewUserInput($data);

        if(! $resultat['validation-errors']){
            // Insertion de l'usager
            $resultat = $this->repository->insertUser($data);

            // Validation des sorties
            $resultat =  $this->validateUserSelectionOutput($resultat);
        }
        return $resultat;
    }

    /**
     * Validation d'entrées.
     *
     * @param array $data Les données de l'usager à l'entrée
     *
     * @return array Un array contenant les erreurs ou $data
     */
    private function validateNewUserInput(array $data): array
    {
        $errors = [];
        $inputErrors = null;

        if (empty($data['username'])) {
            $inputErrors['username'] = 'Le champ username unique est requis';
        }
        if (empty($data['first_name'])) {
            $inputErrors['first_name'] = 'Le champ first_name est requis';
        }
        if (empty($data['last_name'])) {
            $inputErrors['last_name'] = 'Le champ last_name est requis';
        }

        if (empty($data['email'])) {
            $inputErrors['email'] = 'Le champ email est requis';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $inputErrors['email'] = 'Le champ email doit être valide';
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
    private function validateUserSelectionOutput(array $data): array
    {
        $errors = [];
        $outputErrors = null;

        if (empty($data)) {
           $outputErrors['errorDescription'] = "Échec de l'insertion de l'usager";
           $outputErrors['username'] = "Le champ username n'est pas unique";
           $errors['validation-errors'] =  $outputErrors;
        }
        
        return $outputErrors ? $errors : $data;
   }
}
