<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.role.all')->only('roleAll');
        $this->middleware('can:admin.role.add')->only('roleAdd', 'roleStore');
        $this->middleware('can:admin.role.edit')->only('roleEdit', 'roleUpdate');
        $this->middleware('can:admin.role.delete')->only('roleDelete');
    }

    public function roleAll()
    {
        $roles = Role::withCount("permissions")->get();
        return view('admin.role.all', compact('roles'));
    }

    public function roleAdd()
    {
        $permissions = Permission::all();
        return view('admin.role.add', compact('permissions'));
    }

    public function roleStore(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'El nombre es requerido'
        ]);

        $role = Role::create($request->all());
        $role->permissions()->sync($request->permissions);

        $notification = [
            'message' => 'Se registró con éxito el rol y sus permisos!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.role.edit', $role->id)->with($notification);
    }

    public function roleEdit($id)
    {
        $permissions = Permission::all();
        $role = Role::find($id);
        $permisionsName = Role::findOrFail($id)->permissions()->get()->pluck('name')->toArray();
        return view('admin.role.edit', compact('permissions', 'role', 'permisionsName'));
    }

    public function roleUpdate(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'El nombre es requerido'
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        $role->permissions()->sync($request->permissions);

        $notification = [
            'message' => 'El rol se actualizó con éxito!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.role.edit', $id)->with($notification);
    }

    public function roleDelete($id)
    {

        $role = Role::findOrFail($id);
        $role->delete();

        $notification = [
            'message' => 'Rol eliminado con éxito',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
