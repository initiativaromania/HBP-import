<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Entity;

use DateTimeInterface;

class Contract
{
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Contract
     */
    public function setId(int $id): Contract
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
     * @return Contract
     */
    public function setProcedure(string $procedure): Contract
    {
        $this->procedure = $procedure;
        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationNo(): string
    {
        return $this->applicationNo;
    }

    /**
     * @param string $applicationNo
     * @return Contract
     */
    public function setApplicationNo(string $applicationNo): Contract
    {
        $this->applicationNo = $applicationNo;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getApplicationDate(): DateTimeInterface
    {
        return $this->applicationDate;
    }

    /**
     * @param DateTimeInterface $applicationDate
     * @return Contract
     */
    public function setApplicationDate(DateTimeInterface $applicationDate): Contract
    {
        $this->applicationDate = $applicationDate;
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
     * @return Contract
     */
    public function setClosingType(string $closingType): Contract
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
     * @return Contract
     */
    public function setContractNo(string $contractNo): Contract
    {
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
     * @return Contract
     */
    public function setContractDate(DateTimeInterface $contractDate): Contract
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
     * @return Contract
     */
    public function setTitle(string $title): Contract
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Contract
     */
    public function setPrice(float $price): Contract
    {
        $this->price = $price;
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
     * @return Contract
     */
    public function setCurrency(string $currency): Contract
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceEur(): float
    {
        return $this->priceEur;
    }

    /**
     * @param string $priceEur
     * @return Contract
     */
    public function setPriceEur(float $priceEur): Contract
    {
        $this->priceEur = $priceEur;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceRon(): float
    {
        return $this->priceRon;
    }

    /**
     * @param float $priceRon
     * @return Contract
     */
    public function setPriceRon(float $priceRon): Contract
    {
        $this->priceRon = $priceRon;
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
     * @return Contract
     */
    public function setCpvcode(string $cpvcode): Contract
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
     * @return Contract
     */
    public function setInstitution(int $institution): Contract
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
     * @return Contract
     */
    public function setRequests(int $requests): Contract
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
     * @return Contract
     */
    public function setCompany(int $company): Contract
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Contract
     */
    public function setDescription(string $description): Contract
    {
        $this->description = $description;
        return $this;
    }

    /** @var int */
    private $id;

    /** @var string */
    private $procedure;

    /** @var string */
    private $applicationNo;

    /** @var DateTimeInterface */
    private $applicationDate;

    /** @var string */
    private $closingType;

    /** @var string */
    private $contractNo;

    /** @var DateTimeInterface */
    private $contractDate;

    /** @var string */
    private $title;

    /** @var float */
    private $price;

    /** @var string */
    private $currency;

    /** @var float */
    private $priceEur;

    /** @var float */
    private $priceRon;

    /** @var string */
    private $cpvcode;

    /** @var int */
    private $institution;

    /** @var int */
    private $requests;

    /** @var int */
    private $company;

    /** @var string */
    private $description;
}