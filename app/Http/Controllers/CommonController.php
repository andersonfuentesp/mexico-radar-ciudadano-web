<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\ReportStatus;
use App\Models\ReportType;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.index')->only('index');
        $this->middleware('can:admin.profile')->only('profile');
        $this->middleware('can:admin.profile.edit')->only('profileEdit', 'profileUpdate');
        $this->middleware('can:admin.password.change')->only('passwordChange', 'passwordUpdate');
    }

    private function sanitizeFileName($filename)
    {
        return preg_replace('/[^a-zA-Z0-9\s_\-\.]/', '_', $filename);
    }

    public function index()
    {
        return view('common.index');
    }

    public function profile()
    {
        //$id = Auth::user()->id;
        $rolesName = User::findOrFail(auth()->user()->id)->getRoleNames()->toArray();
        $rolesName = implode(" | ", $rolesName);
        $userData = User::find(auth()->user()->id);
        return view('common.profile.profile', compact('userData', 'rolesName'));
    }

    public function profileEdit()
    {
        // Otra forma => auth()->user();
        $userData = User::find(auth()->user()->id);
        return view('common.profile.edit', compact('userData'));
    }

    public function profileUpdate(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);

        // Actualizando los campos básicos
        $data->name = $request->name;
        $data->lastname = $request->lastname;
        $data->email = $request->email;
        $data->phone = $request->phone; // Asegúrate de que este campo exista en tu modelo y en la base de datos

        // Manejo de la carga de la imagen
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $this->sanitizeFileName($file->getClientOriginalName());
            $file->move(public_path('files/profile'), $filename);
            $data['image'] = 'files/profile/' . $filename;
        }

        // Guardando los cambios
        $data->save();

        // Mensaje de notificación para la vista
        $notification = [
            'message' => 'Datos actualizados con éxito!',
            'alert-type' => 'success'
        ];

        // Redirigiendo con la notificación
        return redirect()->route('admin.profile')->with($notification);
    }

    public function passwordChange()
    {
        return view('common.profile.password_change');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required|string|min:6',
            'confirm_password' => 'required|same:newpassword',
        ], [
            'oldpassword.required' => 'La contraseña antigua es requerida',
            'newpassword.required' => 'La nueva antigua es requerida',
            'confirm_password.required' => 'La confirmación de nueva contraseña es requerida'
        ]);

        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->oldpassword, $hashedPassword)) {
            $users = User::find(Auth::id());
            $users->password = bcrypt($request->newpassword);
            $users->save();

            session()->flash('message', 'Contraseña actualizada con éxito!');
            return redirect()->back();
        } else {
            session()->flash('message', 'Contraseña antigua no coincide con nuestros registros!');
            return redirect()->back();
        }
    }
}
