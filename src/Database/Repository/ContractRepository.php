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
}