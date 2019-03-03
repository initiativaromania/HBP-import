<?php

declare(strict_types=1);

namespace Hbp\Import\Database;

use PDO;

interface RepositoryInterface
{
    public function __construct(PDO $pdo);
}