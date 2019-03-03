<?php declare(strict_types=1);

namespace Hbp\Import;

use Hbp\Import\Database\Database;
use Leaveyou\Console\Input;

class Application
{
    /** @var Input */
    private $input;

    /** @var Database */
    private $connection;

    /**
     * Application constructor.
     * @param Input $input
     * @param Database $connection
     */
    public function __construct(Input $input, Database $connection)
    {
        $this->input = $input;
        $this->connection = $connection;
    }

    public function run(ImportStrategy $strategy)
    {
        $verbosity = $this->input->getValue('verbose');
        $fileName = $this->input->getValue('file');

        $strategy->processFile($fileName);
    }
}
