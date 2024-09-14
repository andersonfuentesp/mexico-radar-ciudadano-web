<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Municipio;

class ApiController extends Controller
{
    public function getEstados()
    {
        $estados = Estado::all();
        return response()->json($estados);
    }

    public function getMunicipiosByEstado($estadoId)
    {
        $municipios = Municipio::where('EstadoId', $estadoId)->get();
        return response()->json($municipios);
    }
}
