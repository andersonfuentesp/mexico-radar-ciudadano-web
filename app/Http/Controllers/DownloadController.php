<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadAPK()
    {
        $filePath = public_path('download/radar_ciudadano.apk');

        if (!file_exists($filePath)) {
            abort(404, 'El archivo no se encuentra disponible.');
        }

        return Response::download($filePath, 'radar_ciudadano.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }
}
