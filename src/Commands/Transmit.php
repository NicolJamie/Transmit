<?php

namespace NicolJamie\Transmit\Commands;

use Illuminate\Console\Command;
use NicolJamie\Transmit\Library;

class Transmit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transmit {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploys the application to staging';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Library $library)
    {
        if ($this->hasArgument('type') === false) {
            throw new \Exception('Please provide either \'push\' or \'fetch\'');
        }

        $type = $this->argument('type');

        $this->comment( 'Working...');

        try {
            $library->$type('staging');
        } catch (\Exception $exception) {
            $this->comment($exception->getMessage());
        }

        $this->comment('Finished!');
    }
}
