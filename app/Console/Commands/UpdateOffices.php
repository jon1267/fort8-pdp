<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateOffices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:offices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily update offices table';

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
        echo 'offices updated';
        return 0;
    }
}
