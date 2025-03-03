<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Departement;
use App\Models\Employee;
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

        $departement = Departement::create([
            'name' => 'Bidang Informasi Kepegawaian',
            'slug' => 'bidang-informasi-kepegawaian'
        ]);

        $admin = User::create([
            'name' => 'Cahyo',
            'email' => 'cgumilang48@gmail.com',
            'password' => bcrypt('12345678'),
            'departement_id' => 1,
            'email_verified_at' => now(),
        ]);

        $pimpinan = User::create([
            'name' => 'pimpinan',
            'email' => 'pimpinan@gmail.com',
            'password' => bcrypt('12345678'),
            'departement_id' => 1,
            'email_verified_at' => now(),
        ]);

        $role_admin = Role::create(['name' => 'admin']);
        $role_pimpinan = Role::create(['name' => 'pimpinan']);

        $admin->assignRole($role_admin);
        $pimpinan->assignRole($role_pimpinan);



        $employee = Employee::create([
            'name' => 'Cahyo',
            'departement_id' => 1,
            'position' => 'Pengelola Data',
            'nip' => '123',
            'nomor_hp' => '123'
        ]);
    }
}
