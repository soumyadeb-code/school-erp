<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SchoolAdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

// Maintenance Preview Route (public - for preview in settings)
Route::get('/maintenance-preview', function () {
    // Default settings for preview
    try {
        $settings = \App\Models\MaintenanceSettings::first();
    } catch (\Exception $e) {
        $settings = null;
    }
    
    return view('errors.maintenance', [
        'page_title' => $settings->page_title ?? 'Site Under Maintenance',
        'school_title' => $settings->school_title ?? 'School Business ERP',
        'maintenance_message' => $settings->maintenance_message ?? "We're currently performing scheduled maintenance to improve our services.",
        'email' => $settings->email ?? 'support@schoolerp.com',
        'phone' => $settings->phone ?? '+1 (555) 123-4567',
    ]);
});

// Authentication routes (with maintenance check)
Route::middleware(['maintenance'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Registration routes
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard - redirect based on role
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        }
        return redirect()->route('school-admin.dashboard');
    })->name('dashboard');

    // Super Admin Routes
    Route::prefix('super-admin')->name('super-admin.')->middleware(['role:super_admin'])->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        
        // Schools management
        Route::get('/schools', [SuperAdminController::class, 'schools'])->name('schools.index');
        Route::get('/schools/create', [SuperAdminController::class, 'createSchool'])->name('schools.create');
        Route::post('/schools', [SuperAdminController::class, 'storeSchool'])->name('schools.store');
        Route::get('/schools/{school}', [SuperAdminController::class, 'showSchool'])->name('schools.show');
        Route::get('/schools/{school}/edit', [SuperAdminController::class, 'editSchool'])->name('schools.edit');
        Route::put('/schools/{school}', [SuperAdminController::class, 'updateSchool'])->name('schools.update');
        Route::delete('/schools/{school}', [SuperAdminController::class, 'destroySchool'])->name('schools.destroy');
        
        Route::get('/reports', [SuperAdminController::class, 'reports'])->name('reports');
        Route::post('/schools/{school}/create-admin', [SuperAdminController::class, 'createAdmin'])->name('schools.create-admin');
        
        // Check school code uniqueness
        Route::get('/schools/check-code', [SuperAdminController::class, 'checkCode'])->name('schools.check-code');
        
        // Maintenance Settings (Global)
        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::put('/maintenance', [MaintenanceController::class, 'update'])->name('maintenance.update');
        Route::post('/maintenance/enable', [MaintenanceController::class, 'enable'])->name('maintenance.enable');
        Route::post('/maintenance/disable', [MaintenanceController::class, 'disable'])->name('maintenance.disable');
    });

    // School Admin Routes
    Route::prefix('')->name('school-admin.')->middleware(['role:school_admin'])->group(function () {
        Route::get('/dashboard', [SchoolAdminController::class, 'dashboard'])->name('dashboard');
        
        // Maintenance Settings (School-specific)
        Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::put('/maintenance', [MaintenanceController::class, 'update'])->name('maintenance.update');
        Route::post('/maintenance/enable', [MaintenanceController::class, 'enable'])->name('maintenance.enable');
        Route::post('/maintenance/disable', [MaintenanceController::class, 'disable'])->name('maintenance.disable');
        
        // Classes Management
        Route::get('/classes', [SchoolAdminController::class, 'classes'])->name('classes.index');
        Route::post('/classes', [SchoolAdminController::class, 'storeClass'])->name('classes.store');
        Route::get('/classes/{class}/edit', [SchoolAdminController::class, 'editClass'])->name('classes.edit');
        Route::put('/classes/{class}', [SchoolAdminController::class, 'updateClass'])->name('classes.update');
        Route::delete('/classes/{class}', [SchoolAdminController::class, 'destroyClass'])->name('classes.destroy');
        
        // Academic Years
        Route::get('/academic-years', [SchoolAdminController::class, 'academicYears'])->name('academic-years.index');
        Route::post('/academic-years', [SchoolAdminController::class, 'storeAcademicYear'])->name('academic-years.store');
        Route::post('/academic-years/{year}/toggle-lock', [SchoolAdminController::class, 'toggleYearLock'])->name('academic-years.toggle-lock');
        Route::post('/academic-years/{year}/activate', [SchoolAdminController::class, 'activateAcademicYear'])->name('academic-years.activate');
        Route::delete('/academic-years/{year}', [SchoolAdminController::class, 'destroyAcademicYear'])->name('academic-years.destroy');
        
// Fees Setup
        Route::get('/fees/admission', [SchoolAdminController::class, 'admissionFees'])->name('fees.admission');
        Route::post('/fees/admission', [SchoolAdminController::class, 'storeAdmissionFee'])->name('fees.admission.store');
        Route::get('/fees/admission/{admissionFee}/edit', [SchoolAdminController::class, 'editAdmissionFee'])->name('fees.admission.edit');
        Route::put('/fees/admission/{admissionFee}', [SchoolAdminController::class, 'updateAdmissionFee'])->name('fees.admission.update');
        Route::delete('/fees/admission/{admissionFee}', [SchoolAdminController::class, 'destroyAdmissionFee'])->name('fees.admission.destroy');
        
        Route::get('/fees/registration', [SchoolAdminController::class, 'registrationFees'])->name('fees.registration');
        Route::post('/fees/registration', [SchoolAdminController::class, 'storeRegistrationFee'])->name('fees.registration.store');
        Route::get('/fees/registration/{registrationFee}/edit', [SchoolAdminController::class, 'editRegistrationFee'])->name('fees.registration.edit');
        Route::put('/fees/registration/{registrationFee}', [SchoolAdminController::class, 'updateRegistrationFee'])->name('fees.registration.update');
        Route::delete('/fees/registration/{registrationFee}', [SchoolAdminController::class, 'destroyRegistrationFee'])->name('fees.registration.destroy');
        
Route::get('/fees/class', [SchoolAdminController::class, 'classFees'])->name('fees.class');
        Route::post('/fees/class', [SchoolAdminController::class, 'storeClassFee'])->name('fees.class.store');
        Route::get('/fees/class/{classFee}/edit', [SchoolAdminController::class, 'editClassFee'])->name('fees.class.edit');
        Route::put('/fees/class/{classFee}', [SchoolAdminController::class, 'updateClassFee'])->name('fees.class.update');
        Route::delete('/fees/class/{classFee}', [SchoolAdminController::class, 'destroyClassFee'])->name('fees.class.destroy');
        
Route::get('/fees/bus', [SchoolAdminController::class, 'busFees'])->name('fees.bus');
        Route::post('/fees/bus', [SchoolAdminController::class, 'storeBusFee'])->name('fees.bus.store');
        Route::get('/fees/bus/{busFee}/edit', [SchoolAdminController::class, 'editBusFee'])->name('fees.bus.edit');
        Route::put('/fees/bus/{busFee}', [SchoolAdminController::class, 'updateBusFee'])->name('fees.bus.update');
        Route::delete('/fees/bus/{busFee}', [SchoolAdminController::class, 'destroyBusFee'])->name('fees.bus.destroy');
        Route::post('/fees/bus/import', [SchoolAdminController::class, 'importBusFees'])->name('fees.bus.import');
Route::get('/fees/bus/export', [SchoolAdminController::class, 'exportBusFees'])->name('fees.bus.export');
        Route::get('/fees/bus/search', [SchoolAdminController::class, 'searchBusFees'])->name('fees.bus.search');
        
Route::get('/fees/bookset', [SchoolAdminController::class, 'booksetPrices'])->name('fees.bookset');
        Route::post('/fees/bookset', [SchoolAdminController::class, 'storeBooksetPrice'])->name('fees.bookset.store');
        Route::get('/fees/bookset/{booksetPrice}/edit', [SchoolAdminController::class, 'editBooksetPrice'])->name('fees.bookset.edit');
        Route::put('/fees/bookset/{booksetPrice}', [SchoolAdminController::class, 'updateBooksetPrice'])->name('fees.bookset.update');
        Route::delete('/fees/bookset/{booksetPrice}', [SchoolAdminController::class, 'destroyBooksetPrice'])->name('fees.bookset.destroy');
        
        Route::get('/fees/discount', [SchoolAdminController::class, 'discountRules'])->name('fees.discount');
        Route::post('/fees/discount', [SchoolAdminController::class, 'storeDiscountRule'])->name('fees.discount.store');
        
        // School Profile
        Route::get('/profile', [SchoolAdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [SchoolAdminController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [SchoolAdminController::class, 'updatePassword'])->name('profile.password');
        Route::get('/profile/check-code', [SchoolAdminController::class, 'checkSchoolCode'])->name('profile.check-code');
        Route::get('/profile/check-email', [SchoolAdminController::class, 'checkSchoolEmail'])->name('profile.check-email');
    });

    // Student Management Routes
    Route::prefix('students')->name('students.')->group(function () {
        // Student list page (renders the view)
        Route::get('/list', function() {
            $classes = \App\Models\SchoolClass::where('school_id', auth()->user()->school_id)
                ->where('status', 'active')
                ->orderBy('minimum_age')
                ->get();
            return view('students.index', compact('classes'));
        })->name('list');
        
        // Student search (AJAX)
        Route::get('/search', [StudentController::class, 'search'])->name('search');
        
        // Student list (AJAX for DataTables)
        Route::get('/', [StudentController::class, 'index'])->name('index');
        
        // Fee Collection - MUST be before /{student} route
        Route::get('/fee-collection', [StudentController::class, 'feeCollection'])->name('fee-collection');
        Route::post('/fee-collection/collect', [StudentController::class, 'collectFee'])->name('collect-fee');
        
        // Bill History - shows all bills/receipts
        Route::get('/bill-history', [StudentController::class, 'billHistory'])->name('bill-history');
        Route::get('/bill-history/ajax', [StudentController::class, 'billHistoryAjax'])->name('bill-history.ajax');
        
        // Admission
        Route::get('/admission', [StudentController::class, 'admission'])->name('admission');
        Route::post('/admission', [StudentController::class, 'storeStudent'])->name('admission.store');
        
        // Registration
        Route::get('/registration', [StudentController::class, 'registration'])->name('registration');
        
        Route::get('/{student}/billing', [StudentController::class, 'admissionBilling'])->name('billing');
        Route::post('/{student}/billing', [StudentController::class, 'processAdmissionBilling'])->name('billing.process');
        
        // Student operations - these come after specific routes
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
        
        // Student fee related routes
        Route::get('/{student}/fee-price-list', [StudentController::class, 'feePriceList'])->name('fee-price-list');
        Route::get('/{student}/payment-history', [StudentController::class, 'paymentHistory'])->name('payment-history');
        Route::get('/{student}/monthly-bill', [StudentController::class, 'monthlyBill'])->name('monthly-bill');
        Route::post('/{student}/monthly-bill', [StudentController::class, 'processMonthlyBill'])->name('monthly-bill.process');
        
        // Receipts
        Route::get('/receipt/{payment}', [StudentController::class, 'showReceipt'])->name('receipt');
        Route::get('/receipt-view/{receipt}', [StudentController::class, 'showReceiptById'])->name('receipt-view');
    });
});
