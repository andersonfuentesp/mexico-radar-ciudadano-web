<?php

namespace App\Http\Controllers\Catalogo;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReportType;

class CatalogoTipoReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.catalog.reports.type')->only('reportsType');
        $this->middleware('can:admin.catalog.reports.type.add')->only('addReportType', 'storeReportType');
        $this->middleware('can:admin.catalog.reports.type.edit')->only('editReportType', 'updateReportType');
        $this->middleware('can:admin.catalog.reports.type.detail')->only('detailReportType');
        $this->middleware('can:admin.catalog.reports.type.delete')->only('deleteReportType');
    }

    public function reportsType()
    {
        $reportTypes = ReportType::paginate(10);
        return view('app.catalog.reports_type', compact('reportTypes'));
    }

    public function addReportType()
    {
        return view('app.catalog.add_report_type');
    }

    public function storeReportType(Request $request)
    {
        $request->validate([
            'ReportTypeName' => 'required|string|max:100',
            'ReportTypeOrderPriority' => 'required|integer',
            'ReportTypePinReported' => 'nullable|image',
            'ReportTypePinInProcess' => 'nullable|image',
            'ReportTypePinFinished' => 'nullable|image',
            'ReportTypeImage' => 'nullable|image',
            'ReportTypeIsActive' => 'required|boolean',
            'ReportTypeRegistrationDate' => 'required|date',
        ]);

        $reportType = new ReportType();
        $reportType->ReportTypeName = $request->ReportTypeName;
        $reportType->ReportTypeOrderPriority = $request->ReportTypeOrderPriority;
        $reportType->ReportTypeIsActive = $request->ReportTypeIsActive;
        $reportType->ReportTypeRegistrationDate = $request->ReportTypeRegistrationDate;

        if ($request->hasFile('ReportTypePinReported')) {
            $reportType->ReportTypePinReported = ImageHelper::storeImage($request->file('ReportTypePinReported'), 'report_type_pins');
        }
        if ($request->hasFile('ReportTypePinInProcess')) {
            $reportType->ReportTypePinInProcess = ImageHelper::storeImage($request->file('ReportTypePinInProcess'), 'report_type_pins');
        }
        if ($request->hasFile('ReportTypePinFinished')) {
            $reportType->ReportTypePinFinished = ImageHelper::storeImage($request->file('ReportTypePinFinished'), 'report_type_pins');
        }
        if ($request->hasFile('ReportTypeImage')) {
            $reportType->ReportTypeImage = ImageHelper::storeImage($request->file('ReportTypeImage'), 'report_type_images');
        }

        $reportType->save();

        $notification = [
            'message' => 'Tipo de reporte creado con éxito',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.catalog.reports.type')->with($notification);
    }

    public function editReportType($id)
    {
        try {
            $id = decrypt($id);
        } catch (\Exception $e) {
            // Manejo del error de desencriptación
            abort(403, 'No se puede desencriptar el ID proporcionado.');
        }
        $reportType = ReportType::findOrFail($id);
        return view('app.catalog.edit_report_type', compact('reportType'));
    }

    public function updateReportType(Request $request, $id)
    {
        $request->validate([
            'ReportTypeName' => 'required|string|max:100',
            'ReportTypeOrderPriority' => 'required|integer',
            'ReportTypePinReported' => 'nullable|image',
            'ReportTypePinInProcess' => 'nullable|image',
            'ReportTypePinFinished' => 'nullable|image',
            'ReportTypeImage' => 'nullable|image',
            'ReportTypeIsActive' => 'required|boolean',
            'ReportTypeRegistrationDate' => 'required|date',
        ]);

        $reportType = ReportType::findOrFail($id);
        $reportType->ReportTypeName = $request->ReportTypeName;
        $reportType->ReportTypeOrderPriority = $request->ReportTypeOrderPriority;
        $reportType->ReportTypeIsActive = $request->ReportTypeIsActive;
        $reportType->ReportTypeRegistrationDate = $request->ReportTypeRegistrationDate;

        if ($request->hasFile('ReportTypePinReported')) {
            $reportType->ReportTypePinReported = ImageHelper::storeImage($request->file('ReportTypePinReported'), 'report_type_pins');
        }
        if ($request->hasFile('ReportTypePinInProcess')) {
            $reportType->ReportTypePinInProcess = ImageHelper::storeImage($request->file('ReportTypePinInProcess'), 'report_type_pins');
        }
        if ($request->hasFile('ReportTypePinFinished')) {
            $reportType->ReportTypePinFinished = ImageHelper::storeImage($request->file('ReportTypePinFinished'), 'report_type_pins');
        }
        if ($request->hasFile('ReportTypeImage')) {
            $reportType->ReportTypeImage = ImageHelper::storeImage($request->file('ReportTypeImage'), 'report_type_images');
        }

        $reportType->save();

        $notification = [
            'message' => 'Datos actualizados con éxito',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.catalog.reports.type')->with($notification);
    }

    public function detailReportType($id)
    {
        try {
            $id = decrypt($id);
        } catch (\Exception $e) {
            // Manejo del error de desencriptación
            abort(403, 'No se puede desencriptar el ID proporcionado.');
        }
        $reportType = ReportType::findOrFail($id);
        return view('app.catalog.detail_report_type', compact('reportType'));
    }

    public function deleteReportType($id)
    {
        try {
            $id = decrypt($id);
        } catch (\Exception $e) {
            // Manejo del error de desencriptación
            abort(403, 'No se puede desencriptar el ID proporcionado.');
        }
        $reportType = ReportType::findOrFail($id);
        $reportType->delete();

        $notification = [
            'message' => 'Eliminado con éxito',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.catalog.reports.type')->with($notification);
    }
}
