<?php

namespace App\Console\Commands;

use App\Http\Directory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DropTableCommand extends Command
{
    protected $signature = 'drop:table {model}';

    protected $description = 'Drop database table Command description';

    public function handle(): void
    {
        $table = str($this->argument('model'))->plural()->lower()->value();

        if(!Schema::hasTable($table))
        {
            $this->components->error('table already dropped');

            return;
        }

        Schema::dropIfExists($table);

        DB::table('migrations')->where('migration','LIKE', '%' . $table . '%')->delete();

        File::delete(Directory::migrations($table));

        $this->components->info('Table dropped successfully');
    }
}
