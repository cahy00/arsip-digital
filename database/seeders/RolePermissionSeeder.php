<?php

namespace Database\Seeders;

use App\Models\Departement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departement = Departement::create([
            'name' => 'Bidang Pengadaan Kepegawaian',
            'slug' => 'bidang-pengadaan-kepegawaian'
        ]);

        $admin = User::create([
            'name' => 'pensiun',
            'email' => 'pensiun@gmail.com',
            'password' => bcrypt('12'),
            'departement_id' => 2,
            'email_verified_at' => now(),
        ]);
    }
}
