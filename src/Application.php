<?php declare(strict_types=1);

namespace Hbp\Import;

use Leaveyou\Console\Input;

class Application
{
    /** @var StrategyCollection */
    private $strategies;

    /** @var Input */
    private $input;

    /**
     * Application constructor.
     * @param Input $input
     * @param StrategyCollection $strategyCollection
     */
    public function __construct(Input $input, StrategyCollection $strategyCollection)
    {
        $this->strategies = $strategyCollection;
        $this->input = $input;
    }


    public function run()
    {
        $fileName = $this->input->getValue('file');
        $strategyName = $this->input->getValue('strategy');
        $verbosity = $this->input->getValue('verbose');

        $strategy = $this->strategies->getStrategy($strategyName);

    }
}