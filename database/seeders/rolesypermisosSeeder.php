<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class rolesypermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        $role2 = Role::create(['name' => 'Automotriz']);
        $role3 = Role::create(['name' => 'Manufactura']);
        $role4 = Role::create(['name' => 'Redes']);
        $role5 = Role::create(['name' => 'LAGE']);
        $role6 = Role::create(['name' => 'Mecatronica']);
        $role7 = Role::create(['name' => 'Negocios']);
        $role8 = Role::create(['name' => 'Posgrado']);
        $role9 = Role::create(['name' => 'Sistemas']);
        $role10 = Role::create(['name' => 'Coordinacion']);
        $role11 = Role::create(['name' => 'Resumen']);
        $role12 = Role::create(['name' => 'Rector']);
        $role13 = Role::create(['name' => 'SecretarioAcademico']);
    




    }
}
