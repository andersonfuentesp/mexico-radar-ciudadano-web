<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogoMunicipioController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.catalog.municipalities')->only('municipalities');
        $this->middleware('can:admin.catalog.municipality.detail')->only('municipalityDetail');
    }

    public function municipalities(Request $request)
    {
        $query = DB::table('municipiopol')
            ->join('municipio', function ($join) {
                $join->on('municipiopol.EstadoPolId', '=', 'municipio.EstadoId')
                    ->on('municipiopol.MunicipioPolId', '=', 'municipio.MunicipioId');
            })
            ->join('estado', 'municipio.EstadoId', '=', 'estado.EstadoId')
            ->select(
                'municipiopol.EstadoPolId',
                'estado.EstadoNombre',
                'municipiopol.MunicipioPolId',
                'municipio.MunicipioNombre',
                'municipiopol.MunicipioPolNumero',
                'municipiopol.MunicipioPolPoligono'
            );

        if ($request->has('search_estado')) {
            $searchEstado = $request->get('search_estado');
            $query->where('estado.EstadoNombre', 'LIKE', "%$searchEstado%");
        }

        if ($request->has('search_municipio')) {
            $searchMunicipio = $request->get('search_municipio');
            $query->where('municipio.MunicipioNombre', 'LIKE', "%$searchMunicipio%");
        }

        $municipalities = $query->paginate(10);

        return view('app.catalog.municipalities', compact('municipalities'));
    }

    public function municipalityDetail($estadoPolId, $municipioPolId, $municipioPolNumero)
    {
        try {
            $estadoPolId = decrypt($estadoPolId);
            $municipioPolId = decrypt($municipioPolId);
            $municipioPolNumero = decrypt($municipioPolNumero);
        } catch (\Exception $e) {
            return redirect()->route('admin.catalog.municipalities')->with('error', 'No se pudo desencriptar los datos del municipio.');
        }

        $municipiopol = DB::table('municipiopol')
            ->join('municipio', function ($join) {
                $join->on('municipiopol.EstadoPolId', '=', 'municipio.EstadoId')
                    ->on('municipiopol.MunicipioPolId', '=', 'municipio.MunicipioId');
            })
            ->join('estado', 'municipio.EstadoId', '=', 'estado.EstadoId')
            ->where('municipiopol.EstadoPolId', $estadoPolId)
            ->where('municipiopol.MunicipioPolId', $municipioPolId)
            ->where('municipiopol.MunicipioPolNumero', $municipioPolNumero)
            ->select(
                'municipiopol.*',
                'municipio.MunicipioNombre',
                'estado.EstadoNombre',
                'municipio.*'
            )
            ->first();

        if (!$municipiopol) {
            return redirect()->route('admin.catalog.municipalities')->with('error', 'Municipio no encontrado.');
        }

        $coordinates = $this->convertPolygonToLatLng($municipiopol->MunicipioPolChar);

        return view('app.catalog.municipality_detail', compact('municipiopol', 'coordinates'));
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
