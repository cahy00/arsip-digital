<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $admin = User::create([
            'name' => 'Cahyo',
            'email' => 'cgumilang48@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);

        $role = Role::create(['name' => 'admin']);

        $admin->assignRole($role);
    }
}
