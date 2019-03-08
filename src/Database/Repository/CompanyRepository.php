<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Repository;

use Exception;
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



    /**
     * @param Company[] $companies
     * @return Company[]
     * @throws Exception
     */
    public function bulkInsert($companies) : array
    {
        if (empty($companies)) {
            return [];
        }
        $values = [];
        $parameters = [];
        foreach ($companies as $key => $company) {
            $values[] = " (:cui$key, :name$key)";
            $parameters["cui$key"] = $company->getRegNo();
            $parameters["name$key"] = $company->getName();
        }

        $query = "INSERT INTO company (reg_no, name) values " . implode(", ", $values) . " RETURNING id, reg_no ";

        $statement = $this->pdo->prepare($query);

        foreach ($parameters as $parameterName => $parameterValue) {
            $statement->bindValue($parameterName, $parameterValue);
        }

        $return = $statement->execute();

        if (!$return) {
            throw new Exception($statement->errorInfo()[2]);
        }

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $data = array_combine(array_column($data, 'reg_no'), array_column($data, 'id'));

        if (count($companies) !== count($data)) {
            throw new Exception("Tried to create Companies dynamically, but only some of them were created: 
            input: " . print_r($companies, true) . " 
            output: " . print_r($data, true));
        }

        foreach ($companies as $key => $company) {
            $company->setId($data[$company->getRegNo()]);
        }

        return $companies;
    }
}