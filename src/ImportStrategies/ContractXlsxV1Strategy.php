<?php

declare(strict_types=1);

namespace Hbp\Import\ImportStrategies;

use DateTimeImmutable;
use Generator;
use Hbp\Import\Database\Database;
use Hbp\Import\Database\Entity\Contract;
use Hbp\Import\Database\Repository\CompanyNotFoundException;
use Hbp\Import\Database\Repository\CompanyRepository;
use Hbp\Import\Database\Repository\InstitutionNotFoundException;
use Hbp\Import\Database\Repository\InstitutionRepository;
use Hbp\Import\Database\Repository\NotFoundException;
use Hbp\Import\ImportStrategy;
use Hbp\Import\IncorrectStrategyException;
use SplFileObject;

class ContractXlsxV1Strategy implements ImportStrategy
{
    static $notFound = 0;
    static $found = 0;

    /** @var Database */
    private $database;

    /** @var string */
    private $encoding;

    /** @var string[] */
    private $columns = [
        "CASTIGATOR",
        "CASTIGATOR_CUI",
        "CASTIGATOR_TARA",
        "CASTIGATOR_LOCALITATE",
        "CASTIGATOR_ADRESA",
        "TIP_PROCEDURA",
        "AUTORITATE_CONTRACTANTA",
        "AUTORITATE_CONTRACTANTA_CUI",
        "NUMAR_ANUNT",
        "DATA_ANUNT",
        "DESCRIERE",
        "TIP_INCHEIERE_CONTRACT",
        "NUMAR_CONTRACT",
        "DATA_CONTRACT",
        "TITLU_CONTRACT",
        "VALOARE",
        "MONEDA",
        "VALOARE_RON",
        "VALOARE_EUR",
        "CPV_CODE_ID",
        "CPV_CODE"
    ];

    /** @var InstitutionRepository */
    private $institutionRepository;

    /** @var CompanyRepository */
    private $companyRepository;

    /**
     * TestStrategy constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        register_shutdown_function(function () {
            echo "Found: " . self::$found . " / Not found: " . self::$notFound . "\n";
        });

        $this->institutionRepository = $this->database->getRepository('institution');
        $this->companyRepository = $this->database->getRepository('company');
    }

    /**
     * @param string $fileName
     * @throws IncorrectStrategyException
     */
    public function processFile(string $fileName)
    {
        if (substr($fileName, -4) !== "xlsx") {
            throw new IncorrectStrategyException("Input file not a xlsx file");
        }
        $file = $this->getFileObject($fileName);

        $contractIterator = $this->getContractIterator($file);

        $savingBatch = 10;

        $unsavedContracts = [];
        $index = 0;
        foreach ($contractIterator as $company)
        {
            $unsavedContracts[] = $company;
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
            $sheetName = "sheet1";
            $file = new SplFileObject("zip://" . $fileName . "#xl/worksheets/$sheetName.xml");
        } catch (\Hbp\Import\PhpError $e) {
            throw new IncorrectStrategyException("Cannot open worksheet \"$sheetName\" from xml file");
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
        $tableRow = $file->fgets();
        preg_match_all("#<c(\ [a-z]+\=\"[a-z0-9]+\")*>(<[a-z0-9]+>)*([^<]*)#i", $tableRow, $matches);
        return array_map('trim', $matches[3]);
    }

    /**
     * @param SplFileObject $file
     * @return Generator|string[]
     * @throws IncorrectStrategyException
     */
    public function getBatchIterator(SplFileObject $file, $batchSize = 1000): Generator
    {
        $this->encoding = $this->parseEncoding($file);
        $this->skipNextRow($file);
        $this->skipNextRow($file);
        $foundColumns = $this->parseRow($file);
        if ($this->columns !== $foundColumns) {
            throw new IncorrectStrategyException("Strategy expects different column names");
        }

        $batch = [];

        while (!$file->eof()) {
            $row = $this->parseRow($file);
            if (empty($row)) {continue;}
            $row = array_combine($this->columns, $row);

            $batch[] = $row;
            if (count($batch) == $batchSize )
            {
                yield $batch;
                $batch = [];
            }


            if (!$file->eof()) {
                $file->fgets();
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
            $row['AUTORITATE_CONTRACTANTA'] = html_entity_decode($row['AUTORITATE_CONTRACTANTA']);
            $row['AUTORITATE_CONTRACTANTA'] = Cleanup::deleteMultipleSpaces($row['AUTORITATE_CONTRACTANTA']);
            $row['AUTORITATE_CONTRACTANTA'] = Cleanup::replaceWeirdCharacters($row['AUTORITATE_CONTRACTANTA']);

            $row['CASTIGATOR'] = html_entity_decode($row['CASTIGATOR']);
            $row['CASTIGATOR'] = Cleanup::deleteMultipleSpaces($row['CASTIGATOR']);
            $row['CASTIGATOR'] = Cleanup::replaceWeirdCharacters($row['CASTIGATOR']);

            try {
                $row['AUTORITATE_CONTRACTANTA_CUI'] = $this->extractCui($row['AUTORITATE_CONTRACTANTA_CUI']);
                $row['CASTIGATOR_CUI'] = $this->extractCui($row['CASTIGATOR_CUI']);
            } catch (NotFoundException $exception) {
                $this->handleFuckedUpCui($row);
            }
            $return[] = $row;
        }
        return $return;
    }

    private function createInstitutionLookupCache($batch)
    {
        $institutions = array_column($batch, 'AUTORITATE_CONTRACTANTA_CUI');
        return $this->institutionRepository->findByCuiBulk($institutions);
    }


    private function createCompanyLookupCache($batch)
    {
        $companies = array_column($batch, 'CASTIGATOR_CUI');
        return $this->companyRepository->findByCuiBulk($companies);
    }


    private function extractCui($string)
    {
        preg_match("#^[^0-9]*([0-9]{1,})#", $string, $matches);
        if (!isset($matches[1])) {
            throw new NotFoundException("Cannot identify CUI in string: '{$string}' ");
        }
        return $matches[1];
    }

    private function handleFuckedUpCui($row)
    {
        echo "Incorrect cui format for record: \n";
        dp($row);
        echo  "\nWill be ignored";
    }

    /**
     * @param $row
     * @param $institution
     * @param $company
     * @return Contract
     */
    private function createContract($row, $institution, $company): Contract
    {
        $contract = new Contract();
        $contract->setProcedure(strtolower($row['TIP_PROCEDURA']));
        $contract->setApplicationNo($row['NUMAR_ANUNT']);
        $contract->setApplicationDate(DateTimeImmutable::createFromFormat("d-m-Y H:i:s", $row['DATA_ANUNT']));
        $contract->setClosingType(strtolower($row['TIP_INCHEIERE_CONTRACT']));
        $contract->setContractNo($row['NUMAR_CONTRACT']);
        $contract->setContractDate(DateTimeImmutable::createFromFormat("d-m-Y H:i:s", $row['DATA_CONTRACT']));
        $contract->setTitle($row['TITLU_CONTRACT']);
        $contract->setPrice((float)$row['VALOARE']);
        $contract->setCurrency($row['MONEDA']);
        $contract->setPriceEur((float)$row['VALOARE_EUR']);
        $contract->setPriceRon((float)$row['VALOARE_RON']);
        $contract->setDescription($row['DESCRIERE']);

        $contract->setCpvcode($row['CPV_CODE']);

        $contract->setInstitution($institution->getId());
        $contract->setCompany($company->getId());

        return $contract;
    }

    /**
     * @param SplFileObject $file
     * @return Generator
     * @throws IncorrectStrategyException
     */
    private function getContractIterator(SplFileObject $file)
    {
        $rows = $this->getBatchIterator($file, 5000);

        foreach ($rows as $batchIndex => $batch) {

            $batch = $this->data_cleanup($batch);

            $institutions = $this->createInstitutionLookupCache($batch);
            $companies = $this->createCompanyLookupCache($batch);

            // todo: create missing institutions
            // todo: create missing companies

            foreach ($batch as $rowIndex => $row)
            {
                try {
                    if (!isset($institutions[$row['AUTORITATE_CONTRACTANTA_CUI']])) {
                        throw new InstitutionNotFoundException("Institutia nu a fost gasita: cui=" . $row['AUTORITATE_CONTRACTANTA_CUI'] . " - " . $row['AUTORITATE_CONTRACTANTA']);
                    }
                    if (!isset($companies[$row['CASTIGATOR_CUI']])) {
                        throw new CompanyNotFoundException("Compania nu a fost gasita: cui=" . $row['CASTIGATOR_CUI'] . " - " . $row['CASTIGATOR']);
                    }
                    $institution = $institutions[$row['AUTORITATE_CONTRACTANTA_CUI']];
                    $company = $companies[$row['CASTIGATOR_CUI']];

                    static::$found++;

                    yield $this->createContract($row, $institution, $company);

                } catch (NotFoundException $exception) {
                    echo "Cound not process one row: " . $exception->getMessage() . "\n";
                    static::$notFound++;
                }


            }
        }
    }


    /**
     * @param Contract[] $unsavedContracts
     */
    private function saveContracts(array $unsavedContracts)
    {
        // todo: create batch save function
    }


}
