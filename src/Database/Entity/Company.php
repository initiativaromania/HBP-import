<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Entity;

class Company
{
    private $id;
    private $name;
    private $regNo;
    private $country;
    private $locality;
    private $address;
    private $contract_company;
    private $tender_company;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRegNo()
    {
        return $this->regNo;
    }

    /**
     * @param mixed $regNo
     */
    public function setRegNo($regNo): void
    {
        $this->regNo = $regNo;
    }
}