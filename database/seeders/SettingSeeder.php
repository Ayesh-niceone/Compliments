<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Create default settings if not exist
        Setting::firstOrCreate([], [
            'site_name' => 'Compliment System',
            'email' => 'info@example.com',
            'phone' => '+966000000000',
            'address' => 'Riyadh, Saudi Arabia',
            'logo' => null,
        ]);
    }
}
