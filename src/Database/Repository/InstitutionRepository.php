<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Repository;

use Exception;
use Hbp\Import\Database\Entity\Institution;
use Hbp\Import\Database\RepositoryInterface;
use PDO;

class InstitutionRepository implements RepositoryInterface
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByCuiBulk($cuis)
    {
        $cuis = array_map('intval', $cuis);

        $statement = $this->pdo->prepare("SELECT * from institution where reg_no in ('" . implode("', '", $cuis) . "')");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $return = [];

        foreach ($data as $institutionData) {
            $institution = new Institution();
            $institution->setId($institutionData['id']);
            $institution->setRegNo($institutionData['reg_no']);
            // todo: set rest of properties

            $return[$institutionData['reg_no']] = $institution;
        }
        return $return;
    }

    /**
     * @param Institution[] $institutions
     * @return Institution[]
     * @throws Exception
     */
    public function bulkInsert($institutions) : array
    {
        if (empty($institutions)) {
            return [];
        }
        $values = [];
        $parameters = [];
        foreach ($institutions as $key => $institution) {
            $values[] = " (:cui$key, :name$key)";
            $parameters["cui$key"] = $institution->getRegNo();
            $parameters["name$key"] = $institution->getName();
        }

        $query = "INSERT INTO institution (reg_no, name) values " . implode(", ", $values) . " RETURNING id, reg_no ";

        $statement = $this->pdo->prepare($query);

        foreach ($parameters as $parameterName => $parameterValue) {
            $statement->bindValue($parameterName, $parameterValue);
        }

        $return = $statement->execute();

        if (!$return) {
            throw new Exception($statement->errorInfo()[2] . " " . print_r($institutions, true));
        }

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $data = array_combine(array_column($data, 'reg_no'), array_column($data, 'id'));

        if (count($institutions) !== count($data)) {
            throw new Exception("Tried to create Institutions dynamically, but only some of them were created: 
            input: " . print_r($institutions, true) . " 
            output: " . print_r($data, true));
        }

        foreach ($institutions as $key => $institution) {
            $institution->setId($data[$institution->getRegNo()]);
        }

        return $institutions;
    }
}