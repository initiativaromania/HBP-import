<?php

declare(strict_types=1);

namespace Hbp\Import\Database;

use PDO;

class Database
{
    /** @var PDO */
    private $pdo;

    /**
     * @var RepositoryInterface[]
     */
    private $repositories = [];

    /**
     * Persister constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $alias
     * @param RepositoryInterface $class
     */
    public function registerRepository(string $alias, RepositoryInterface $class)
    {
        $this->repositories[$alias] = $class;
    }

    /**
     * @param string $alias
     * @return RepositoryInterface
     */
    public function getRepository(string $alias) : RepositoryInterface
    {
        return $this->repositories[$alias];
    }
}