<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContractedMunicipality;
use App\Models\Estado;
use App\Models\MunicipalityService;
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
        // Consultar los municipios contratados con sus estados y municipios utilizando joins y contar los servicios
        $municipalities = DB::table('contracted_municipalities')
            ->leftJoin('estado', 'contracted_municipalities.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('contracted_municipalities.state_id', '=', 'municipio.EstadoId')
                    ->on('contracted_municipalities.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
            ->select(
                'contracted_municipalities.id',
                'contracted_municipalities.name',
                'contracted_municipalities.state_id',
                'contracted_municipalities.municipality_id',
                'contracted_municipalities.contract_date',
                'contracted_municipalities.status',
                'contracted_municipalities.is_private',
                'contracted_municipalities.url',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre',
                DB::raw('COUNT(municipality_services.id) as services_count') // Contar la cantidad de servicios por municipio
            )
            ->groupBy(
                'contracted_municipalities.id',
                'contracted_municipalities.name',
                'contracted_municipalities.state_id',
                'contracted_municipalities.municipality_id',
                'contracted_municipalities.contract_date',
                'contracted_municipalities.status',
                'contracted_municipalities.is_private',
                'contracted_municipalities.url',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre'
            ) // Agrupar por todas las columnas seleccionadas
            ->orderByDesc('contracted_municipalities.created_at')
            ->get();

        // Retornar la vista con los datos de los municipios contratados
        return view('app.contractedMunicipality.index', compact('municipalities'));
    }

    // Mostrar el formulario para crear un nuevo municipio contratado
    public function create()
    {
        $states = Estado::all();
        $municipalities = Municipio::all();
        return view('app.contractedMunicipality.create', compact('states', 'municipalities'));
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
            'token' => 'nullable|string|max:255', // Validación del token
        ]);

        // Verificar si ya existe un registro con el mismo estado y municipio
        $existingMunicipality = ContractedMunicipality::where('state_id', $request->state_id)
            ->where('municipality_id', $request->municipality_id)
            ->first();

        if ($existingMunicipality) {
            $notification = [
                'message' => 'El municipio ya está registrado para este estado.',
                'alert-type' => 'info',
            ];

            return redirect()->route('admin.contractedMunicipality.add')->with($notification);
        }

        // Crear nuevo registro
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
        $data->token = $request->token; // Almacenamiento del token
        $data->contract_number = $request->contract_number;
        $data->status = $request->status ?? true;
        $data->is_private = $request->has('is_private'); // Captura del checkbox
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

        return view('app.contractedMunicipality.detail', compact('municipality'));
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
        return view('app.contractedMunicipality.edit', compact('data', 'estados', 'municipios'));
    }

    // Actualizar los datos de un municipio contratado
    public function update(Request $request, $id)
    {
        // Validaciones del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|integer|exists:estado,EstadoId', // Validación de estado (tabla 'estado')
            'municipality_id' => 'required|integer|exists:municipio,MunicipioId', // Validación de municipio (tabla 'municipio')
            'contract_date' => 'required|date',
            'contact_responsible' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone1' => 'required|string|max:15',
            'token' => 'nullable|string|max:255', // Validación del token (opcional)
        ]);

        $id = decrypt($id);

        // Buscar el municipio contratado por ID
        $data = ContractedMunicipality::findOrFail($id);

        // Verificar si ya existe otro registro con el mismo estado y municipio
        $existingMunicipality = ContractedMunicipality::where('state_id', $request->state_id)
            ->where('municipality_id', $request->municipality_id)
            ->where('id', '!=', $id) // Excluir el registro actual
            ->first();

        if ($existingMunicipality) {
            $notification = [
                'message' => 'Ya existe un registro para este estado y municipio.',
                'alert-type' => 'warning',
            ];

            return redirect()->route('admin.contractedMunicipality.edit', encrypt($id))->with($notification);
        }

        // Actualizar los datos del municipio contratado
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
        $data->token = $request->token; // Actualización del token
        $data->contract_number = $request->contract_number;
        $data->status = $request->status ?? true; // Actualizar estado del contrato (activo/inactivo)
        $data->is_private = $request->has('is_private'); // Actualizar si el municipio es privado

        // Guardar los cambios
        $data->save();

        // Notificación de éxito
        $notification = [
            'message' => 'Municipio contratado actualizado con éxito',
            'alert-type' => 'success',
        ];

        // Redirigir al listado de municipios contratados
        return redirect()->route('admin.contractedMunicipality.all')->with($notification);
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
        // Desencriptar el ID
        $decryptedId = decrypt($id);

        // Obtener los datos del municipio contratado, incluyendo el estado y el municipio
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

        // Obtener los servicios del municipio
        $services = DB::table('municipality_services')
            ->where('municipality_id', $decryptedId)
            ->get();

        // Retornar la vista con los datos del municipio y los servicios
        return view('app.contractedMunicipality.services.index', compact('municipality', 'services'));
    }

    public function servicesAdd($id)
    {
        // Desencriptar el ID
        $decryptedId = decrypt($id);

        // Obtener los datos del municipio
        $municipality = ContractedMunicipality::findOrFail($decryptedId);

        return view('app.contractedMunicipality.services.add', compact('municipality'));
    }

    public function servicesStore(Request $request, $id)
    {
        // Desencriptar el ID
        $decryptedId = decrypt($id);

        // Obtener los datos del municipio
        $municipality = ContractedMunicipality::findOrFail($decryptedId);

        // Validar el request
        $request->validate([
            'service_name' => 'required|string|max:255',
            'api_url' => 'required|string',  // Solo validamos el segmento de la URL
            'method' => 'required|in:GET,POST,PUT,DELETE',
            'response_format' => 'required|in:JSON,XML,CSV,Other',
            'status' => 'boolean',
        ]);

        // Guardar el nuevo servicio
        $service = new MunicipalityService();
        $service->municipality_id = $decryptedId;
        $service->service_name = $request->service_name;
        $service->api_url = $request->api_url;  // Guardar solo el segmento adicional
        $service->method = $request->method;
        $service->response_format = $request->response_format;
        $service->description = $request->description;
        $service->status = $request->status ?? true;
        $service->save();

        // Notificación
        $notification = [
            'message' => 'Servicio agregado con éxito',
            'alert-type' => 'success',
        ];

        return redirect()->route('admin.contractedMunicipality.services', encrypt($decryptedId))
            ->with($notification);
    }

    public function servicesDetail($id)
    {
        $decryptedId = decrypt($id);

        // Obtener el servicio
        $service = MunicipalityService::findOrFail($decryptedId);

        // Obtener el municipio al que pertenece el servicio
        $municipality = ContractedMunicipality::findOrFail($service->municipality_id);

        return view('app.contractedMunicipality.services.detail', compact('service', 'municipality'));
    }

    public function servicesEdit($municipalityId, $serviceId)
    {
        $decryptedMunicipalityId = decrypt($municipalityId);
        $decryptedServiceId = decrypt($serviceId);

        $service = MunicipalityService::findOrFail($decryptedServiceId);
        $municipality = ContractedMunicipality::findOrFail($decryptedMunicipalityId);

        return view('app.contractedMunicipality.services.edit', compact('service', 'municipality'));
    }

    public function servicesUpdate(Request $request, $municipalityId, $serviceId)
    {
        // Validar el request
        $request->validate([
            'service_name' => 'required|string|max:255',
            'api_url' => 'required|string',  // Validamos el segmento adicional de la URL
            'method' => 'required|string|in:GET,POST,PUT,DELETE,PATCH', // Validación del método HTTP
            'response_format' => 'required|in:JSON,XML,CSV,Other',
            'status' => 'boolean',
        ]);

        // Desencriptar IDs
        $decryptedMunicipalityId = decrypt($municipalityId);
        $decryptedServiceId = decrypt($serviceId);

        // Actualizar el servicio
        $service = MunicipalityService::findOrFail($decryptedServiceId);
        $service->service_name = $request->service_name;
        $service->api_url = $request->api_url;  // Aquí se actualiza solo el segmento de la URL
        $service->method = $request->method; // Actualizar el método HTTP
        $service->response_format = $request->response_format;
        $service->description = $request->description;
        $service->status = $request->status ?? true;
        $service->save();

        // Notificación
        $notification = [
            'message' => 'Servicio actualizado con éxito',
            'alert-type' => 'success',
        ];

        return redirect()->route('admin.contractedMunicipality.services', encrypt($decryptedMunicipalityId))
            ->with($notification);
    }

    public function servicesDestroy($municipalityId, $serviceId)
    {
        $decryptedMunicipalityId = decrypt($municipalityId);
        $decryptedServiceId = decrypt($serviceId);

        // Eliminar el servicio
        MunicipalityService::findOrFail($decryptedServiceId)->delete();

        return redirect()->route('admin.contractedMunicipality.services', encrypt($decryptedMunicipalityId))
            ->with('message', 'Servicio eliminado con éxito')
            ->with('alert-type', 'success');
    }
}
