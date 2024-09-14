<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:assignment.all')->only('assignmentAll');
        $this->middleware('can:assignment.edit')->only('assignmentEdit', 'assignmentUpdate');
    }

    public function assignmentAll()
    {
        //$users= User::latest()->get();

        // Otra forma de hacerlo: $users= User::withCount("roles")->get(); 
        $users = User::with("roles")->get();
        return view('admin.assignment.all', compact('users'));
    }

    public function assignmentEdit($id)
    {
        $data = User::findOrFail($id);
        $rolesName = User::findOrFail($id)->getRoleNames()->toArray();
        $roles = Role::all();
        return view('admin.assignment.edit', compact('data', 'roles', 'rolesName'));
    }

    public function assignmentUpdate(Request $request, $id)
    {

        User::findOrFail($id)->roles()->sync($request->roles);

        $notification = [
            'message' => 'Se asignó con éxito!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
