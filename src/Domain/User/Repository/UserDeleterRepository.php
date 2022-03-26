<?php

namespace App\Domain\User\Repository;

use PDO;

/**
 * Repository.
 */
class UserDeleterRepository
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
     * Suppresion d'un usager.
     *
     * @param array $usager Donnée de l'usager
     *
     * @return array Un array contenant l'id de l'usager ou vide
     */
    public function DeleteUser(array $usager): array
    {
        try {
            $resultat = [];
            (int)$usager['id'];
            $ligne = [];
            foreach ($usager as $cle => $valeur) {
                $ligne[$cle] = $valeur;
            }

            $sql = "DELETE FROM users
            WHERE id=:id;";

            $this->connection->prepare($sql)->execute($ligne);
            return [ "userId" => $usager['id'] ];
            
        } catch (\Throwable $th) {
            return [];
        }
    }
}

