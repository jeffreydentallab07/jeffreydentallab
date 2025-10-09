<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseOrder;
use App\Models\Appointment;
use App\Models\Delivery;
use App\Models\Billing;
use App\Models\Technician;
use App\Models\Rider;
use App\Models\Clinic;
use App\Models\Dentist;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CaseOrdersExport;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ReportController extends Controller
{
    // 📊 Main Reports Dashboard
    public function index()
    {
        return view('admin.reports.index');
    }

    // 🦷 Case Orders Report (with filters)
    public function caseOrders(Request $request)
    {
        $query = CaseOrder::with(['clinic', 'dentist', 'patient', 'delivery', 'appointment']);

        // Filter by Clinic
        if ($request->clinic_id) {
            $query->where('clinic_id', $request->clinic_id);
        }

        // Filter by Status
        if ($request->status) {
            $status = $request->status;
            $query->where(function($q) use ($status) {
                $q->where('status', $status)
                  ->orWhereHas('delivery', fn($d) => $d->where('delivery_status', $status))
                  ->orWhereHas('appointment', fn($a) => $a->where('work_status', $status));
            });
        }

        // Filter by Date Range
        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59'
            ]);
        } elseif ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        } elseif ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $caseOrders = $query->orderBy('created_at', 'desc')->paginate(20);
        $clinics = Clinic::all();

        $statuses = collect([
            ...CaseOrder::pluck('status')->toArray(),
            ...Appointment::pluck('work_status')->toArray(),
            ...Delivery::pluck('delivery_status')->toArray()
        ])->unique()->filter()->values();

        if ($request->ajax()) {
            return view('admin.reports.caseorders', compact('caseOrders', 'clinics', 'statuses'))->render();
        }

        return view('admin.reports.caseorders', compact('caseOrders', 'clinics', 'statuses'));
    }
public function exportCaseOrders(Request $request, $type)
{
    // Get filters from request
    $query = CaseOrder::with(['clinic', 'dentist', 'patient', 'delivery'])
        ->orderBy('created_at', 'desc');

    if ($request->clinic_id) {
        $query->where('clinic_id', $request->clinic_id);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->from) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->to) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    $caseOrders = $query->get();
    $clinics = Clinic::all();

    // ✅ Export according to type
    if ($type === 'pdf') {
        $pdf = Pdf::loadView('admin.reports.caseorders_pdf', compact('caseOrders', 'clinics'));
        return $pdf->download('case_orders_report.pdf');
    } 
    elseif ($type === 'word') {
        $content = view('admin.reports.caseorders_word', compact('caseOrders', 'clinics'))->render();
        return response($content)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="case_orders_report.doc"');
    } 
    elseif ($type === 'excel') {
        // Simplest Excel output
        return response()->streamDownload(function () use ($caseOrders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Case Type', 'Clinic', 'Dentist', 'Patient', 'Status', 'Created At']);
            foreach ($caseOrders as $order) {
                fputcsv($handle, [
                    $order->co_id,
                    $order->case_type,
                    $order->clinic->clinic_name ?? 'N/A',
                    $order->dentist->name ?? 'N/A',
                    $order->patient->full_name ?? 'N/A',
                    $order->status,
                    $order->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        }, 'case_orders_report.csv');
    }

    abort(404);
}
    // 📅 Appointments Report
    public function appointments(Request $request)
    {
        $appointments = Appointment::with(['caseOrder.clinic', 'caseOrder.dentist', 'caseOrder.patient', 'technician'])
            ->when($request->clinic_id, fn($q) => $q->whereHas('caseOrder', fn($q2) => $q2->where('clinic_id', $request->clinic_id)))
            ->when($request->dentist_id, fn($q) => $q->whereHas('caseOrder', fn($q2) => $q2->where('dentist_id', $request->dentist_id)))
            ->when($request->technician_id, fn($q) => $q->where('technician_id', $request->technician_id))
            ->when($request->work_status, fn($q) => $q->where('work_status', $request->work_status))
            ->when($request->from, fn($q) => $q->whereDate('schedule_datetime', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('schedule_datetime', '<=', $request->to))
            ->orderBy('schedule_datetime', 'desc')
            ->paginate(20);

        $clinics = Clinic::all();
        $dentists = Dentist::when($request->clinic_id, fn($q) =>
            $q->whereHas('appointments.caseOrder', fn($q2) => $q2->where('clinic_id', $request->clinic_id))
        )->get();

        $technicians = User::where('role', 'technician')->get();
        $statuses = ['pending', 'in progress', 'finished'];

        if ($request->ajax()) {
            return view('admin.reports.appointments', compact('appointments', 'clinics', 'dentists', 'technicians', 'statuses'))->render();
        }

        return view('admin.reports.appointments', compact('appointments', 'clinics', 'dentists', 'technicians', 'statuses'));
    }

public function exportAppointments(Request $request, $type)
{
    $query = Appointment::with(['caseOrder.clinic', 'caseOrder.dentist', 'caseOrder.patient', 'technician']);

    if ($request->filled('clinic_id')) {
        $query->whereHas('caseOrder', fn($q) => $q->where('clinic_id', $request->clinic_id));
    }

    if ($request->filled('dentist_id')) {
        $query->whereHas('caseOrder', fn($q) => $q->where('dentist_id', $request->dentist_id));
    }

    if ($request->filled('technician_id')) {
        $query->where('technician_id', $request->technician_id);
    }

    if ($request->filled('work_status')) {
        $query->where('work_status', $request->work_status);
    }

    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('schedule_datetime', [$request->from, $request->to]);
    }

    $appointments = $query->orderByDesc('schedule_datetime')->get();

    switch ($type) {
        case 'pdf':
            $pdf = PDF::loadView('admin.reports.exports.appointments-pdf', compact('appointments'));
            return $pdf->download('appointments_report.pdf');

        case 'word':
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText('Appointments Report', ['bold' => true, 'size' => 16]);
            foreach ($appointments as $appt) {
                $section->addText("Appointment #{$appt->id} - {$appt->caseOrder->patient->full_name} ({$appt->work_status})");
            }
            $filePath = storage_path('app/appointments_report.docx');
            IOFactory::createWriter($phpWord, 'Word2007')->save($filePath);
            return response()->download($filePath)->deleteFileAfterSend();

        case 'excel':
            return Excel::download(new \App\Exports\AppointmentsExport($appointments), 'appointments_report.xlsx');

        default:
            abort(404);
    }
}
    public function deliveries(Request $request)
    {
        $query = Delivery::with(['appointment.caseOrder.clinic']);

        if ($request->clinic_id) {
            $query->whereHas('appointment.caseOrder', fn($q) => $q->where('clinic_id', $request->clinic_id));
        }
        if ($request->status) {
            $query->where('delivery_status', $request->status);
        }
        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [$request->from.' 00:00:00', $request->to.' 23:59:59']);
        } elseif ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        } elseif ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $deliveries = $query->orderBy('created_at', 'desc')->paginate(20);
        $clinics = Clinic::all();
        $statuses = Delivery::pluck('delivery_status')->unique()->values();

        if ($request->ajax()) {
            return view('admin.reports.deliveries', compact('deliveries', 'clinics', 'statuses'))->render();
        }

        return view('admin.reports.deliveries', compact('deliveries', 'clinics', 'statuses'));
    }
 public function exportDeliveries($type)
{
    $deliveries = Delivery::with(['appointment.caseOrder.clinic'])->latest()->get();

    switch ($type) {
        case 'pdf':
            $pdf = Pdf::loadView('admin.reports.exports.deliveries-pdf', compact('deliveries'))
                      ->setPaper('A4', 'portrait');
         
            return $pdf->download('Deliveries_Report_' . now()->format('Ymd') . '.pdf');

        case 'word':
            return response()->download(storage_path('exports/deliveries.docx'));

        case 'excel':
            return response()->download(storage_path('exports/deliveries.xlsx'));

        default:
            abort(404);
    }
}

   public function billings(Request $request)
    {
        $clinics = Clinic::all();

        $query = Billing::with('appointment.caseOrder.clinic');

        if ($request->filled('clinic_id')) {
            $query->whereHas('appointment.caseOrder', function ($q) use ($request) {
                $q->where('clinic_id', $request->clinic_id);
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $billings = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return view('admin.reports.billings', compact('billings', 'clinics'))->render();
        }

        return view('admin.reports.billings', compact('billings', 'clinics'));
    }

    public function exportBillings($type)
    {
        $billings = Billing::with('appointment.caseOrder.clinic')->latest()->get();

        switch ($type) {
            case 'pdf':
                $pdf = Pdf::loadView('admin.reports.exports.billings-pdf', compact('billings'))
                    ->setPaper('a4', 'portrait');
                return $pdf->download('billings_report_' . now()->format('Ymd_His') . '.pdf');

            case 'word':
                $html = view('admin.reports.exports.billings-pdf', compact('billings'))->render();
                $filename = 'billings_report_' . now()->format('Ymd_His') . '.doc';
                Storage::disk('public')->put($filename, $html);
                return response()->download(storage_path('app/public/' . $filename));

            case 'excel':
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', '#');
                $sheet->setCellValue('B1', 'Clinic');
                $sheet->setCellValue('C1', 'Appointment ID');
                $sheet->setCellValue('D1', 'Created At');
                $sheet->setCellValue('E1', 'Total Amount');

                $row = 2;
                foreach ($billings as $index => $billing) {
                    $sheet->setCellValue('A' . $row, $index + 1);
                    $sheet->setCellValue('B' . $row, $billing->appointment->caseOrder->clinic->clinic_name ?? 'N/A');
                    $sheet->setCellValue('C' . $row, $billing->appointment_id);
                    $sheet->setCellValue('D' . $row, $billing->created_at->format('M j, Y'));
                    $sheet->setCellValue('E' . $row, $billing->total_amount);
                    $row++;
                }

                $filename = 'billings_report_' . now()->format('Ymd_His') . '.xlsx';
                $writer = new Xlsx($spreadsheet);
                $writer->save(storage_path('app/public/' . $filename));
                return response()->download(storage_path('app/public/' . $filename));

            default:
                abort(404);
        }
    }

    public function technicians()
    {
        $technicians = User::where('role', 'technician')
            ->withCount('appointments')
            ->paginate(20);

        return view('admin.reports.technicians', compact('technicians'));
    }

public function exportTechnicians($type)
{
    $technicians = User::where('role', 'technician')
        ->withCount('appointments')
        ->get();

    $filename = 'technicians_report_' . now()->format('Ymd_His');

    switch ($type) {
        case 'pdf':
            $pdf = \PDF::loadView('admin.reports.exports.technicians-pdf', compact('technicians'));
            return $pdf->download("{$filename}.pdf");

        case 'word':
            $content = view('admin.reports.exports.technicians-word', compact('technicians'))->render();
            return response()->streamDownload(function () use ($content) {
                echo $content;
            }, "{$filename}.docx");

        case 'excel':
            return \Excel::download(new \App\Exports\TechniciansExport($technicians), "{$filename}.xlsx");

        default:
            abort(404, 'Invalid export type.');
    }
}
public function riders(Request $request)
{
    $query = Rider::riders() 
        ->withCount(['deliveries' => function($q) use ($request) {
            if ($request->clinic_id) {
                $q->whereHas('caseOrder', fn($q2) => 
                    $q2->where('clinic_id', $request->clinic_id)
                );
            }
        }]);

    $riders = $query->orderBy('name')->paginate(20);
    $clinics = Clinic::all();

    if ($request->ajax()) {
        return view('admin.reports.riders', compact('riders', 'clinics'))->render();
    }

    return view('admin.reports.riders', compact('riders', 'clinics'));
}

    public function export(Request $request, $type)
{
    $clinicId = $request->get('clinic_id');
    $selectedClinic = $clinicId ? Clinic::find($clinicId) : null;

    $riders = Rider::withCount('deliveries')
        ->when($clinicId, fn($q) => $q->where('clinic_id', $clinicId))
        ->get();

    $viewData = [
        'riders' => $riders,
        'selectedClinic' => $selectedClinic,
    ];

    $view = view('reports.exports.riders_report', $viewData);

    if ($type === 'pdf') {
        $pdf = \PDF::loadHTML($view->render());
        return $pdf->download('Riders_Report.pdf');
    } elseif ($type === 'word') {
        return response()->streamDownload(function() use ($view) {
            echo $view->render();
        }, 'Riders_Report.doc');
    } elseif ($type === 'excel') {
        return \Excel::download(new RidersExport($riders), 'Riders_Report.xlsx');
    }

    return redirect()->back();
}
public function print(Request $request)
{
    $clinicId = $request->get('clinic_id');
    $selectedClinic = $clinicId ? Clinic::find($clinicId) : null;

    $riders = Rider::withCount('deliveries')
        ->when($clinicId, fn($q) => $q->where('clinic_id', $clinicId))
        ->get();

    return view('admin.reports.print.riders_report', compact('riders', 'selectedClinic'));
}

public function exportRiders($type)
{
    $riders = User::where('role', 'rider')
        ->withCount('deliveries')
        ->orderBy('name', 'asc')
        ->get();

    $clinics = Clinic::all();

    $data = [
        'riders' => $riders,
        'clinics' => $clinics,
    ];

    switch ($type) {
        case 'pdf':
            $pdf = \PDF::loadView('admin.reports.riders-pdf', $data);
            return $pdf->download('riders_report.pdf');

        case 'word':
            $content = view('admin.reports.riders-pdf', $data)->render();
            $headers = [
                "Content-type" => "application/vnd.ms-word",
                "Content-Disposition" => "attachment;Filename=riders_report.doc",
            ];
            return response($content, 200, $headers);

        case 'excel':
            return \Excel::download(new \App\Exports\RidersExport, 'riders_report.xlsx');

        default:
            return redirect()->back()->with('error', 'Invalid export type.');
    }
}

    public function clinics(Request $request)
    {
        $query = Clinic::withCount(['caseOrders', 'appointments']);

        if ($request->filled('clinic_name')) {
            $query->where('clinic_name', 'like', '%'.$request->clinic_name.'%');
        }

        $clinics = $query->orderBy('clinic_name')->paginate(20);
        return view('admin.reports.clinics', compact('clinics'));
    }
    public function exportClinics($type)
{
    $clinics = \DB::table('tbl_clinic')
        ->select('clinic_id', 'clinic_name', 'email')
        ->orderBy('clinic_name', 'asc')
        ->get();

    switch ($type) {
        case 'pdf':
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.exports.clinics-pdf', compact('clinics'));
            return $pdf->download('clinics-report.pdf');

        case 'word':
            $headers = [
                "Content-type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "Content-Disposition" => "attachment;Filename=clinics-report.docx"
            ];
            return response()->view('admin.reports.exports.clinics-word', compact('clinics'), 200, $headers);

        case 'excel':
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ClinicsExport, 'clinics-report.xlsx');

        default:
            abort(404, 'Invalid export type');
    }
}

}
