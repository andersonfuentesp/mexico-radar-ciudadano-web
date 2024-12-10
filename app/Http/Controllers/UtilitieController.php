<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Models\Municipio;
use App\Models\Report;
use App\Models\ReportHandled;
use App\Models\Response;
use Illuminate\Http\Request;

class UtilitieController extends Controller
{
    public function getMunicipiosByEstado($estadoId)
    {
        $municipios = Municipio::where('EstadoId', $estadoId)
            ->orderBy('MunicipioNombre', 'asc')
            ->get();
        return response()->json($municipios);
    }
}
