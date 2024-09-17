<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContractedMunicipality;
use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ContractedMunicipalityController extends Controller
{
    public function __construct()
    {
        // Middleware para controlar el acceso a las rutas
        $this->middleware('can:admin.contractedMunicipality.all')->only('index');
        $this->middleware('can:admin.contractedMunicipality.add')->only('create', 'store');
        $this->middleware('can:admin.contractedMunicipality.edit')->only('edit', 'update');
        $this->middleware('can:admin.contractedMunicipality.delete')->only('destroy');

        $this->middleware('can:admin.contractedMunicipality.services')->only('services');
    }

    // Listar todos los municipios contratados
    public function index()
    {
        // Consultar los municipios contratados con sus estados y municipios utilizando joins
        $municipalities = DB::table('contracted_municipalities')
            ->leftJoin('estado', 'contracted_municipalities.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('contracted_municipalities.state_id', '=', 'municipio.EstadoId')
                    ->on('contracted_municipalities.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->select(
                'contracted_municipalities.*',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre'
            )
            ->orderByDesc('contracted_municipalities.created_at')
            ->get();

        // Retornar la vista con los datos de los municipios contratados
        return view('admin.contractedMunicipality.index', compact('municipalities'));
    }

    // Mostrar el formulario para crear un nuevo municipio contratado
    public function create()
    {
        $states = Estado::all();
        $municipalities = Municipio::all();
        return view('admin.contractedMunicipality.create', compact('states', 'municipalities'));
    }

    // Guardar un nuevo municipio contratado
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required',
            'municipality_id' => 'required',
            'contract_date' => 'required|date',
            'contact_responsible' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone1' => 'required|string|max:15',
        ]);

        $data = new ContractedMunicipality();
        $data->name = $request->name;
        $data->state_id = $request->state_id;
        $data->municipality_id = $request->municipality_id;
        $data->contract_date = Carbon::parse($request->contract_date);
        $data->contact_responsible = $request->contact_responsible;
        $data->contact_position = $request->contact_position;
        $data->contact_email = $request->contact_email;
        $data->contact_phone1 = $request->contact_phone1;
        $data->contact_phone2 = $request->contact_phone2;
        $data->description = $request->description;
        $data->url = $request->url;
        $data->contract_number = $request->contract_number;
        $data->status = $request->status ?? true;
        $data->save();

        $notification = [
            'message' => 'Municipio contratado creado con éxito',
            'alert-type' => 'success',
        ];

        return redirect()->route('admin.contractedMunicipality.all')->with($notification);
    }

    public function detail($id)
    {
        // Desencriptar el ID
        $decryptedId = decrypt($id);

        // Obtener los datos del municipio contratado
        $municipality = DB::table('contracted_municipalities')
            ->leftJoin('estado', 'contracted_municipalities.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('contracted_municipalities.state_id', '=', 'municipio.EstadoId')
                    ->on('contracted_municipalities.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->select(
                'contracted_municipalities.*',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre'
            )
            ->where('contracted_municipalities.id', $decryptedId)
            ->first();

        return view('admin.contractedMunicipality.detail', compact('municipality'));
    }

    // Mostrar el formulario para editar un municipio contratado
    public function edit($id)
    {
        $decryptedId = decrypt($id);

        $data = ContractedMunicipality::findOrFail($decryptedId);
        $estados = Estado::all();

        // Filtrar municipios por el estado relacionado con el municipio contratado
        $municipios = Municipio::where('EstadoId', $data->state_id)->get();

        // Retornar la vista con los datos
        return view('admin.contractedMunicipality.edit', compact('data', 'estados', 'municipios', 'userRoles'));
    }

    // Actualizar los datos de un municipio contratado
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|integer|exists:states,id',
            'municipality_id' => 'required|integer|exists:municipalities,id',
            'contract_date' => 'required|date',
            'contact_responsible' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone1' => 'required|string|max:15',
        ]);

        $data = ContractedMunicipality::findOrFail($id);
        $data->name = $request->name;
        $data->state_id = $request->state_id;
        $data->municipality_id = $request->municipality_id;
        $data->contract_date = Carbon::parse($request->contract_date);
        $data->contact_responsible = $request->contact_responsible;
        $data->contact_position = $request->contact_position;
        $data->contact_email = $request->contact_email;
        $data->contact_phone1 = $request->contact_phone1;
        $data->contact_phone2 = $request->contact_phone2;
        $data->description = $request->description;
        $data->url = $request->url;
        $data->contract_number = $request->contract_number;
        $data->status = $request->status ?? true;
        $data->save();

        $notification = [
            'message' => 'Municipio contratado actualizado con éxito',
            'alert-type' => 'success',
        ];

        return redirect()->route('admin.contractedMunicipality.index')->with($notification);
    }

    // Eliminar un municipio contratado
    public function destroy($id)
    {
        ContractedMunicipality::findOrFail($id)->delete();

        $notification = [
            'message' => 'Municipio contratado eliminado con éxito',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function services($id)
    {
        $decryptedId = decrypt($id);

    }
}
