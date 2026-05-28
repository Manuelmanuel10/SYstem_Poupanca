<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'gestor']);
        Role::firstOrCreate(['name' => 'membro']);

        // Criar Super Admin apenas se não existir
        $admin = User::firstOrCreate(
            ['email' => 'admin@poupanca.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('admin123'),
            ]
        );
        $admin->assignRole('super_admin');
    }
}
