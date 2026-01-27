<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('123456'),
            'role' => 'Admin',
        ]);

        $brands = [
            ['id' => 1, 'name' => 'بتروماب'],
            ['id' => 2, 'name' => 'بترولي'],
            ['id' => 3, 'name' => 'أسدال'],
        ];
        DB::table('brands')->insert($brands);

        $departments = [
            ['name' => 'الجنادرية', 'code' => 'pm001','brand_id' => 1],
            ['name' => 'الدوادمي', 'code' => 'pm003','brand_id' => 1],
            ['name' => 'حفر الباطن', 'code' => 'pm002','brand_id' => 1],
            ['name' => 'الخرج حي العالية', 'code' => 'pm004','brand_id' => 1],
            ['name' => 'الخرج حي مشرف', 'code' => 'pm005','brand_id' => 1],
            ['name' => 'رماح', 'code' => 'pm006','brand_id' => 2],
            ['name' => 'المطار', 'code' => 'PETROLY 1','brand_id' => 2],
            ['name' => 'طويق', 'code' => 'PETROLY 2','brand_id' => 3],
            ['name' => 'الثمامة التخصصي', 'code' => 'PETROLY 3','brand_id' => 3],
            ['name' => 'المهدية', 'code' => 'PETROLY 4','brand_id' => 3],
            ['name' => 'النرجس', 'code' => 'PETROLY 5','brand_id' => 3],
            ['name' => 'انس', 'code' => 'PETROLY 6','brand_id' => 3],
            ['name' => 'جابر', 'code' => 'PETROLY 7','brand_id' => 3],
            ['name' => 'حطين', 'code' => 'PETROLY 8','brand_id' => 3],
            ['name' => 'سلمان الفارسي', 'code' => 'PETROLY 9','brand_id' => 3],
        ];

        DB::table('departments')->insert($departments);
        $this->call([
        SettingSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
    ]);
    }
}
