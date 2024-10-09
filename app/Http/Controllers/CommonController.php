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
        $municipalities = DB::table('contracted_municipalities')
            ->leftJoin('estado', 'contracted_municipalities.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('contracted_municipalities.state_id', '=', 'municipio.EstadoId')
                    ->on('contracted_municipalities.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
            ->select(
                'contracted_municipalities.name as municipio',
                'estado.EstadoNombre as estado',
                DB::raw('COUNT(municipality_services.id) as services_count')
            )
            ->groupBy('contracted_municipalities.name', 'estado.EstadoNombre')
            ->orderByDesc('contracted_municipalities.created_at')
            ->get();

        // Preparar datos para el gráfico de barras
        $categories = $municipalities->pluck('municipio')->toArray();
        $serviceCounts = $municipalities->pluck('services_count')->toArray();

        // Datos para gráfico de barras
        $barData = json_encode([
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Cantidad de Servicios',
                    'data' => $serviceCounts,
                    'color' => '#4CAF50' // Color principal
                ]
            ]
        ]);

        // Datos para gráfico de pastel
        $pieData = json_encode([
            'series' => [
                [
                    'name' => 'Servicios',
                    'data' => $municipalities->map(function ($item) {
                        return [
                            'name' => $item->municipio,
                            'y' => $item->services_count,
                            'color' => '#' . substr(md5(rand()), 0, 6) // Colores dinámicos para cada municipio
                        ];
                    })->toArray()
                ]
            ]
        ]);

        return view('common.index', compact('barData', 'pieData'));
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
