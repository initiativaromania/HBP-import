<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Repository;

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

}