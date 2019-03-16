<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Entity;

use DateTimeInterface;
use Hbp\Import\Database\Repository\NotFoundException;

class Tender
{

    /** @var int */
    private $id;

    /** @var string */
    private $procedure = "";

    /** @var string */
    private $closingType = "";

    /** @var string */
    private $contractNo = "";

    /** @var DateTimeInterface */
    private $contractDate;

    /** @var string */
    private $title = "";

    /** @var string */
    private $price;

    /** @var string */
    private $currency = "";

    /** @var string */
    private $priceEur;

    /** @var string */
    private $priceRon;

    /** @var string */
    private $cpvcode = "";

    /** @var int */
    private $institution;

    /** @var int */
    private $requests;

    /** @var int */
    private $company;

    /** @var string */
    protected $type = "";    //character varying(80)
         
    /** @var string */
    protected $contractType = "";   //character varying(20)
         
    /** @var string */
    protected $activityType = "";   //character varying(80)
         
    /** @var string */
    protected $awardingNo = ""; //character(10)
         
    /** @var DateTimeInterface */
    protected $awardingDate;   //timestamp without time zone
         
    /** @var string */
    protected $awardingCriteria = "";   //character varying(50)
         
    /** @var boolean */
    protected $isElectronic;   //boolean
         
    /** @var int */
    protected $bids;    //integer
         
    /** @var boolean */
    protected $isSubcontracted;    //boolean
         
    /** @var int */
    protected $cpvcodeId;  //integer
         
    /** @var string */
    protected $bidNo = "";  //character(10)
         
    /** @var DateTimeInterface */
    protected $bidDate;    //date
         
    /** @var string */
    protected $estimatedBidPrice; //numeric(20,2)
         
    /** @var string */
    protected $estimatedBidPriceCurrency = "";    //character(3)
         
    /** @var string */
    protected $depositsGuarantees = ""; //character varying(4000)
         
    /** @var string */
    protected $financingNotes = ""; //character varying(500)
          
    /** @var string */
    protected $institutionType = "";    //character varying(200)
         
    /** @var boolean */
    protected $communityFunds; //boolean
         
    /** @var string */
    protected $financingType = "";  //character varying(20)
         
    /** @var string */
    protected $euFund = ""; //character varying(20)

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Tender
     */
    public function setId(int $id): Tender
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getProcedure(): string
    {
        return $this->procedure;
    }

    /**
     * @param string $procedure
     * @return Tender
     */
    public function setProcedure(string $procedure): Tender
    {
        $this->procedure = $procedure;
        return $this;
    }

    /**
     * @return string
     */
    public function getClosingType(): string
    {
        return $this->closingType;
    }

    /**
     * @param string $closingType
     * @return Tender
     */
    public function setClosingType(string $closingType): Tender
    {
        $this->closingType = $closingType;
        return $this;
    }

    /**
     * @return string
     */
    public function getContractNo(): string
    {
        return $this->contractNo;
    }

    /**
     * @param string $contractNo
     * @return Tender
     */
    public function setContractNo(string $contractNo): Tender
    {
        if(strlen($contractNo) > 80) throw new NotFoundException("Dafuck");
        $this->contractNo = $contractNo;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getContractDate(): DateTimeInterface
    {
        return $this->contractDate;
    }

    /**
     * @param DateTimeInterface $contractDate
     * @return Tender
     */
    public function setContractDate(DateTimeInterface $contractDate): Tender
    {
        $this->contractDate = $contractDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Tender
     */
    public function setTitle(string $title): Tender
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return Tender
     */
    public function setPrice(string $price): Tender
    {
        $this->price = sprintf("%0.2f", (float)$price);
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Tender
     */
    public function setCurrency(string $currency): Tender
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getPriceEur(): string
    {
        return $this->priceEur;
    }

    /**
     * @param string $priceEur
     * @return Tender
     */
    public function setPriceEur(string $priceEur): Tender
    {
        $this->priceEur = sprintf("%0.2f", (float)$priceEur);
        return $this;
    }

    /**
     * @return string
     */
    public function getPriceRon(): string
    {
        return $this->priceRon;
    }

    /**
     * @param string $priceRon
     * @return Tender
     */
    public function setPriceRon(string $priceRon): Tender
    {
        $this->priceRon = sprintf("%0.2f", (float)$priceRon);
        return $this;
    }

    /**
     * @return string
     */
    public function getCpvcode(): string
    {
        return $this->cpvcode;
    }

    /**
     * @param string $cpvcode
     * @return Tender
     */
    public function setCpvcode(string $cpvcode): Tender
    {
        $this->cpvcode = $cpvcode;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstitution(): int
    {
        return $this->institution;
    }

    /**
     * @param int $institution
     * @return Tender
     */
    public function setInstitution(int $institution): Tender
    {
        $this->institution = $institution;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param int $requests
     * @return Tender
     */
    public function setRequests(int $requests): Tender
    {
        $this->requests = $requests;
        return $this;
    }

    /**
     * @return int
     */
    public function getCompany(): int
    {
        return $this->company;
    }

    /**
     * @param int $company
     * @return Tender
     */
    public function setCompany(int $company): Tender
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Tender
     */
    public function setType(string $type): Tender
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getContractType(): string
    {
        return $this->contractType;
    }

    /**
     * @param string $contractType
     * @return Tender
     */
    public function setContractType(string $contractType): Tender
    {
        $this->contractType = $contractType;
        return $this;
    }

    /**
     * @return string
     */
    public function getActivityType(): string
    {
        return $this->activityType;
    }

    /**
     * @param string $activityType
     * @return Tender
     */
    public function setActivityType(string $activityType): Tender
    {
        $this->activityType = $activityType;
        return $this;
    }

    /**
     * @return string
     */
    public function getAwardingNo(): string
    {
        return $this->awardingNo;
    }

    /**
     * @param string $awardingNo
     * @return Tender
     */
    public function setAwardingNo(string $awardingNo): Tender
    {
        $this->awardingNo = $awardingNo;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getAwardingDate(): DateTimeInterface
    {
        return $this->awardingDate;
    }

    /**
     * @param DateTimeInterface $awardingDate
     * @return Tender
     */
    public function setAwardingDate(DateTimeInterface $awardingDate): Tender
    {
        $this->awardingDate = $awardingDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getAwardingCriteria(): string
    {
        return $this->awardingCriteria;
    }

    /**
     * @param string $awardingCriteria
     * @return Tender
     */
    public function setAwardingCriteria(string $awardingCriteria): Tender
    {
        $this->awardingCriteria = $awardingCriteria;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsElectronic(): bool
    {
        return $this->isElectronic;
    }

    /**
     * @param boolean $isElectronic
     * @return Tender
     */
    public function setIsElectronic(bool $isElectronic): Tender
    {
        $this->isElectronic = $isElectronic;
        return $this;
    }

    /**
     * @return int
     */
    public function getBids(): int
    {
        return $this->bids;
    }

    /**
     * @param int $bids
     * @return Tender
     */
    public function setBids(int $bids): Tender
    {
        $this->bids = $bids;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsSubcontracted(): bool
    {
        return $this->isSubcontracted;
    }

    /**
     * @param boolean $isSubcontracted
     * @return Tender
     */
    public function setIsSubcontracted(bool $isSubcontracted): Tender
    {
        $this->isSubcontracted = $isSubcontracted;
        return $this;
    }

    /**
     * @return int
     */
    public function getCpvcodeId(): int
    {
        return $this->cpvcodeId;
    }

    /**
     * @param int $cpvcodeId
     * @return Tender
     */
    public function setCpvcodeId(int $cpvcodeId): Tender
    {
        $this->cpvcodeId = $cpvcodeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBidNo(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Tender
     */
    public function setBidNo(string $type): Tender
    {
        $this->type = $type;
        return $this;
    }

     /**
     * @return DateTimeInterface
     */
    public function getBidDate()
    {
        return $this->bidDate;
    }

    /**
     * @param DateTimeInterface $bidDate
     * @return Tender
     */
    public function setBidDate(DateTimeInterface $bidDate): Tender
    {
        $this->bidDate = $bidDate;
        return $this;
    }
               
    /**
     * @return string
     */
    public function getEstimatedBidPrice(): string
    {
        return $this->estimatedBidPrice;
    }

    /**
     * @param string $estimatedBidPrice
     * @return Tender
     */
    public function setEstimatedBidPrice(string $estimatedBidPrice): Tender
    {
        $this->estimatedBidPrice = sprintf("%0.2f", (float)$estimatedBidPrice);
        return $this;
    }  

    /**
     * @return string
     */
    public function getEstimatedBidPriceCurrency(): string
    {
        return $this->estimatedBidPriceCurrency;
    }

    /**
     * @param string $estimatedBidPriceCurrency
     * @return Tender
     */
    public function setEstimatedBidPriceCurrency(string $estimatedBidPriceCurrency): Tender
    {
        $this->estimatedBidPriceCurrency = $estimatedBidPriceCurrency;
        return $this;
    }

    /**
     * @return string
     */
    public function getDepositsGuarantees(): string
    {
        return $this->depositsGuarantees;
    }

    /**
     * @param string $depositsGuarantees
     * @return Tender
     */
    public function setDepositsGuarantees(string $depositsGuarantees): Tender
    {
        $this->depositsGuarantees = $depositsGuarantees;
        return $this;
    }
         
    /**
     * @return string
     */
    public function getFinancingNotes(): string
    {
        return $this->financingNotes;
    }

    /**
     * @param string $financingNotes
     * @return Tender
     */
    public function setFinancingNotes(string $financingNotes): Tender
    {
        $this->financingNotes = $financingNotes;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitutionType(): string
    {
        return $this->institutionType;
    }

    /**
     * @param string $institutionType
     * @return Tender
     */
    public function setInstitutionType(string $institutionType): Tender
    {
        $this->institutionType = $institutionType;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCommunityFunds(): bool
    {
        return $this->communityFunds;
    }

    /**
     * @param boolean $communityFunds
     * @return Tender
     */
    public function setCommunityFunds(bool $communityFunds): Tender
    {
        $this->communityFunds = $communityFunds;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinancingType(): string
    {
        return $this->financingType;
    }

    /**
     * @param string $financingType
     * @return Tender
     */
    public function setFinancingType(string $financingType): Tender
    {
        $this->financingType = $financingType;
        return $this;
    }

    /**
     * @return string
     */
    public function getEuFund(): string
    {
        return $this->euFund;
    }

    /**
     * @param string $euFund
     * @return Tender
     */
    public function setEuFund(string $euFund): Tender
    {
        $this->euFund = $euFund;
        return $this;
    }     
    
}