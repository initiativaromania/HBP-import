<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Entity;

class Tender extends Contract
{
    //protected $castigator;
    //protected $castigatorCui;
    //protected $castigatorTara;
    //protected $castigatorLocalitate;
    //protected $castigatorAdresa;
    //protected $tip;
    //protected $tipContract;
    //protected $tipProcedura;
    //protected $autoritateContractanta;
    //protected $autoritateContractantaCUI;
    //protected $tipAC;
    //protected $tipActivitateAC;
    //protected $numarAnuntAtribuire;
    //protected $dataAnuntAtribuire;
    //protected $tipIncheiereContract;
    //protected $tipCriteriiAtribuire;
    //protected $cuLicitatieElectronica;
    //protected $numarOfertePrimite;
    //protected $subcontractat;
    //protected $numarContract;
    //protected $dataContract;
    //protected $titluContract;
    //protected $valoare;
    //protected $moneda;
    //protected $valoareRON;
    //protected $valoareEUR;
    //protected $cPVCodeID;
    //protected $cPVCode;
    //protected $numarAnuntParticipare;
    //protected $dataAnuntParticipare;
    //protected $valoareEstimataParticipare;
    //protected $monedaValoareEstimataParticipare;
    //protected $fonduriComunitare;
    //protected $tipFinantare;
    //protected $tipLegislatieID;
    //protected $fondEuropean;
    //protected $contractPeriodic;
    //protected $depoziteGarantii;
    //protected $modalitatiFinantare;

   
    /** @var string */
    protected $type    //character varying(80)   
         
    /** @var string */
    protected $contractType   //character varying(20)   
         
    /** @var string */
    protected $activityType   //character varying(80)   
         
    /** @var string */
    protected $awardingNo //character(10)   
         
    /** @var DateTimeInterface */
    protected $awardingDate   //timestamp without time zone 
         
    /** @var string */
    protected $awardingCriteria   //character varying(50)   
         
    /** @var boolean */
    protected $isElectronic   //boolean 
         
    /** @var int */
    protected $bids    //integer 
         
    /** @var boolean */
    protected $isSubcontracted    //boolean   
         
    /** @var int */
    protected $cpvcodeId  //integer 
         
    /** @var string */
    protected $bidNo  //character(10)   
         
    /** @var DateTimeInterface */
    protected $bidDate    //date    
         
    /** @var float */
    protected $estimatedBidPrice //numeric(20,2)   
         
    /** @var string */
    protected $estimatedBidPriceCurrency    //character(3)    
         
    /** @var string */
    protected $depositsGuarantees //character varying(4000) 
         
    /** @var string */
    protected $financingNotes //character varying(500)  
          
    /** @var string */
    protected $institutionType    //character varying(200)  
         
    /** @var boolean */
    protected $communityFunds //boolean 
         
    /** @var string */
    protected $financingType  //character varying(20)   
         
    /** @var string */
    protected $euFund //character varying(20)

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
    public function getIsElectronic(): boolean
    {
        return $this->isElectronic;
    }

    /**
     * @param boolean $isElectronic
     * @return Tender
     */
    public function setIsElectronic(boolean $isElectronic): Tender
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
    public function getIsSubcontracted(): boolean
    {
        return $this->isSubcontracted;
    }

    /**
     * @param boolean $isSubcontracted
     * @return Tender
     */
    public function setIsSubcontracted(boolean $isSubcontracted): Tender
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
    public function getBidDate(): DateTimeInterface
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
     * @return float
     */
    public function getEstimatedBidPrice(): float
    {
        return $this->estimatedBidPrice;
    }

    /**
     * @param float $estimatedBidPrice
     * @return Tender
     */
    public function setEstimatedBidPrice(float $estimatedBidPrice): Tender
    {
        $this->estimatedBidPrice = $estimatedBidPrice;
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
    public function getCommunityFunds(): boolean
    {
        return $this->communityFunds;
    }

    /**
     * @param boolean $communityFunds
     * @return Tender
     */
    public function setCommunityFunds(boolean $communityFunds): Tender
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