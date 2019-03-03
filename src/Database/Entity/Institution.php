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
}