<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckWeatherCache extends Command
{
    protected $signature = 'check:weather-cache';
    protected $description = 'Check weather cache tables and their contents';

    public function handle()
    {
        $tables = ['weather_cache', 'weather_caches'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("Table {$table} exists");
                $count = DB::table($table)->count();
                $this->info("Records in {$table}: {$count}");
                
                if ($count > 0) {
                    $records = DB::table($table)->get();
                    $this->table(['id', 'location', 'expires_at'], 
                        $records->map(fn($record) => [(string)$record->id, $record->location, $record->expires_at]));
                }
            } else {
                $this->error("Table {$table} does not exist");
            }
        }
    }
}

