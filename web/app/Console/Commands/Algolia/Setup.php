<?php

namespace App\Console\Commands\Algolia;

use App\Facades\Algolia;
use Illuminate\Console\Command;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'algolia:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Algolia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Algolia::setup();
        return 0;
    }
}
