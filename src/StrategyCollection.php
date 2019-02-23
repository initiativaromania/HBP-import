<?php declare(strict_types=1);

namespace Hbp\Import;

use LogicException;

class StrategyCollection implements \IteratorAggregate
{
    /**
     * @var ImportStrategy[]
     */
    private $strategies;

    /**
     * StrategyCollection constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return ImportStrategy[]
     */
    public function getIterator()
    {
        return $this->strategies;
    }

    /**
     * @param ImportStrategy $strategy
     * @param string $alias
     */
    public function add(ImportStrategy $strategy, $alias)
    {

        if (!is_string($alias) || !strlen($alias)) {
            throw new LogicException("Incorrect alias specified for class \"" . get_class($strategy) . "\"");
        }
    }
}