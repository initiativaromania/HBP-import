<?php declare(strict_types=1);

namespace Hbp\Import;

use InvalidArgumentException;

class StrategyCollection implements \IteratorAggregate
{
    /**
     * @var ImportStrategy[]
     */
    private $strategies;

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
            throw new ImplementationException("Incorrect alias specified for class \"" . get_class($strategy) . "\"");
        }

        if (isset($this->strategies[$alias])) {
            throw new ImplementationException("Strategy \"$alias\" was already defined for class \"" . get_class($strategy) . "\"");
        }

        $this->strategies[$alias] = $strategy;
    }

    /**
     * @param $alias
     * @return ImportStrategy
     */
    public function getStrategy($alias)
    {
        if (!isset($this->strategies[$alias])) {
            $definedStrategies = implode(", ", array_keys($this->strategies));
            throw new InvalidArgumentException("Undefined strategy \"$alias\". Currently defined are: [" . $definedStrategies . "]");
        }

        return $this->strategies[$alias];
    }
}