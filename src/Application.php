<?php declare(strict_types=1);

namespace Hbp\Import;

class Application
{
    /** @var StrategyCollection */
    private $strategyCollection;

    /**
     * Application constructor.
     * @param StrategyCollection $strategyCollection
     */
    public function __construct(StrategyCollection $strategyCollection)
    {
        $this->strategyCollection = $strategyCollection;
    }

    public function run()
    {
        //
    }
}