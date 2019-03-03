<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Repository;

use Hbp\Import\Database\Entity\Company;
use Hbp\Import\Database\RepositoryInterface;
use PDO;

class CompanyRepository implements RepositoryInterface
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

        $statement = $this->pdo->prepare("SELECT * from company where reg_no in ('" . implode("', '", $cuis) . "')");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $return = [];

        foreach ($data as $companyData) {
            $company = new Company();
            $company->setId($companyData['id']);
            $company->setRegNo($companyData['reg_no']);
            // todo: set rest of properties

            $return[$companyData['reg_no']] = $company;
        }
        return $return;
    }
}