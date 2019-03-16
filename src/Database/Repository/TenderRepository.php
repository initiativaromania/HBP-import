<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Repository;

use Exception;
use Hbp\Import\Database\Entity\Tender;
use Hbp\Import\Database\RepositoryInterface;
use PDO;

class TenderRepository implements RepositoryInterface
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param Tender[] $tenders
     * @return array
     * @throws Exception
     */
    public function bulkInsert(array $tenders)
    {
        if (empty($tenders)) {
            return [];
        }
        $values = [];
        $parameters = [];
        foreach ($tenders as $key => $tender) {

            $parameters[$key]["type"] = $tender->getType();
            $parameters[$key]["contract_type"] = $tender->getContractType();
            $parameters[$key]["procedure"] = $tender->getProcedure();
            $parameters[$key]["activity_type"] = $tender->getActivityType();
            $parameters[$key]["awarding_no"] = $tender->getAwardingNo();
            $parameters[$key]["awarding_date"] = $tender->getAwardingDate()->format("Y-m-d H:i:s.u");
            $parameters[$key]["closing_type"] = $tender->getClosingType();
            $parameters[$key]["awarding_criteria"] = $tender->getAwardingCriteria();
            $parameters[$key]["is_electronic"] = $tender->getIsElectronic();
            $parameters[$key]["bids"] = $tender->getBids();
            $parameters[$key]["is_subcontracted"] = $tender->getIsSubcontracted();
            $parameters[$key]["contract_no"] = $tender->getContractNo();
            $parameters[$key]["contract_date"] = $tender->getContractDate()->format("Y-m-d");
            $parameters[$key]["title"] = $tender->getTitle();
            $parameters[$key]["price"] = $tender->getPrice();
            $parameters[$key]["currency"] = $tender->getCurrency();
            $parameters[$key]["price_ron"] = $tender->getPriceRon();
            $parameters[$key]["price_eur"] = $tender->getPriceEur();
            $parameters[$key]["cpvcode_id"] = $tender->getCpvcodeId();
            $parameters[$key]["cpvcode"] = $tender->getCpvcode();
            $parameters[$key]["bid_no"] = $tender->getBidNo();
            $bidDate = $tender->getBidDate();
            $parameters[$key]["bid_date"] = $bidDate ? $bidDate->format("Y-m-d") : null;

            $parameters[$key]["estimated_bid_price"] = $tender->getEstimatedBidPrice();
            $parameters[$key]["estimated_bid_price_currency"] = $tender->getEstimatedBidPriceCurrency();
            $parameters[$key]["deposits_guarantees"] = $tender->getDepositsGuarantees();
            $parameters[$key]["financing_notes"] = $tender->getFinancingNotes();
            $parameters[$key]["institution"] = $tender->getInstitution();
            $parameters[$key]["requests"] = $tender->getRequests();
            $parameters[$key]["company"] = $tender->getCompany();
            $parameters[$key]["institution_type"] = $tender->getInstitutionType();
            $parameters[$key]["community_funds"] = (bool)$tender->getCommunityFunds();
            $parameters[$key]["financing_type"] = $tender->getFinancingType();
            $parameters[$key]["eu_fund"] = $tender->getEuFund();

            $parameterNames = array_map(function ($element) use ($key) {
                return ":" . $element . "_" . $key;
            }, array_keys($parameters[$key]));

            $values[] = " (" . implode(", ", $parameterNames) . ")";

        }


        $query = 'INSERT INTO tender ("type", "contract_type", "procedure", "activity_type", "awarding_no", "awarding_date", "closing_type", "awarding_criteria", "is_electronic", "bids", "is_subcontracted", "contract_no", "contract_date", "title", "price", "currency", "price_ron", "price_eur", "cpvcode_id", "cpvcode", "bid_no", "bid_date", "estimated_bid_price", "estimated_bid_price_currency", "deposits_guarantees", "financing_notes", "institution", "requests", "company", "institution_type", "community_funds", "financing_type", "eu_fund") 
        values ' . implode(", ", $values);

        $statement = $this->pdo->prepare($query);


        foreach ($parameters as $parameterKey => $parameterFields) {
            foreach ($parameterFields as $parameterName => $parameterValue) {
                if (is_bool($parameterValue)) {
                    $statement->bindValue($parameterName . "_" . $parameterKey , $parameterValue, PDO::PARAM_BOOL);
                }
                else if ( is_int($parameterValue)){
                    $statement->bindValue($parameterName . "_" . $parameterKey , $parameterValue, PDO::PARAM_INT);

                }
                else {
                    $statement->bindValue($parameterName . "_" . $parameterKey , $parameterValue);
                }
            }
        }

        $return = $statement->execute();

        if (!$return) {
            throw new Exception($statement->errorCode() . " " . $statement->errorInfo()[2] . "\n");
        }

    }
}