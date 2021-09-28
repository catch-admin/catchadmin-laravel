<?php

namespace Catcher\Commands\Tools;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InitAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:init:admin {--table=admin_users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'catch init admin';

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
        if (Schema::hasTable($this->option('table'))) {
            $password = $this->ask('Please create new password');

            $confirmPassword = $this->ask('Please confirm you password');

            while ($password <> $confirmPassword) {
                $confirmPassword = $this->ask('Please confirm you password again');
            }

            $user = DB::table($this->option('table'))
                ->orderBy('id')
                ->first();

            if ($user) {
                DB::table($this->option('table'))
                    ->where('id', $user->id)
                    ->update([
                        'password' => bcrypt($password)
                    ]);
            }
        } else {
            $this->error('Table {'.$this->option('table').'} not exist');
        }
    }
}
