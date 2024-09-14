<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.user.all')->only('userAll');
        $this->middleware('can:admin.user.add')->only('userAdd', 'userStore');
        $this->middleware('can:admin.user.edit')->only('userEdit', 'userUpdate');
        $this->middleware('can:admin.user.detail')->only('userDetail');
        $this->middleware('can:admin.user.delete')->only('userDelete');

        $this->middleware('can:admin.user.report.type')->only('userReportType');
    }

    private function sanitizeFileName($filename)
    {
        return preg_replace('/[^a-zA-Z0-9\s_\-\.]/', '_', $filename);
        #return preg_replace('/[^a-zA-Z0-9\s_\-\.]/', '', $filename);
    }

    public function userAll()
    {
        $users = User::with('roles')->latest()->get();
        return view('admin.user.all', compact('users'));
    }

    public function userAdd()
    {
        $roles = Role::all();
        return view('admin.user.add', compact('roles'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            //'password' => ['required', Rules\Password::defaults()],
            'password' => [
                'required',
                'min:8',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ], [
            'password.min' => 'La contraseña debe tener más de 8 caracteres'
        ]);

        $data = new User();
        $data->name = $request->name;
        $data->lastname = $request->lastname;
        $data->username = $request->username;
        $data->password = bcrypt($request->password);
        $data->email = $request->email;
        $data->date_register = Carbon::now('America/Lima');
        $data->last_connection = Carbon::now('America/Lima');

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $this->sanitizeFileName($file->getClientOriginalName());
            $file->move(public_path('files/profile'), $filename);
            $data['image'] = 'files/profile/' . $filename;
        }
        $data->save();

        User::findOrFail($data->id)->roles()->sync([$request->rol_id]);

        $notification = [
            'message' => 'Se registró con éxito al usuario',
            'alert-type' => 'success'
        ];
        return redirect()->route('admin.user.all')->with($notification);
    }

    public function userEdit($id)
    {
        $data = User::findOrFail($id);
        return view('admin.user.edit', compact('data'));
    }

    public function userUpdate(Request $request, $id)
    {

        $data = User::findOrFail($id);
        $data->name = $request->name;
        $data->lastname = $request->lastname;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->updated_at = Carbon::now('America/Lima');

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $this->sanitizeFileName($file->getClientOriginalName());
            $file->move(public_path('files/profile'), $filename);
            $data['image'] = 'files/profile/' . $filename;
        }

        if (!empty($request->password)) {
            $data->password = bcrypt($request->password);
        }
        $data->save();

        $notification = [
            'message' => 'Se actualizaron con éxito los datos del usuario',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.user.all')->with($notification);
    }

    public function userDelete($id)
    {
        User::findOrFail($id)->delete();

        $notification = [
            'message' => 'Se eliminó con éxito al usuario',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
