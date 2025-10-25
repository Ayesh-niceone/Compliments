<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $departments = [
            ['name' => 'الجنادرية', 'code' => 'pm001'],
            ['name' => 'الدوادمي', 'code' => 'pm003'],
            ['name' => 'حفر الباطن', 'code' => 'pm002'],
            ['name' => 'الخرج حي العالية', 'code' => 'pm004'],
            ['name' => 'الخرج حي مشرف', 'code' => 'pm005'],
        ];

        DB::table('departments')->insert($departments);
    }
}
