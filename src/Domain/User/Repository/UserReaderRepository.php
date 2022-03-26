<?php

namespace App\Domain\User\Repository;

use PDO;

use function PHPUnit\Framework\countOf;

/**
 * Repository.
 */
class UserReaderRepository
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
     * Read user row.
     *
     * @param int $usagerId L'id de l'usager
     *
     * @return array Un array clées : valeurs des données de l'usager
     */
    public function selectUser(int $usagerId): array
    {
        $cond = [
            $usagerId
        ];
        $sql = "SELECT * FROM users WHERE id= ?;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($cond);

        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultat ? $resultat: [];
    }


    /**
     * Read user row.
     *
     * @param object $requestBody Le body http
     *
     * @return array Un array clées : valeurs des données de l'usager
     */
    //FIXME: SQLINJECTIONS !!!
    //FIXME: SQLINJECTIONS !!!
    //FIXME: SQLINJECTIONS !!!
    public function selectUsers(object $requestBody): array
    {
        $cond = [];
        $sqlString = "";
        $this->parseQueryBody($requestBody, $cond, $sqlString);

        try {
            $stmt = $this->connection->prepare($sqlString);
            $stmt->execute($cond);
            $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $resultat ? $resultat: [];
    }

    /**
     * // Permet de parse un body json (queryBuilder) et définir un array de cond. et un string SQl
     * @param object &$jsonQueryBody IN Le body http pour filtrer le select
     * @param array &$condOutArr INOUT L'array associatif au valeurs pour le PDO
     * @param object &$sqlOutString INOUT Le query string SQL
     *
     * @return string &$sqlOutString;
     */
    private function parseQueryBody(object &$jsonQueryBody,array &$condOutArr,string &$sqlOutString) : string{
        $condOutArr = [];
        $sqlOutString = "SELECT ";
        $fields = $jsonQueryBody->fields ?? null;
        $match = $jsonQueryBody->match ?? null;
        $order = $jsonQueryBody->order ?? null;
        $limit = $jsonQueryBody->limit ?? 50;
        

        $fieldsString = "";
        foreach ($fields as $key => $value) {
            $fieldsString .= $value . ",";
        }
        if(strlen($fieldsString) == 0){
            $fieldsString = "*";
        }
        else {
            $n = strlen($fieldsString)-1;
            $fieldsString = mb_strcut($fieldsString, 0, $n);
        }
        $sqlOutString .= $fieldsString . " FROM users ";


        $matchString = "";
        foreach ($match as $key => $value) {
            $matchString .= $key . "= ? AND ";
            array_push($condOutArr, $value);
        }
        if(strlen($matchString) > 0){
            $n = strlen($matchString)-4;
            $matchString = mb_strcut($matchString, 0, $n);
            $sqlOutString.= " WHERE ";
        }
        $sqlOutString .= $matchString;


        $orderString = "";
        foreach ($order as $key => $value) {
            $orderString .= $key;
            $orderString .= $value == -1 ? " DESC," : " ASC,";
        }
        if(strlen($orderString) > 0){
            $n = strlen($orderString)-1;
            $orderString = mb_strcut($orderString, 0, $n);
            $sqlOutString .= "ORDER BY " . $orderString;
        }
        else {
            $sqlOutString .= "ORDER BY id ";
        }
        $sqlOutString .= " LIMIT {$limit};";

        return $sqlOutString;
    }
}

