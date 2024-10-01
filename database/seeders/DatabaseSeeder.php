<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Path to the SQL file
        $sqlPath = database_path('seeders/neurohubdb.sql');

        // Read the SQL file content
        $sql = File::get($sqlPath);

        // Execute the SQL
        DB::unprepared($sql);

        $this->command->info('Database seeded from neurohubdb.sql!');
    }
}
