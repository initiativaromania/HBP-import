<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Repository;

use Exception;
use Hbp\Import\Database\Entity\Contract;
use Hbp\Import\Database\RepositoryInterface;
use Hbp\Import\PhpError;
use PDO;

class ContractRepository implements RepositoryInterface
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function persist(Contract $contract)
    {
        if ($contract->getId()) {
            $this->update($contract);
        } else {
            $this->insert($contract);
        }
    }

    private function update(Contract $contract)
    {
        throw new Exception("Update not implemented");
    }

    private function insert(Contract $contract)
    {
        $statement = $this->pdo->prepare('INSERT INTO contract values (
            default,
            :procedure,
            :application_no,
            :application_date,
            :closing_type,
            :contract_no,
            :contract_date,
            :title,
            :price,
            :currency,
            :price_eur,
            :price_ron,
            :cpvcode,
            :institution,
            :requests,
            :company,
            :description      
            )
        ');

        $statement->bindValue("proc",             $contract->getProcedure());
        $statement->bindValue("application_no",   $contract->getApplicationNo());
        $statement->bindValue("application_date", $contract->getApplicationDate());
        $statement->bindValue("closing_type",     $contract->getClosingType());
        $statement->bindValue("contract_no",      $contract->getContractNo());
        $statement->bindValue("contract_date",    $contract->getContractDate());
        $statement->bindValue("title",            $contract->getTitle());
        $statement->bindValue("price",            $contract->getPrice());
        $statement->bindValue("currency",         $contract->getCurrency());
        $statement->bindValue("price_eur",        $contract->getPriceEur());
        $statement->bindValue("price_ron",        $contract->getPriceRon());
        $statement->bindValue("cpvcode",          $contract->getCpvcode());
        $statement->bindValue("institution",      $contract->getInstitution());
        $statement->bindValue("requests",         $contract->getRequests());
        $statement->bindValue("company",          $contract->getCompany());
        $statement->bindValue("description",      $contract->getDescription());
    }


    /**
     * @param Contract[] $contracts
     * @return array
     * @throws Exception
     */
    public function bulkInsert($contracts)
    {
        if (empty($contracts)) {
            return [];
        }
        $values = [];
        $parameters = [];
        foreach ($contracts as $key => $contract) {

            $parameters[$key]["proc"]             = $contract->getProcedure();
            $parameters[$key]["application_no"]   = $contract->getApplicationNo();
            $parameters[$key]["application_date"] = $contract->getApplicationDate()->format("Y-m-d");
            $parameters[$key]["closing_type"]     = $contract->getClosingType();
            $parameters[$key]["contract_no"]      = $contract->getContractNo();
            $parameters[$key]["contract_date"]    = $contract->getContractDate()->format("Y-m-d");
            $parameters[$key]["title"]            = $contract->getTitle();
            $parameters[$key]["price"]            = $contract->getPrice();
            $parameters[$key]["currency"]         = $contract->getCurrency();
            $parameters[$key]["price_eur"]        = $contract->getPriceEur();
            $parameters[$key]["price_ron"]        = $contract->getPriceRon();
            $parameters[$key]["cpvcode"]          = $contract->getCpvcode();
            $parameters[$key]["institution"]      = $contract->getInstitution();
            $parameters[$key]["requests"]         = $contract->getRequests();
            $parameters[$key]["company"]          = $contract->getCompany();
            $parameters[$key]["description"]      = substr($contract->getDescription(), 0, 2000);

            $parameterNames = array_map(function ($element) use ($key) {
                return ":" . $element . "_" . $key;
            }, array_keys($parameters[$key]));

            $values[] = " (" . implode(", ", $parameterNames) . ")";

        }


        $query = 'INSERT INTO contract ("procedure", "application_no", "application_date", "closing_type", "contract_no", "contract_date", "title", "price", "currency", "price_eur", "price_ron", "cpvcode", "institution", "requests", "company", "description") 
        values ' . implode(", ", $values);

        $statement = $this->pdo->prepare($query);


        foreach ($parameters as $parameterKey => $parameterFields) {
            foreach ($parameterFields as $parameterName => $parameterValue) {
                $statement->bindValue($parameterName . "_" . $parameterKey , $parameterValue);
            }
        }

        $return = $statement->execute();

        if (!$return) {
            throw new Exception($statement->errorCode() . " " . $statement->errorInfo()[2] . "\n$query\n");
        }
    }
}