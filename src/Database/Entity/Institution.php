<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Entity;

class Institution
{
    private $id;
    private $county;
    private $reg_no;
    private $name;
    private $locality;
    private $address;
    private $lng;
    private $lat;
    private $version;
    private $geo;

    public function store()
    {
//        id
//        county
//        reg_no
//        name
//        locality
//        address
//        lng
//        lat
//        version
//        geo
    }

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
        return $this->reg_no;
    }

    /**
     * @param mixed $reg_no
     */
    public function setRegNo($reg_no): void
    {
        $this->reg_no = $reg_no;
    }

    /**
     * @return mixed
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param mixed $county
     */
    public function setCounty($county): void
    {
        $this->county = $county;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param mixed $locality
     */
    public function setLocality($locality): void
    {
        $this->locality = $locality;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }
}