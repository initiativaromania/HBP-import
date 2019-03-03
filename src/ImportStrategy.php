<?php declare(strict_types=1);

namespace Hbp\Import;

interface ImportStrategy
{
    public function processFile(string $fileName);
}
