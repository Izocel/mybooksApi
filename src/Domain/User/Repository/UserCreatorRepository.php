<?php

namespace App\Domain\User\Repository;

use PDO;

/**
 * Repository.
 */
class UserCreatorRepository
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
     * Insertion de l'usager row.
     *
     * @param array $usager The user
     *
     * @return array The nouveau ID in an array or an empty array
     */
    public function insertUser(array $usager): array
    {
        $resultat = [];
        $ligne = [
            'username' => $usager['username'],
            'first_name' => $usager['first_name'],
            'last_name' => $usager['last_name'],
            'email' => $usager['email'],
        ];

        $sql = "INSERT INTO users SET 
                username=:username, 
                first_name=:first_name, 
                last_name=:last_name, 
                email=:email;";

        try {
            //code...
            $this->connection->prepare($sql)->execute($ligne);
        } catch (\Throwable $th) {
            return [];
        }
        
        return ["userId" => (int)$this->connection->lastInsertId()];    
    }
}

