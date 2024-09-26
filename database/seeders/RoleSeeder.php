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

        //Catálogo - Tipo de reporte
        Permission::create(['name' => 'admin.catalog.reports.type', 'description' => 'Ver lista tipo de reportes - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.reports.type.add', 'description' => 'Agregar tipo de reporte - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.reports.type.edit', 'description' => 'Editar tipo de reporte - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.reports.type.detail', 'description' => 'Ver detalle de tipo de reporte - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.reports.type.delete', 'description' => 'Eliminar tipo de reporte - Catálogo'])
            ->syncRoles([$role1]);

        //Catálogo - Estados
        Permission::create(['name' => 'admin.catalog.states', 'description' => 'Ver lista de estados - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.state.detail', 'description' => 'Ver detalle de estado - Catálogo'])
            ->syncRoles([$role1]);

        //Catálogo - Municipios
        Permission::create(['name' => 'admin.catalog.municipalities', 'description' => 'Ver lista de municipios - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.municipality.detail', 'description' => 'Ver detalle de municipio - Catálogo'])
            ->syncRoles([$role1]);

        //Catálogo - Colonias
        Permission::create(['name' => 'admin.catalog.neighborhoods', 'description' => 'Ver lista de colonias - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.neighborhood.add', 'description' => 'Agregar colonia - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.neighborhood.edit', 'description' => 'Editar datos de colonia - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.neighborhood.delete', 'description' => 'Eliminar colonia - Catálogo'])
            ->syncRoles([$role1]);

        //Catálogo - Estatus Reporte
        Permission::create(['name' => 'admin.catalog.report.status', 'description' => 'Ver lista de reporte de estatus - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.report.status.add', 'description' => 'Agregar reporte de estatus - Catálogo'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.catalog.report.status.delete', 'description' => 'Eliminar reporte de estatus - Catálogo'])
            ->syncRoles([$role1]);

        //Municipios contratados
        Permission::create(['name' => 'admin.contractedMunicipality.all', 'description' => 'Gestionar municipios contratados'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.add', 'description' => 'Agregar municipio contratado'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.detail', 'description' => 'Ver datos de municipio contratado'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.edit', 'description' => 'Editar datos municipio contratado'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.delete', 'description' => 'Eliminar municipio contratado'])
            ->syncRoles([$role1]);

        Permission::create(['name' => 'admin.contractedMunicipality.services', 'description' => 'Servicios del municipio contratado'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.services.add', 'description' => 'Agregar servicio al municipio contratado'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.services.detail', 'description' => 'Detalle de servicio del municipio contratado'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.services.edit', 'description' => 'Editar datos del servicio del municipio contratado'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.contractedMunicipality.services.delete', 'description' => 'Eliminar servicio del municipio contratado'])
            ->syncRoles([$role1]);

        //Reporte
        Permission::create(['name' => 'admin.reports.all', 'description' => 'Ver lista de reportes ciudadano - Reportes'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.reports.add', 'description' => 'Agregar reporte ciudadano - Reportes'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.reports.edit', 'description' => 'Editar reporte ciudadano - Reportes'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.reports.detail', 'description' => 'Detalle de reporte ciudadano - Reportes'])
            ->syncRoles([$role1]);
        Permission::create(['name' => 'admin.reports.delete', 'description' => 'Eliminar reporte ciudadano - Reportes'])
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
