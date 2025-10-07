<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import your controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicAuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\CaseOrderController;
use App\Http\Controllers\TechnicianController; // Ensure this is imported
use App\Http\Controllers\BillingController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\NewCaseOrderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ClinicAppointmentController;
use App\Http\Controllers\ClinicBillingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClinicSettingsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClinicDashboardController;


/*
|--------------------------------------------------------------------------
| Landing Pages
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('landing'))->name('landing');
Route::get('/lab', fn() => view('index'))->name('index');
Route::get('/clinic', fn() => view('clinic_index'))->name('clinic_index');

/*
|--------------------------------------------------------------------------
| General Authentication (Admin / Staff)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');


/*
|--------------------------------------------------------------------------
| Admin + Staff Routes (auth:web)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/rider/dashboard', [AuthController::class, 'dashboard'])->name('rider.dashboard');

    // Logout route for general auth
    Route::post('/logout', function () {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // Case Orders
    Route::resource('case-orders', CaseOrderController::class);
    Route::put('/case-orders/{id}/approve', [CaseOrderController::class, 'approve'])->name('case-orders.approve');

    // Clinics
    Route::resource('clinics', ClinicController::class);

    // Technicians
    Route::resource('technicians', TechnicianController::class); // Manages technicians as users
    
    // Appointments (Admin/Staff view of all appointments)
    Route::resource('appointments', AppointmentController::class);
    Route::put('/appointments/{appointment}/assign-technician', [AppointmentController::class, 'assignTechnician'])->name('appointments.assignTechnician');
    // The markAsFinished route for admin/staff
    Route::put('/appointments/{id}/finish', [AppointmentController::class, 'markAsFinished'])->name('appointments.markAsFinished'); 
    Route::post('/appointments/{id}/create-billing', [AppointmentController::class, 'createBilling'])->name('appointments.createBilling');

    // Materials
    Route::resource('materials', MaterialController::class);

    // Billing
    Route::get('/billing/receipt/{billing}', [BillingController::class, 'receiptModal'])->name('billing.receiptModal');
    Route::resource('billing', BillingController::class);
    Route::get('/billing/print/{billing}', [BillingController::class, 'print'])->name('billing.print');
    Route::get('/billing/receipt/{billing}/pdf', [BillingController::class, 'exportPdf'])->name('billing.exportPdf');

    // Deliveries
    Route::resource('deliveries', DeliveryController::class)->except(['show']); // No need for show method
    Route::post('deliveries/assign-rider/{delivery}', [DeliveryController::class, 'assignRider'])->name('deliveries.assignRider');
    Route::post('/deliveries/create-from-appointment/{appointment}', [DeliveryController::class, 'createFromAppointment'])->name('deliveries.createFromAppointment');

    // Riders
    Route::resource('riders', RiderController::class);

    // Profile
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Technician Specific Routes (within authenticated web group)
    |--------------------------------------------------------------------------
    */
    Route::get('/technician/dashboard', [TechnicianController::class, 'dashboard'])->name('technician.dashboard');
    Route::post('/technician/appointment/{id}/update', [TechnicianController::class, 'updateAppointment'])->name('technician.appointment.update');

    /*
    |--------------------------------------------------------------------------
    | Rider Specific Routes (within authenticated web group)
    |--------------------------------------------------------------------------
    */
   Route::prefix('rider')->name('rider.')->group(function () {
        Route::get('/dashboard', [RiderController::class, 'dashboard'])->name('dashboard');
        
        // This is the new route for updating delivery status from dropdown
        Route::put('/deliveries/{delivery}/update-status', [RiderController::class, 'updateStatus'])->name('deliveries.updateStatus');

        // Existing markDelivered route, ensure it accepts PUT
        Route::put('/deliveries/{delivery}/mark-delivered', [DeliveryController::class, 'markDelivered'])->name('deliveries.markDelivered'); 
    });
});


/*
|--------------------------------------------------------------------------
| Clinic Authentication (auth:clinic)
|--------------------------------------------------------------------------
*/
Route::prefix('clinic')->name('clinic.')->group(function () {

    // Auth
    Route::get('/signup', [ClinicAuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [ClinicAuthController::class, 'signup']);
    Route::get('/login', [ClinicAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [ClinicAuthController::class, 'login'])->name('login.post');

    // Protected Routes
    Route::middleware('auth:clinic')->group(function () {
        Route::get('/dashboard', [ClinicAuthController::class, 'index'])->name('dashboard');
        Route::post('/logout', [ClinicAuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [ClinicAuthController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [ClinicAuthController::class, 'updateProfile'])->name('profile.update');

        // Settings
        Route::get('/settings', [ClinicController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [ClinicController::class, 'updateSettings'])->name('settings.update');

        // Clinic Modules
        Route::resource('appointments', ClinicAppointmentController::class);
        Route::resource('billing', ClinicBillingController::class);
        Route::resource('new-case-orders', NewCaseOrderController::class)->names('new-case-orders');

        // Patients & Dentists (Clinic Side)
        Route::resource('patients', PatientController::class);
        Route::resource('dentists', DentistController::class);
    });
});

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/
// removed duplicate Route::get('/reports') here

Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/caseorders', [ReportController::class, 'caseOrders'])->name('reports.caseorders');
    Route::get('/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');
    Route::get('/deliveries', [ReportController::class, 'deliveries'])->name('reports.deliveries');
    Route::get('/billings', [ReportController::class, 'billings'])->name('reports.billings');
    Route::get('/technicians', [ReportController::class, 'technicians'])->name('reports.technicians');
    Route::get('/riders', [ReportController::class, 'riders'])->name('reports.riders');
    Route::get('/clinics', [ReportController::class, 'clinics'])->name('reports.clinics');
});


/*
|--------------------------------------------------------------------------
| Billing Extra Routes
|--------------------------------------------------------------------------
*/
Route::prefix('billing')->middleware(['auth'])->group(function() {
    Route::get('/', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/report', [BillingController::class, 'generateReport'])->name('billing.generateReport');
    Route::get('/{billing}', [BillingController::class, 'show'])->name('billing.show'); // For View button
});

Route::get('/push-finished-to-billing', [BillingController::class, 'pushFinishedAppointmentsToBilling']);

// Sa web.php
Route::get('/appointments/dentists/{clinic}', [ReportController::class, 'getDentistsByClinic']);




Route::middleware('auth:clinic')->group(function () {
    Route::get('/clinic/dashboard', [ClinicDashboardController::class, 'index'])->name('clinic.dashboard');
    Route::get('/clinic/live-counts', [ClinicDashboardController::class, 'liveCounts'])->name('clinic.liveCounts');
});