<?php

declare(strict_types=1);

namespace Hbp\Import\ImportStrategies;

use Generator;
use Hbp\Import\ImportStrategy;
use Hbp\Import\IncorrectStrategyException;
use SplFileObject;

class TestStrategy implements ImportStrategy
{
    /** @var string */
    private $encoding;

    /** @var string[] */
    private $columns;

    /**
     * TestStrategy constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $fileName
     * @throws IncorrectStrategyException
     */
    public function openFile(string $fileName)
    {
        if (substr($fileName, -4) !== "xlsx") {
            throw new IncorrectStrategyException("Input file not a xlsx file");
        }

        $file = $this->getFileObject($fileName);

        $this->encoding = $this->parseEncoding($file);

        $this->skipNextRow($file);
        $this->skipNextRow($file);

        $this->columns = $this->parseColumnNames($file);

        echo implode("\n", $this->columns) . "\n";


        $expectedTitles = [
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

        if ($expectedTitles !== $this->columns) {
            throw new IncorrectStrategyException("Strategy expects different column names");
        }

        $rows = $this->getRowIterator($file);

        foreach ($rows as $index => $row) {
              echo $row;
            echo PHP_EOL;
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
    private function parseColumnNames(SplFileObject $file)
    {
        $tableHeaderRow = $file->fgets();

        preg_match_all("#<t>([^<>]+)</t>#", $tableHeaderRow, $matches);
        return $matches[1];
    }

    /**
     * @param SplFileObject $file
     * @return Generator|string[]
     */
    public function getRowIterator(SplFileObject $file): Generator
    {
        while (!$file->eof()) {
            yield $file->fgets();
        }
    }
}
