<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EstadoPol;

class CatalogoEstadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.catalog.states')->only('states');
        $this->middleware('can:admin.catalog.state.detail')->only('stateDetail');
    }

    public function states(Request $request)
    {
        $query = EstadoPol::with(['estado', 'estado.municipios'])
            ->select('estadopol.*');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('estado', function ($q) use ($search) {
                $q->where('EstadoNombre', 'LIKE', "%$search%");
            });
        }

        $states = $query->paginate(10);

        return view('app.catalog.states', compact('states'));
    }

    public function stateDetail($estadoPolId, $estadoPolNumero)
    {
        try {
            $estadoPolId = decrypt($estadoPolId);
            $estadoPolNumero = decrypt($estadoPolNumero);
        } catch (\Exception $e) {
            // Manejo del error de desencriptación
            return redirect()->route('admin.catalog.states')->with('error', 'No se pudo desencriptar los datos del estado.');
        }

        $estadopol = EstadoPol::where('EstadoPolId', $estadoPolId)
            ->where('EstadoPolNumero', $estadoPolNumero)
            ->firstOrFail();
        $state = $estadopol->estado;

        //Log::info('Polígono encontrado: ', ['Polígono' => $estadopol]);
        //Log::info('Estado asociado encontrado: ', ['Estado' => $state]);

        // Convertir los datos del polígono en un formato adecuado para Google Maps
        $coordinates = $this->convertPolygonToLatLng($estadopol->EstadoPolChar);

        // Pasar los datos a la vista
        return view('app.catalog.state_detail', compact('state', 'coordinates'));
    }

    private function convertPolygonToLatLng($polygon)
    {
        $coordinates = [];
        $points = explode(',', trim(str_replace(['POLYGON((', '))'], '', $polygon)));
        foreach ($points as $point) {
            list($lng, $lat) = explode(' ', trim($point));
            $coordinates[] = ['lat' => (float) $lat, 'lng' => (float) $lng];
        }
        return $coordinates;
    }
}
