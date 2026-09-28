<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            FoundationSeeder::class,
            AccountSeeder::class,
            ShopCatalogSeeder::class,
        ]);
    }
}
