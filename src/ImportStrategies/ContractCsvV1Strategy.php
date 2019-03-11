<?php

declare(strict_types=1);

namespace Hbp\Import\ImportStrategies;

use DateTimeImmutable;
use Exception;
use Generator;
use Hbp\Import\Database\Database;
use Hbp\Import\Database\Entity\Company;
use Hbp\Import\Database\Entity\Contract;
use Hbp\Import\Database\Entity\Institution;
use Hbp\Import\Database\Repository\CompanyNotFoundException;
use Hbp\Import\Database\Repository\CompanyRepository;
use Hbp\Import\Database\Repository\ContractRepository;
use Hbp\Import\Database\Repository\InstitutionNotFoundException;
use Hbp\Import\Database\Repository\InstitutionRepository;
use Hbp\Import\Database\Repository\NotFoundException;
use Hbp\Import\ImportStrategy;
use Hbp\Import\IncorrectStrategyException;
use SplFileObject;

/**
 * Class ContractCsvV1Strategy was created based on Csv files from 2018
 */
class ContractCsvV1Strategy implements ImportStrategy
{
    const FIELD_NUME_COMPANIE          = "Castigator";
    const FIELD_CUI_COMPANIE           = "CastigatorCUI";
    const FIELD_TARA_COMPANIE          = "CastigatorTara";
    const FIELD_LOCALITATE_COMPANIE    = "CastigatorLocalitate";
    const FIELD_ADRESA_COMPANIE        = "CastigatorAdresa";
    const FIELD_PROCEDURA              = "TipProcedura";
    const FIELD_NUME_INSTITUTIE        = "AutoritateContractanta";
    const FIELD_CUI_INSTITUTIE         = "AutoritateContractantaCUI";
    const FIELD_NUMAR_ANUNT            = "NumarAnunt";
    const FIELD_DATA_ANUNT             = "DataAnunt";
    const FIELD_DESCRIERE_CONTRACT     = "Descriere";
    const FIELD_TIP_INCHEIERE_CONTRACT = "TipIncheiereContract";
    const FIELD_NUMAR_CONTRACT         = "NumarContract";
    const FIELD_DATA_CONTRACT          = "DataContract";
    const FIELD_TITLU_CONTRACT         = "TitluContract";
    const FIELD_VALOARE                = "Valoare";
    const FIELD_MONEDA                 = "Moneda";
    const FIELD_VALOARE_RON            = "ValoareRON";
    const FIELD_VALOARE_EUR            = "ValoareEUR";
    const FIELD_CPV_CODE_ID            = "CPVCodeID";
    const FIELD_CPV_CODE               = "CPVCode";

    /** @var Database */
    private $database;

    /** @var string */
    private $encoding;

    /** @var string[] */
    private $expectedColumns = [
        self::FIELD_NUME_COMPANIE,
        self::FIELD_CUI_COMPANIE,
        self::FIELD_TARA_COMPANIE,
        self::FIELD_LOCALITATE_COMPANIE,
        self::FIELD_ADRESA_COMPANIE,
        self::FIELD_PROCEDURA,
        self::FIELD_NUME_INSTITUTIE,
        self::FIELD_CUI_INSTITUTIE,
        self::FIELD_NUMAR_ANUNT,
        self::FIELD_DATA_ANUNT,
        self::FIELD_DESCRIERE_CONTRACT,
        self::FIELD_TIP_INCHEIERE_CONTRACT,
        self::FIELD_NUMAR_CONTRACT,
        self::FIELD_DATA_CONTRACT,
        self::FIELD_TITLU_CONTRACT,
        self::FIELD_VALOARE,
        self::FIELD_MONEDA,
        self::FIELD_VALOARE_RON,
        self::FIELD_VALOARE_EUR,
        self::FIELD_CPV_CODE_ID,
        self::FIELD_CPV_CODE
    ];

    /** @var InstitutionRepository */
    private $institutionRepository;

    /** @var CompanyRepository */
    private $companyRepository;

    /** @var ContractRepository  */
    private $contractRepository;

    /**
     * TestStrategy constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;

        $this->institutionRepository = $this->database->getRepository('institution');
        $this->companyRepository = $this->database->getRepository('company');
        $this->contractRepository = $this->database->getRepository('contract');
    }

    /**
     * @param string $fileName
     * @throws IncorrectStrategyException
     * @throws Exception
     */
    public function processFile(string $fileName)
    {
        if (substr($fileName, -3) !== "csv") {
            throw new IncorrectStrategyException("Input file not a csv file");
        }
        $file = $this->getFileObject($fileName);

        $contractIterator = $this->getContractIterator($file);

        $savingBatch = 4000;

        $unsavedContracts = [];
        $index = 0;
        foreach ($contractIterator as $contract)
        {
            $unsavedContracts[] = $contract;
            $index++;
            if ($index == $savingBatch) {
                $this->saveContracts($unsavedContracts);
                $unsavedContracts = [];
                $index = 0;
            }
        }
        if (!empty($unsavedContracts)) {
            $this->saveContracts($unsavedContracts);
        }
    }

    /**
     * @param string $fileName
     * @return SplFileObject
     * @throws IncorrectStrategyException
     */
    private function getFileObject(string $fileName)
    {
        try {
            $file = new SplFileObject($fileName);
        } catch (\Hbp\Import\PhpError $e) {
            throw new IncorrectStrategyException("Cannot open file \"$fileName\"");
        }
        return $file;
    }

    /**
     * Parse encoding from header
     *
     * @param SplFileObject $file
     * @return string
     * @throws IncorrectStrategyException
     */
    private function parseEncoding(SplFileObject $file)
    {
        $firstRow = $file->fgets();
        $found = preg_match('#encoding\=\"([A-Z0-9\-]+)\"#', $firstRow, $matches);
        if (!$found) {
            throw new IncorrectStrategyException("Worksheet xml not as expected. Encoding missing.");
        }
        return $matches[1];
    }

    /**
     * @param SplFileObject $file
     */
    private function skipNextRow(SplFileObject $file)
    {
        $file->fgets();
    }

    /**
     * @param SplFileObject $file
     * @return string[]
     */
    private function parseRow(SplFileObject $file)
    {
        $current = $file->ftell();
        $tableRow = $file->fgetcsv("^");
        if (count($tableRow) !== 21) {
            // try again
            $file->fseek($current);

            $tableRow = $file->fgets();
            $data = preg_match("#^(.+)\^([0-9]+)\^(.+)\^(.+)\^(.+)\^(Cumparare directa)\^(.+)\^(.+)\^(DA[0-9]+)\^([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]*)\^(.+)\^(Cumparare directa)\^(DA[0-9]+)\^([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]*)\^(.+)\^(.+)\^([a-z]{3})\^(.+)\^(.+)\^(.+)\^(.+)$#i", $tableRow, $matches);


            if (!$data) {echo $tableRow . "\n\n"; print_r($matches);}

            unset($matches[0]);
            $tableRow = $matches;
        }

        return array_map('trim', $tableRow);
    }

    /**
     * @param SplFileObject $file
     * @param int $batchSize
     * @return Generator|string[]
     * @throws IncorrectStrategyException
     */
    public function getBatchIterator(SplFileObject $file, $batchSize = 1000): Generator
    {
        $foundColumns = $this->parseRow($file);
        $foundColumns[0] = substr($foundColumns[0], 3); // bom hack
        if ($this->expectedColumns !== $foundColumns) {
            throw new IncorrectStrategyException("Strategy expects different column names");
        }

        $batch = [];

        while (!$file->eof()) {
            $row = $this->parseRow($file);
            if (empty($row)) {continue;}

            if (count($row) !== count($this->expectedColumns)) {
                print_r($row);
                exit;
            }

            $row = array_combine($this->expectedColumns, $row);

            $batch[] = $row;
            if (count($batch) == $batchSize )
            {
                yield $batch;
                $batch = [];
            }
        }

        if (!empty($batch)) {
            yield $batch;
        }
    }

    private function data_cleanup($batch)
    {
        $return = [];
        foreach ($batch as $row) {
            $row[self::FIELD_NUME_INSTITUTIE] = html_entity_decode($row[self::FIELD_NUME_INSTITUTIE]);
            $row[self::FIELD_NUME_INSTITUTIE] = Cleanup::deleteMultipleSpaces($row[self::FIELD_NUME_INSTITUTIE]);
            $row[self::FIELD_NUME_INSTITUTIE] = Cleanup::replaceWeirdCharacters($row[self::FIELD_NUME_INSTITUTIE]);

            $row[self::FIELD_NUME_COMPANIE] = html_entity_decode($row[self::FIELD_NUME_COMPANIE]);
            $row[self::FIELD_NUME_COMPANIE] = Cleanup::deleteMultipleSpaces($row[self::FIELD_NUME_COMPANIE]);
            $row[self::FIELD_NUME_COMPANIE] = Cleanup::replaceWeirdCharacters($row[self::FIELD_NUME_COMPANIE]);

            try {
                $row[self::FIELD_CUI_INSTITUTIE] = $this->extractCui($row[self::FIELD_CUI_INSTITUTIE]);
                $row[self::FIELD_CUI_COMPANIE] = $this->extractCui($row[self::FIELD_CUI_COMPANIE]);
                $return[] = $row;
            } catch (NotFoundException $exception) {
                echo $exception->getMessage() . PHP_EOL;
                echo print_r($row, true) . PHP_EOL;
                echo  "Will be ignored\n";
            }
        }
        return $return;
    }

    /**
     * @param $batch
     * @return Institution[]
     * @throws Exception
     */
    private function createInstitutionLookupCache($batch)
    {
        $institutionCuis = array_column($batch, self::FIELD_CUI_INSTITUTIE);
        $persisted = $this->institutionRepository->findByCuiBulk($institutionCuis);
        $unpersisted = [];
        foreach ($batch as $each) {
            if (isset($persisted[$each[self::FIELD_CUI_INSTITUTIE]])) {
                continue;
            }
            $institution = new Institution();
            $institution->setRegNo($each[self::FIELD_CUI_INSTITUTIE]);
            $institution->setName($each[self::FIELD_NUME_INSTITUTIE]);
            $unpersisted[$each[self::FIELD_CUI_INSTITUTIE]] = $institution;

        }
        $this->institutionRepository->bulkInsert($unpersisted);

        return $persisted + $unpersisted;
    }

    /**
     * @param $batch
     * @return Company[]
     * @throws Exception
     */
    private function createCompanyLookupCache($batch)
    {
        $companies = array_column($batch, self::FIELD_CUI_COMPANIE);
        $persisted = $this->companyRepository->findByCuiBulk($companies);
        $unpersisted = [];
        foreach ($batch as $each) {
            if (isset($persisted[$each[self::FIELD_CUI_COMPANIE]])) {
                continue;
            }
            $company = new Company();
            $company->setRegNo($each[self::FIELD_CUI_COMPANIE]);
            $company->setName($each[self::FIELD_NUME_COMPANIE]);
            $company->setCountry($each[self::FIELD_TARA_COMPANIE]);
            $company->setLocality($each[self::FIELD_ADRESA_COMPANIE]);
            $company->setAddress($each[self::FIELD_LOCALITATE_COMPANIE]);

            $unpersisted[$each[self::FIELD_CUI_COMPANIE]] = $company;

        }
        $this->companyRepository->bulkInsert($unpersisted);

        return $persisted + $unpersisted;
    }


    private function extractCui($string)
    {
        preg_match("#^[^1-9]*([0-9]{1,13})#", $string, $matches);
        if (!isset($matches[1])) {
            throw new NotFoundException("Cannot identify CUI in string: '{$string}' ");
        }
        return $matches[1];
    }


    /**
     * @param $row
     * @param $institution
     * @param $company
     * @return Contract
     */
    private function createContract($row, Institution $institution, Company $company): Contract
    {
        $contract = new Contract();
        $contract->setProcedure(strtolower($row[self::FIELD_PROCEDURA]));
        $contract->setApplicationNo($row[self::FIELD_NUMAR_ANUNT]);
        $contract->setApplicationDate(DateTimeImmutable::createFromFormat("Y-m-d H:i:s.u", $row[self::FIELD_DATA_ANUNT]));
        $contract->setClosingType(strtolower($row[self::FIELD_TIP_INCHEIERE_CONTRACT]));
        $contract->setContractNo($row[self::FIELD_NUMAR_CONTRACT]);
        $contract->setContractDate(DateTimeImmutable::createFromFormat("Y-m-d H:i:s.u", $row[self::FIELD_DATA_CONTRACT]));
        $contract->setTitle($row[self::FIELD_TITLU_CONTRACT]);
        $contract->setPrice((float)$row[self::FIELD_VALOARE]);
        $contract->setCurrency($row[self::FIELD_MONEDA]);
        $contract->setPriceEur((float)$row[self::FIELD_VALOARE_EUR]);
        $contract->setPriceRon((float)$row[self::FIELD_VALOARE_RON]);
        $contract->setDescription($row[self::FIELD_DESCRIERE_CONTRACT]);

        $contract->setCpvcode($row[self::FIELD_CPV_CODE]);

        $contract->setInstitution($institution->getId());
        $contract->setCompany($company->getId());

        return $contract;
    }

    /**
     * @param SplFileObject $file
     * @return Generator
     * @throws IncorrectStrategyException
     * @throws Exception
     */
    private function getContractIterator(SplFileObject $file)
    {
        $batchSize = 5000;

        echo "Running script in batches of $batchSize\n";

        $rows = $this->getBatchIterator($file, $batchSize);

        foreach ($rows as $batchIndex => $batch) {
            echo "#";
            $batch = $this->data_cleanup($batch);

            $institutions = $this->createInstitutionLookupCache($batch);
            $companies = $this->createCompanyLookupCache($batch);

            foreach ($batch as $rowIndex => $row)
            {
                try {
                    if (!isset($institutions[$row[self::FIELD_CUI_INSTITUTIE]])) {
                        throw new InstitutionNotFoundException("Institution not found: cui=" . $row[self::FIELD_CUI_INSTITUTIE] . " - " . $row[self::FIELD_NUME_INSTITUTIE]);
                    }
                    if (!isset($companies[$row[self::FIELD_CUI_COMPANIE]])) {
                        throw new CompanyNotFoundException("Company not found: cui=" . $row[self::FIELD_CUI_COMPANIE] . " - " . $row[self::FIELD_NUME_COMPANIE]);
                    }
                    $institution = $institutions[$row[self::FIELD_CUI_INSTITUTIE]];
                    $company = $companies[$row[self::FIELD_CUI_COMPANIE]];
                    yield $this->createContract($row, $institution, $company);

                } catch (NotFoundException $exception) {
                    echo "Could not process one row: " . $exception->getMessage() . "\n";
                    exit;
                }
            }
        }
    }


    /**
     * @param Contract[] $unsavedContracts
     * @throws Exception
     */
    private function saveContracts(array $unsavedContracts)
    {
        $this->contractRepository->bulkInsert($unsavedContracts);
    }
}
