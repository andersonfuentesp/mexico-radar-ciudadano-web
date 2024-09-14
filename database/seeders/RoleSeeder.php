<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Administrador']);

        /************* Admin ************/
        Permission::create(['name' => 'admin.index', 'description' => 'Visualizar página principal'])->syncRoles([$role1]);

        //Perfil
        Permission::create(['name' => 'admin.profile', 'description' => 'Ver el perfil de usuario - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.profile.edit', 'description' => 'Editar el perfil de usuario - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.password.change', 'description' => 'Ver la vista para cambiar la contraseña - Administrativos'])
            ->syncRoles([$role1]);

        //Usuarios
        Permission::create(['name' => 'admin.user.all', 'description' => 'Gestionar usuarios - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.user.add', 'description' => 'Agregar usuario - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.user.detail', 'description' => 'Detalle de usuario - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.user.edit', 'description' => 'Editar usuarios - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.user.delete', 'description' => 'Eliminar usuarios - Administrativos'])
            ->syncRoles([$role1]);

        //Roles
        Permission::create(['name' => 'admin.role.all', 'description' => 'Ver lista de roles - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.role.add', 'description' => 'Agregar rol - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.role.edit', 'description' => 'Editar rol - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.role.delete', 'description' => 'Eliminar rol - Administrativos'])
            ->syncRoles([$role1]);

        //Roles - Assignment
        Permission::create(['name' => 'admin.assignment.all', 'description' => 'Ver lista de asignaciones - Administrativos'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.assignment.edit', 'description' => 'Editar asignación de rol a usuario - Administrativos'])
            ->syncRoles([$role1]);

        User::findOrFail(1)->roles()->sync([1]);
    }
}
