<?php

namespace App\Domain\User\Repository;

use PDO;

/**
 * Repository.
 */
class UserUpdaterRepository
{
    /**
     * @var PDO La connection au LGBD
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection La connection au LGBD
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Mise à jour d'un usager.
     *
     * @param array $usager Donnée de l'usager
     *
     * @return array Un array contenant l'id de l'usager ou vide
     */
    public function UpdateUser(array $usager): array
    {
        try {
            $resultat = [];

            $ligne = [];
            foreach ($usager as $cle => $valeur) {
                $ligne[$cle] = $valeur;
            }

            $sql = "Update users SET 
            username=:username, 
            first_name=:first_name, 
            last_name=:last_name, 
            email=:email
            WHERE id=:id;";

            $this->connection->prepare($sql)->execute($ligne);
            return [ "userId" => $usager['id'] ];
            
        } catch (\Throwable $th) {

            
            return [];
        }
    }
}

