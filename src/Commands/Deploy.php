<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use NicolJamie\Transmit\Library;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:staging';

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
        $this->comment('Deploying assets to staging');

        try {
            $library->push('staging');
        } catch (\Exception $exception) {
            $this->comment($exception->getMessage());
        }

        $this->comment('Finished deploying to staging');
    }
}
