<?php

declare(strict_types=1);

namespace Hbp\Import\Database\Repository;

use Hbp\Import\Database\RepositoryInterface;
use PDO;

class TenderRepository implements RepositoryInterface
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function bulkInsert(array $tenders)
    {
        //
    }
}