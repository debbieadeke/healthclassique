<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SampleBatchController;
use App\Http\Controllers\DraftOrderController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\PharmacyController;

use App\Http\Controllers\InputController;
use App\Http\Controllers\InputBatchController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SalesCallController;
use App\Http\Controllers\Auth\ChangePasswordController;

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationsController;

use App\Http\Middleware\CheckUserSuspension;

use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TargetsController;
use App\Http\Controllers\ProdOrdersController;
use App\Http\Controllers\GPSLocationController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ManufacturingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome-v2');
});



Route::get('/test', function () {
    return view('welcome-v2');
});


Auth::routes([
    'register' => false,
    'verify' => true
]);

Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('password.change');
Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.update');

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(CheckUserSuspension::class);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/employee_performance_filter', [HomeController::class, 'employee_performance_filter'])->name('employee_performance_filter');


// added this route to store oreders
Route::post('store-production-data', [ProductionOrderController::class, 'storeProductionOrder'])->name('post-production-order');


Route::middleware(['auth'])->group(function () {
    Route::group(['prefix' => 'salescalls', 'as' => 'salescalls.'], function () {
        Route::get('/doctor', [SalesCallController::class, 'indexdoctor'])->name('list-doctor');
        Route::get('/pharmacy', [SalesCallController::class, 'indexpharmacy'])->name('list-pharmacy');
        Route::get('/', [SalesCallController::class, 'index'])->name('list');
        Route::get('/roundtable', [SalesCallController::class, 'indexroundtable'])->name('list-roundtable');
        Route::get('/cme', [SalesCallController::class, 'indexcme'])->name('list-cme');
        Route::get('/create', [SalesCallController::class, 'create'])->name('create');
        Route::get('/create-doctor', [SalesCallController::class, 'createdoctor'])->name('create-doctor');
        Route::get('/create-pharmacy', [SalesCallController::class, 'createpharmacy'])->name('create-pharmacy');
        Route::get('/get-pharmacy-data', [SalesCallController::class, 'phamacyPerformance'])->name('get-pharmacy-data');
        Route::get('/create-roundtable', [SalesCallController::class, 'createroundtable'])->name('create-roundtable');
        Route::get('/create-cme', [SalesCallController::class, 'createcme'])->name('create-cme');
        Route::get('/create-cme-clinic', [SalesCallController::class, 'createcmeclinic'])->name('create-cme-clinic');
        Route::get('/create-cme-pharmacy', [SalesCallController::class, 'createcmepharmacy'])->name('create-cme-pharmacy');
        Route::post('add', [SalesCallController::class, 'store'])->name('store');
        Route::post('add-hospital', [SalesCallController::class, 'storehospital'])->name('store-hospital');
        Route::post('add-pharmacy', [SalesCallController::class, 'storepharmacy'])->name('store-pharmacy');
        Route::post('add-roundtable', [SalesCallController::class, 'storeroundtable'])->name('store-roundtable');
        Route::post('add-cme', [SalesCallController::class, 'storecme'])->name('store-cme');
        Route::post('store-clinic-cme', [SalesCallController::class, 'storecliniccme'])->name('store-clinic-cme');
        Route::post('store-pharmacy-cme', [SalesCallController::class, 'storepharmacycme'])->name('store-pharmacy-cme');

        Route::post('continue-hospital', [SalesCallController::class, 'continuehospital'])->name('continue-hospital');
        Route::get('/{salescall}/show', [SalesCallController::class, 'show'])->name('show');
        Route::get('/{salescall}/show-hospital', [SalesCallController::class, 'showhospital'])->name('show-hospital');
        Route::get('/{salescall}/show-pharmacy', [SalesCallController::class, 'showpharmacy'])->name('show-pharmacy');
        Route::get('/{salescall}/show-roundtable', [SalesCallController::class, 'showroundtable'])->name('show-roundtable');
        Route::get('/{salescall}/show-cme', [SalesCallController::class, 'showcme'])->name('show-cme');

        Route::get('/view-prescription-audits', [ReportsController::class, 'view_prescription_audits'])->name('view-prescription-audits');
        Route::get('/view-orders-booked', [ReportsController::class, 'view_orders_booked'])->name('view-orders-booked');
        Route::get('/view-sample-slips', [ReportsController::class, 'view_sample_slips'])->name('view-sample-slips');


        Route::get('/new_pharmacy_clinic', [SalesCallController::class, 'newPharmacyClinic'])->name('new_pharmacy_clinic');
        Route::get('/admin_new_doctor', [SalesCallController::class, 'adminNewDoctors'])->name('admin_new_doctor');
        Route::get('edit_new_doctor/{id}', [SalesCallController::class, 'editNewDoctors'])->name('edit_new_doctor');
        Route::delete('destroy_new_doctor/{id}', [SalesCallController::class, 'destroyNewDoctors'])->name('destroy_new_doctor');
        Route::post('create_new_doctor', [SalesCallController::class, 'createNewDoctors'])->name('create_new_doctor');
        Route::get('/admin_new_pharmacy', [SalesCallController::class, 'adminNewPharmacyClinic'])->name('admin_new_pharmacy');
        Route::get('edit_new_pharmacy/{id}', [SalesCallController::class, 'editNewPharmacyClinic'])->name('edit_new_pharmacy');
        Route::delete('destroy_new_pharmacy/{id}', [SalesCallController::class, 'destroyNewPharmacyClinic'])->name('destroy_new_pharmacy');
        Route::post('create_new_pharmacy_clinic', [SalesCallController::class, 'createNewPharmacyClinic'])->name('create_new_pharmacy_clinic');
        Route::get('/new_doctor', [SalesCallController::class, 'newDoctor'])->name('new_doctor');
        Route::get('/view-general-uploads', [SalesCallController::class, 'overalGeneralUploads'])->name('view-general-uploads');
        Route::get('/general_uploads', [SalesCallController::class, 'generalUploads'])->name('general_uploads');
        Route::get('/pob_uploads', [SalesCallController::class, 'pobUploads'])->name('pob_uploads');
        Route::post('/new_facility', [SalesCallController::class, 'facility_store'])->name('new_facility');
        Route::post('/newDoctor', [SalesCallController::class, 'doctor_store'])->name('newDoctor');
        Route::post('/generalUploads', [SalesCallController::class, 'storeGeneralUploads'])->name('generalUploads');
        Route::post('/pobsUploads', [SalesCallController::class, 'pobsUploads'])->name('pobsUploads');

        // Speciality and Titles
        Route::get('/titles', [SalesCallController::class, 'titles'])->name('titles');
        Route::get('/speciality', [SalesCallController::class, 'speciality'])->name('speciality');
        Route::get('/create_title', [SalesCallController::class, 'create_title'])->name('create_title');
        Route::get('/create_speciality', [SalesCallController::class, 'create_speciality'])->name('create_speciality');
        Route::post('store_title', [SalesCallController::class, 'store_title'])->name('store_title');
        Route::post('store_speciality', [SalesCallController::class, 'store_speciality'])->name('store_speciality');
        Route::delete('destroy_title/{id}', [SalesCallController::class, 'destroy_title'])->name('destroy_title');
        Route::delete('destroy_speciality/{id}', [SalesCallController::class, 'destroy_speciality'])->name('destroy_speciality');
        Route::get('edit_title/{id}', [SalesCallController::class, 'edit_title'])->name('edit_title');
        Route::get('edit_speciality/{id}', [SalesCallController::class, 'edit_speciality'])->name('edit_speciality');
        Route::post('update_title/{id}', [SalesCallController::class, 'update_title'])->name('update_title');
        Route::post('update_speciality/{id}', [SalesCallController::class, 'update_speciality'])->name('update_speciality');

        // GPS map
        Route::get('gpsMap', [SalesCallController::class, 'gpsMap'])->name('gpsMap');
        Route::post('store-gps-location', [SalesCallController::class, 'store_gps'])->name('store-gps-location');

        // Record Sales
        Route::get('record-sale', [SalesCallController::class, 'record_sales'])->name('record-sale');
        Route::get('approve-reps-sale', [SalesCallController::class, 'approve_sales'])->name('approve-reps-sale');
        Route::get('edit-reps-sale/{id}', [SalesCallController::class, 'edit_sales'])->name('edit-reps-sale');
        Route::post('export_excel', [SalesCallController::class, 'export_excel'])->name('export_excel');
        Route::post('store-record-sale', [SalesCallController::class, 'store_record_sale'])->name('store-record-sale');
        Route::post('update-record-sale/{id}', [SalesCallController::class, 'update_record_sale'])->name('update-record-sale');
        Route::post('approve-new-sale/{id}', [SalesCallController::class, 'approve_new_sale'])->name('approve-new-sale');
        Route::post('destroy-new-sale/{id}', [SalesCallController::class, 'destroy_new_sale'])->name('destroy-new-sale');

        // Post Comment
        Route::post('comments/{user_id}/{sales_call_id}', [SalesCallController::class, 'sales_comment'])->name('comments');


        Route::get('/view-report', [SalesCallController::class, 'fetchReportData'])->name('view-report');
    });

    // GPS map
    Route::group(['prefix' => 'gps', 'as' => 'gps.'], function () {
        Route::get('index', [GPSLocationController::class, 'index'])->name('index');
        Route::post('store-gps-location', [GPSLocationController::class, 'store_gps'])->name('store-gps-location');
        Route::post('interval-gps-location', [GPSLocationController::class, 'interval_gps'])->name('interval-gps-location');
        Route::post('store-client-location', [GPSLocationController::class, 'store_client_gps'])->name('store-client-location');
    });


    // Notification
    Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    });


    // Incentives
    Route::group(['prefix' => 'incentive', 'as' => 'incentive.'], function () {
        Route::get('tier-products', [IncentiveController::class, 'tier_products'])->name('tier-products');
        Route::get('users-incentive', [IncentiveController::class, 'users_incentives'])->name('users-incentive');
        Route::get('userIncentive/{id}', [IncentiveController::class, 'user_incentives'])->name('userIncentive');
        Route::get('salesrep-index', [IncentiveController::class, 'salesrep_index'])->name('salesrep-index');
        Route::get('incentive-metrics', [IncentiveController::class, 'incentive_metrics'])->name('incentive-metrics');
        Route::post('store-percentage-ranges', [IncentiveController::class, 'store_percentage'])->name('store-percentage-ranges');
        Route::post('store-epimol-metrics', [IncentiveController::class, 'store_epimol_metrics'])->name('store-epimol-metrics');
        Route::delete('destroy-percentage-ranges/{id}', [IncentiveController::class, 'destroy_percentage'])->name('destroy-percentage-ranges');
        Route::delete('destroy-epimol-metrics/{id}', [IncentiveController::class, 'destroy_epimol_metrics'])->name('destroy-epimol-metrics');
        Route::get('edit-epimol-metrics/{id}', [IncentiveController::class, 'edit_epimol_metrics'])->name('edit-epimol-metrics');
        Route::post('update-epimol-metrics/{id}', [IncentiveController::class, 'update_epimol_metrics'])->name('update-epimol-metrics');
        Route::post('store-tier-product', [IncentiveController::class, 'store_tier_product'])->name('store-tier-product');

    });
    // Admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/user-report', [ReportsController::class, 'userreport'])->name('user-report');
        Route::get('/user-report-month', [ReportsController::class, 'userreportmonth'])->name('user-report-month');
    });

    Route::group(['prefix' => 'planner', 'as' => 'planner.'], function () {
        Route::get('/create-appointment', [AppointmentController::class, 'create'])->name('create-appointment');
        Route::get('/create-facility-appointment', [AppointmentController::class, 'createfacilityappointment'])->name('create-facility-appointment');
        Route::get('/create-pharmacy-appointment', [AppointmentController::class, 'createpharmacyappointment'])->name('create-pharmacy-appointment');

        Route::get('/list-appointments', [AppointmentController::class, 'list'])->name('list-appointments');
        Route::post('store-appointment', [AppointmentController::class, 'store'])->name('store-appointment');
        Route::post('update-appointment', [AppointmentController::class, 'update'])->name('update-appointment');

        Route::post('store-facility-appointment', [AppointmentController::class, 'storefacilityappointment'])->name('store-facility-appointment');

        Route::post('store-pharmacy-appointment', [AppointmentController::class, 'storepharmacyappointment'])->name('store-pharmacy-appointment');

        Route::get('/calendar', [AppointmentController::class, 'index'])->name('calendar');
        Route::get('/userCalender', [AppointmentController::class, 'userCalender'])->name('userCalender');
        Route::get('/calendar_version2', [AppointmentController::class, 'index_version2'])->name('calendar_version2');
        Route::get('userPlanner/{id}', [AppointmentController::class, 'userPlanner'])->name('userPlanner');
        Route::get('plannerInfo/{id}', [AppointmentController::class, 'plannerInfo'])->name('plannerInfo');
        Route::get('eventInfo/{id}', [AppointmentController::class, 'eventInfo'])->name('eventInfo');
        Route::get('reschedule/{id}', [AppointmentController::class, 'reschedule'])->name('reschedule');
        Route::get('create_schedule', [AppointmentController::class, 'create_schedule'])->name('create_schedule');
        Route::post('update_schedule/{id}', [AppointmentController::class, 'update_schedule'])->name('update_schedule');
        Route::get('lastCall/{id}', [AppointmentController::class, 'lastCall'])->name('lastCall');
        Route::post('appointments_ajax_update', [AppointmentController::class, 'ajaxUpdate'])->name('appointments.ajax_update');
        Route::delete('destroy_appointment/{id}', [AppointmentController::class, 'destroy_appointment'])->name('destroy_appointment');

    });

    Route::get('newphysicallogs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);


    Route::group([
        'prefix' => 'locations',
    ], function () {
        Route::get('/', [LocationsController::class, 'index'])
            ->name('locations.location.index');
        Route::get('/create', [LocationsController::class, 'create'])
            ->name('locations.location.create');
        Route::get('/show/{location}', [LocationsController::class, 'show'])
            ->name('locations.location.show');
        Route::get('/{location}/edit', [LocationsController::class, 'edit'])
            ->name('locations.location.edit');
        Route::post('/', [LocationsController::class, 'store'])
            ->name('locations.location.store');
        Route::put('location/{location}', [LocationsController::class, 'update'])
            ->name('locations.location.update');
        Route::delete('/location/{location}', [LocationsController::class, 'destroy'])
            ->name('locations.location.destroy');
    });

    Route::group([
        'prefix' => 'manage',
    ], function () {
        Route::get('/', [ClientController::class, 'index'])
            ->name('client-users.index');
        Route::get('/clients.index_two', [ClientController::class, 'index_two'])
            ->name('clients.index_two');
        Route::get('/create', [ClientController::class, 'create'])
            ->name('client-users.create');
        Route::get('/create_two', [ClientController::class, 'create_two'])
            ->name('client-users.create_two');
        Route::get('/edit_two/{id}', [ClientController::class, 'edit_two'])
            ->name('edit-users.edit_two');
        Route::post('store-clients', [ClientController::class, 'store_clients'])
            ->name('client-users.store-clients');
        Route::post('update-clients/{id}', [ClientController::class, 'update_client'])
            ->name('client-users.update-clients');
        Route::delete('destroy-clients/{id}', [ClientController::class, 'destroy_client'])
            ->name('client-users.destroy-clients');
        Route::post('update-user-clients', [ClientController::class, 'updateuserclients'])
            ->name('client-users.update');
        Route::get('/personal-client-users', [ClientController::class, 'personal_list'])
            ->name('personal-client-users.index');
        Route::post('personal-update-user-clients', [ClientController::class, 'personalupdateuserclients'])
            ->name('personal-client-users.update');
    });

    Route::group([
        'prefix' => 'managefacilities',
    ], function () {
        Route::get('/create', [FacilityController::class, 'create'])
            ->name('facility-users.create');
        Route::get('/index/{facility_type}', [FacilityController::class, 'index'])
            ->name('facility.index');
        Route::post('/store', [FacilityController::class, 'store'])
            ->name('facility-location.store');
        Route::get('/edit/{id}', [FacilityController::class, 'edit_facility'])
            ->name('facility.edit');
        Route::post('/update/{id}', [FacilityController::class, 'update_facility'])
            ->name('facility.update');
        Route::post('update-user-facilities', [FacilityController::class, 'updateuserfacilities'])
            ->name('facility-users.update');
        Route::delete('/delete/{id}', [FacilityController::class, 'destroy'])
            ->name('facility.delete');
        Route::get('/personal-facility-users/{facility_type}', [FacilityController::class, 'personal_facilities_list'])
            ->name('personal-facility-users.index');
        Route::post('personal-update-user-facilities', [FacilityController::class, 'personalupdateuserfacilities'])
            ->name('personal-facility-users.update');
        Route::get('admin_clinic', [FacilityController::class, 'admin_page'])
            ->name('managefacilities.admin_clinic');
        Route::get('/{facility_type}', [FacilityController::class, 'index'])
            ->name('facility-users.index');
        Route::get('/manage-doctor/{id}', [FacilityController::class, 'manage_doctor'])
            ->name('facility.manage-doctor');
        Route::post('/facility-doctors/{id}', [FacilityController::class, 'facility_doctors'])
            ->name('facility-doctors');
    });

    Route::group([
        'prefix' => 'managepharmacies',
    ], function () {
        Route::get('/create', [PharmacyController::class, 'create'])
            ->name('pharmacy-users.create');
        Route::post('/export_excel', [PharmacyController::class, 'export_excel'])
            ->name('pharmacy-users.export_excel');
        Route::get('/index/{facility_type}', [PharmacyController::class, 'index'])
            ->name('pharmacy.index');
        Route::post('/store', [PharmacyController::class, 'store'])
            ->name('pharmacy-location.store');
        Route::get('/edit/{id}', [PharmacyController::class, 'edit_pharmacy'])
            ->name('pharmacy.edit');
        Route::post('/update/{id}', [PharmacyController::class, 'update_pharmacy'])
            ->name('pharmacy.update');
        Route::delete('/delete/{id}', [PharmacyController::class, 'destroy'])
            ->name('pharmacy.delete');
        Route::post('update-user-clients', [PharmacyController::class, 'updateuserpharmacies'])
            ->name('pharmacy-users.update');
        Route::get('/personal-pharmacy-users/{facility_type}', [PharmacyController::class, 'personal_pharmacies_list'])
            ->name('personal-pharmacy-users.index');
        Route::post('personal-update-user-pharmacies', [PharmacyController::class, 'personalupdateuserpharmacies'])
            ->name('personal-pharmacy-users.update');
        Route::get('admin_pharmacy', [PharmacyController::class, 'admin_page'])
            ->name('managepharmacies.admin_pharmacy');
        Route::get('/{facility_type}', [PharmacyController::class, 'index'])
            ->name('pharmacy-users.index');

        Route::post('/store-selected-checkboxes', [PharmacyController::class, 'storeSelectedCheckboxes'])->name('store.selected.checkboxes');
    });






    Route::resource('brand', App\Http\Controllers\BrandController::class);



    Route::resource('category', App\Http\Controllers\CategoryController::class);


    Route::post('process', [ProductionOrderController::class, 'process'])->name('process');
    Route::get('/print/{production_order}', [ProductionOrderController::class, 'print'])->name('production-order.print');

    Route::resource('production-order', ProductionOrderController::class);
    //this route is used to CRUID the prodution phase ; meant to overide the route above
    Route::resource('prod-order', ProdOrdersController::class);

    Route::resource('phase', App\Http\Controllers\PhaseController::class);

    Route::resource('production-batch-phase', App\Http\Controllers\ProductionBatchPhaseController::class);

    Route::resource('production-batch-phase-detail', App\Http\Controllers\ProductionOrderPhaseDetailController::class);

    Route::resource('pack-size', App\Http\Controllers\PackSizeController::class);



    Route::get('/input-batch/{id}/edit', [InputBatchController::class, 'edit'])->name('input-batch.edit');
    Route::group(['prefix' => 'input-batch', 'as' => 'input-batch.'], function () {
        Route::get('/', [InputBatchController::class, 'index'])->name('index');
        Route::get('create', [InputBatchController::class, 'create'])->name('create');
        Route::get('{input-batch}/delete', [InputBatchController::class, 'destroy'])->name('destroy');
        Route::post('store', [InputBatchController::class, 'store'])->name('store');
        Route::patch('{inputbatch}/update', [InputBatchController::class, 'update'])->name('update');
    });

    Route::controller(InputController::class)->prefix('input')->as('input.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/input/{id}/edit', 'edit')->name('edit');
        Route::get('create', 'create')->name('create');
        Route::delete('{input}/delete', 'destroy')->name('destroy');
        Route::post('store', 'store')->name('store');
        Route::patch('{input}/update', 'update')->name('update');
    });

    Route::controller(SupplierController::class)->prefix('suppliers')->as('suppliers.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}/show', 'show')->name('show');
        Route::get('create', 'create')->name('create');
        Route::delete('{supplier}/delete', 'destroy')->name('destroy');
        Route::post('store', 'store')->name('store');
        Route::patch('{supplier}/update', 'update')->name('update');
    });

    Route::controller(ProductController::class)->prefix('products')->as('products.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('create', 'create')->name('create');
        Route::delete('{product}/delete', 'destroy')->name('destroy');
        Route::post('store', 'store')->name('store');
        Route::patch('{product}/update', 'update')->name('update');
    });
    Route::controller(ManufacturingController::class)->prefix('manufacturing')->as('manufacturing.')->group(function () {
        Route::get('production', 'production')->name('production');
        Route::get('chem_store', 'chem_store')->name('chem_store');
        Route::get('production_form','production_form')->name('production_form');
        Route::get('chemstore_form','chemstore_form')->name('chemstore_form');
        Route::get('manufacturing_records','manufacturing_records')->name('manufacturing_records');
        Route::get('quality_analysis_report','quality_analysis_report')->name('quality_analysis_report');

    });
//
//    Route::group(['prefix' => 'manufacturing', 'as' => 'sale.'], function () {
//        Route::get('production',[ManufacturingController::class,'production'])->name('production');
//        Route::get('chem_store', 'chem_store')->name('chem_store');
//    });

    Route::get('/report/stock-report', [InputBatchController::class, 'report_stock'])->name('report.stock');
    Route::resource('unit-of-measure', App\Http\Controllers\UnitOfMeasureController::class);

    Route::controller(DraftOrderController::class)->prefix('draft-order')->as('draft-order.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('show', 'show')->name('show');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
    });

    Route::controller(MessagesController::class)->prefix('messages')->as('messages.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('markAsRead/{threadId}', 'markAsRead')->name('markAsRead');
        Route::get('{id}', 'show')->name('show');
        Route::put('{id}', 'update')->name('update');
        Route::delete('{id}', 'delete')->name('delete');
    });

    Route::controller(SampleBatchController::class)->prefix('sample-batch')->as('sample-batch.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/request-sample', 'request_sample')->name('request-sample');
        Route::get('view-sample-request/{id}', 'view_sample_request')->name('view-sample-request');
        Route::get('view-user-sample-request/{id}', 'view_user_sample_request')->name('view-user-sample-request');
        Route::get('/approve-sample-request', 'approve_sample_request')->name('approve-sample-request');
        Route::get('/issue-sample-request', 'issue_sample_request')->name('issue-sample-request');
        Route::get('/issue-user_sample/{id}/{userId}', 'issue_user_sample')->name('issue-user_sample');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('create', 'create')->name('create');
        Route::get('approve', 'approve')->name('approve');
        Route::get('report', 'report')->name('report');
        Route::post('adminReport', 'adminReport')->name('adminReport');
        Route::post('issueUserSample/{id}', 'issue_sample_user')->name('issueUserSample');
        Route::post('adminReportFilter', 'adminReportFilter')->name('adminReportFilter');
        Route::get('invoice', 'invoice')->name('invoice');
        Route::get('issued', 'issued')->name('issued');
        Route::get('sample-balance', 'sample_balance')->name('sample-balance');
        Route::get('sample-inventory', 'sample_inventory')->name('sample-inventory');
        Route::get('user-sample-inventory', 'user_sample_inventory')->name('user-sample-inventory');
        Route::get('create-inventory', 'create_inventory')->name('create-inventory');
        Route::get('edit-sample-request', 'edit_sample_request')->name('edit-sample-request');
        Route::get('editSampleRequest/{id}/{userId}', 'editSampleRequest')->name('editSampleRequest');
        Route::get('update-inventory/{id}', 'update_inventory')->name('update-inventory');
        Route::post('stock-update-inventory/{id}', 'stock_update_inventory')->name('stock-update-inventory');
        Route::post('updateSampleRequest/{id}', 'updateSampleRequest')->name('updateSampleRequest');
        Route::delete('destroy-inventory/{id}', 'destroy_inventory')->name('destroy-inventory');
        Route::get('user-sample-balance/{id}', 'user_sample_balance')->name('user-sample-balance');
        Route::delete('{supplier}/delete', 'destroy')->name('destroy');
        Route::delete('destroySampleRequest/{id}', 'destroySampleRequest')->name('destroySampleRequest');
        Route::post('store', 'store')->name('store');
        Route::post('store_sample', 'store_sample')->name('store_sample');
        Route::post('store_new_sample', 'store_new_sample')->name('store_new_sample');
        Route::post('store-inventory', 'store_inventory')->name('store-inventory');
        Route::post('issue-samples', 'issue_samples')->name('issue-samples');
        Route::post('update', 'update')->name('update');
        Route::post('approve_samples', 'approve_samples')->name('approve_samples');
        Route::post('approve_user_sample/{id}', 'approve_user_sample')->name('approve_user_sample');
        Route::post('invoiced', 'invoiced')->name('invoiced');
        Route::post('issuance', 'issuance')->name('issuance');
        Route::get('users', 'users')->name('users');
        Route::post('user_approve', 'user_approve')->name('user_approve');
        Route::get('user_sample_report', 'user_sample_report')->name('user_sample_report');
        Route::get('edit_user_inventory/{id}', 'edit_user_inventory')->name('edit_user_inventory');
        Route::get('view_user_inventory/{id}', 'view_user_inventory')->name('view_user_inventory');
        Route::get('update_user_inventory/{id}', 'update_user_inventory')->name('update_user_inventory');
        Route::post('updateUseInventory/{id}', 'updateUseInventory')->name('updateUseInventory');
    });

    // Routes for the sales
    Route::group(['prefix' => 'sale', 'as' => 'sale.'], function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::post('create', [SaleController::class, 'create'])->name('create');
        Route::get('report', [SaleController::class, 'report'])->name('report');
        Route::get('salesrep', [SaleController::class, 'salesRep'])->name('salesrep');
        Route::get('salesfacilities/{userId}/{productCode}', [SaleController::class, 'salesFacilities'])->name('salesfacilities');
        Route::get('reportfacilities/{userId}/{productCode}', [SaleController::class, 'reportfacilities'])->name('reportfacilities');
        Route::get('quarterReportFacilities/{userId}/{productCode}', [SaleController::class, 'quarterReportFacilities'])->name('quarterReportFacilities');
        Route::get('salesitems', [SaleController::class, 'salesItems'])->name('salesitems');
        Route::get('/repfacilities/{userId}/{productCode}', [SaleController::class, 'repFacilities'])->name('repfacilities');
        Route::get('/repItems/{userId}', [SaleController::class, 'repItems'])->name('repItems');
        Route::get('/reportRepItems/{userId}', [SaleController::class, 'reportRepItems'])->name('reportRepItems');
        Route::get('userMonthlyReport', [SaleController::class, 'userMonthlyReport'])->name('userMonthlyReport');
        Route::get('userMonthlyFacilities/{userId}/{productCode}/{month}/{year}', [SaleController::class, 'userMonthlyFacilities'])->name('userMonthlyFacilities');
        Route::get('/quarterlyReport', [SaleController::class, 'quarterReport'])->name('quarter-report');
        Route::get('/quarterlyRepItems/{userId}', [SaleController::class, 'quarterlyRepItems'])->name('quarterlyRepItems');
        Route::get('/fullreport_index', [SaleController::class, 'fullreport_Index'])->name('fullreport_index');
        Route::get('/fullRepItems/{userId}', [SaleController::class, 'fullRepItems'])->name('fullRepItems');
        Route::get('fullReportfacilities/{userId}/{productCode}', [SaleController::class, 'fullReportfacilities'])->name('fullReportfacilities');
        Route::get('delete_sales', [SaleController::class, 'deleteSale'])->name('delete_sales');
        Route::get('sales_record', [SaleController::class, 'saleRecord'])->name('sales_record');
        Route::delete('delete_filtered_records/{start_date}/{end_date}', [SaleController::class, 'delete_filtered_records'])->name('delete_filtered_records');
        Route::get('/monthlyReport_index', [SaleController::class, 'montlyReport_Index'])->name('monthlyReport_index');
        Route::get('/monthlyRepItems/{userId}', [SaleController::class, 'monthlyRepItems'])->name('monthlyRepItems');
        Route::get('/monthlyReportFilter/{userId}', [SaleController::class, 'monthlyReportFilter'])->name('monthlyReportFilter');
        Route::get('/monthlyUserReportFilter', [SaleController::class, 'monthlyUserReportFilter'])->name('monthlyUserReportFilter');
        Route::get('monthlyReportfacilities/{userId}/{productCode}/{month}/{year}', [SaleController::class, 'monthlyReportfacilities'])->name('monthlyReportfacilities');
    });

    // Routes for the user
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::delete('destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('update/{id}', [UserController::class, 'update'])->name('update');

        // Profile
        Route::get('myProfile', [UserController::class, 'myProfile'])->name('myProfile');
        Route::get('edit_profile', [UserController::class, 'edit_profile'])->name('edit_profile');


        // Did find a good home for this (upload privacy policy)
        Route::get('privacy_policy', [UserController::class, 'privacy_policy'])->name('privacy_policy');
        Route::get('user_privacy_policy', [UserController::class, 'user_privacy_policy'])->name('user_privacy_policy');
        Route::post('privacy_upload', [UserController::class, 'privacy_upload'])->name('privacy_upload');
        Route::get('view_pdf/{filename}', [UserController::class, 'viewPdf'])->name('view_pdf');
        Route::delete('destroy_privacy_upload/{id}', [UserController::class, 'destroy_privacy_upload'])->name('destroy_privacy_upload');

        // Profile update
        Route::post('basic_info', [UserController::class, 'basic_info'])->name('basic_info');
        Route::post('education_info', [UserController::class, 'education_info'])->name('education_info');
        Route::post('experience_info', [UserController::class, 'experience_info'])->name('experience_info');

    });

    // Routes for targets
    Route::group(['prefix' => 'targets', 'as' => 'targets.'], function () {
        Route::get('/', [TargetsController::class, 'index'])->name('index');
        Route::get('customers', [TargetsController::class, 'customers'])->name('customers');
        Route::get('sales_rep_target', [TargetsController::class, 'sales_rep_target'])->name('sales_rep_target');
        Route::get('accumulated_targets', [TargetsController::class, 'accumulated_targets'])->name('accumulated_targets');
        Route::get('pharmacy', [TargetsController::class, 'pharmacy'])->name('pharmacy');
        Route::post('pharmacy_target', [TargetsController::class, 'pharmacyTarget'])->name('pharmacy_targets');
        Route::post('pharmacy_quarter', [TargetsController::class, 'pharmacyQuarter'])->name('pharmacy_quarter');
        Route::post('customer_target', [TargetsController::class, 'customersTarget'])->name('customer_targets');
        Route::get('set/{id}/{code}', [TargetsController::class, 'set'])->name('set');
        Route::get('user_target/{id}', [TargetsController::class, 'user_target'])->name('user_target');
        Route::get('monthly_user_target_filter', [TargetsController::class, 'monthly_user_target_filter'])->name('monthly_user_target_filter');
        Route::get('user_monthly_target/{userId}/{productCode}/{month}/{year}', [TargetsController::class, 'user_monthly_target'])->name('user_monthly_target');
        Route::get('monthlyTargets/{userId}/{productCode}/{month}/{year}', [TargetsController::class, 'monthlyTargets'])->name('monthlyTargets');
        Route::get('monthlyTargetsFilters/{userId}', [TargetsController::class, 'monthlyTargetsFilters'])->name('monthlyTargetsFilters');
        Route::get('set_clinic/{id}/{code}', [TargetsController::class, 'set_clinic'])->name('set_clinic');
        Route::get('edit/{id}/{code}', [TargetsController::class, 'edit'])->name('edit');
        Route::get('edit_clinic/{id}/{code}', [TargetsController::class, 'edit_clinic'])->name('edit_clinic');
        Route::post('store/{id}/{code}', [TargetsController::class, 'store'])->name('store');
        Route::post('store_clinic/{id}/{code}', [TargetsController::class, 'store_clinic'])->name('store_clinic');
        Route::post('admin_targets', [TargetsController::class, 'adminTargets'])->name('admin_targets');
        Route::post('admin_facilities', [TargetsController::class, 'adminFacilities'])->name('admin_facilities');
        Route::get('admin_pharmacies_targets/{id}/{user_id}', [TargetsController::class, 'adminPharmaciesTargets'])->name('admin_pharmacies_targets');
        Route::get('admin_facility_targets/{id}/{user_id}', [TargetsController::class, 'adminFacilityTargets'])->name('admin_facility_targets');
        Route::get('edit_admin/{id}/{code}/{user}/{type}', [TargetsController::class, 'edit_admin'])->name('edit_admin');
        Route::post('update_targets/{code}/{user}/{type}', [TargetsController::class, 'update_targets'])->name('update_targets');
        Route::get('admin-index', [TargetsController::class, 'adminIndex'])->name('admin-index');
        Route::post('update_pharmacy_targets/{code}', [TargetsController::class, 'update_pharmacy_targets'])->name('update_pharmacy_targets');
        Route::post('update_clinic_targets/{code}', [TargetsController::class, 'update_clinic_targets'])->name('update_clinic_targets');
    });

    //Routes for Leave management
    Route::group(['prefix' => 'leaves', 'as' => 'leaves.'], function () {
        Route::get('admin_index', [LeaveController::class, 'admin_index'])->name('admin_index');
        Route::get('user_index', [LeaveController::class, 'user_index'])->name('user_index');
        Route::get('user_edit_index/{id}', [LeaveController::class, 'user_edit_index'])->name('user_edit_index');
        Route::get('user_upload/{id}', [LeaveController::class, 'user_upload'])->name('user_upload');
        Route::get('manager_index', [LeaveController::class, 'manager_index'])->name('manager_index');
        Route::get('users_leave_days', [LeaveController::class, 'users_leave_days'])->name('users_leave_days');
        Route::get('assign_leave_days/{id}', [LeaveController::class, 'assign_leave_days'])->name('assign_leave_days');
        Route::get('leave_application', [LeaveController::class, 'leave_application'])->name('leave_application');
        Route::post('apply_leave', [LeaveController::class, 'apply_leave'])->name('apply_leave');
        Route::post('edit_leave/{id}', [LeaveController::class, 'edit_leave'])->name('edit_leave');
        Route::post('approve_leave/{id}', [LeaveController::class, 'approve_leave'])->name('approve_leave');
        Route::post('assign_user_leave/{userId}', [LeaveController::class, 'assign_user_leave'])->name('assign_user_leave');
        Route::get('show_leave/{id}', [LeaveController::class, 'show_leave'])->name('show_leave');
    });


});
