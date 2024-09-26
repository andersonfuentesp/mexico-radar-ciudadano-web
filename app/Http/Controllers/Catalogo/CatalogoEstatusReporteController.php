<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use App\Models\ReportStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatalogoEstatusReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.catalog.report.status')->only('reportStatus');
        $this->middleware('can:admin.catalog.report.status.add')->only('addReportStatus', 'storeReportStatus');
        $this->middleware('can:admin.catalog.report.status.delete')->only('deleteReportStatus');
    }

    public function reportStatus()
    {
        $statuses = ReportStatus::orderBy('created_at', 'desc')->paginate(10);
        return view('app.catalog.report_status', compact('statuses'));
    }

    public function addReportStatus()
    {
        return view('app.catalog.add_report_status');
    }

    public function storeReportStatus(Request $request)
    {
        $request->validate([
            'report_status_name' => 'required|string|max:100',
        ]);

        // Obtener el máximo report_status_id actual y sumar 1
        $maxId = ReportStatus::max('report_status_id');
        $nextId = $maxId + 1;

        // Crear el nuevo estatus con el ID calculado
        ReportStatus::create([
            'report_status_id' => $nextId,
            'report_status_name' => $request->report_status_name,
        ]);

        return redirect()->route('admin.catalog.report.status')->with([
            'message' => 'Estatus de reporte creado con éxito.',
            'alert-type' => 'success'
        ]);
    }

    public function deleteReportStatus($id)
    {
        try {
            // Verificar si el estatus está siendo utilizado en reportes
            $reportExists = DB::table('reports')
                ->where('report_status_id', $id)
                ->exists();

            if ($reportExists) {
                return redirect()->route('admin.catalog.report.status')->with([
                    'message' => 'No se puede eliminar el estatus porque está asociado a reportes.',
                    'alert-type' => 'error'
                ]);
            }

            // Eliminar el estatus
            ReportStatus::where('report_status_id', $id)->delete();

            return redirect()->route('admin.catalog.report.status')->with([
                'message' => 'Estatus de reporte eliminado con éxito.',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            // Registrar el error en el log
            Log::error('Error al eliminar el estatus de reporte: ' . $e->getMessage());

            return redirect()->route('admin.catalog.report.status')->with([
                'message' => 'Ocurrió un error al intentar eliminar el estatus de reporte.',
                'alert-type' => 'error'
            ]);
        }
    }
}
