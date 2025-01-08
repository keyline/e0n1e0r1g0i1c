<?php
use Illuminate\Support\Facades\Route;

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
// Route::get('/', function () {
//     return view('welcome');
// });
/* Front Panel */
    // before login
        Route::match(['get', 'post'], '/', 'App\Http\Controllers\FrontController@home');
        Route::match(['get', 'post'], 'page/{id}', 'App\Http\Controllers\FrontController@page');
        Route::match(['get', 'post'], '/contact-us', 'App\Http\Controllers\FrontController@contactUs');
        Route::match(['get', 'post'], 'cron-for-attendance-notification', 'App\Http\Controllers\FrontController@cron_for_attendance_notification');
    // before login
/* Front Panel */
/* Admin Panel */
    Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function(){
        Route::match(['get', 'post'], '/', 'UserController@login');
        Route::match(['get','post'],'/forgot-password', 'UserController@forgotPassword');
        Route::match(['get','post'],'/validateOtp/{id}', 'UserController@validateOtp');
        Route::match(['get','post'],'/resendOtp/{id}', 'UserController@resendOtp');
        Route::match(['get','post'],'/changePassword/{id}', 'UserController@changePassword');
        Route::group(['middleware' => ['admin']], function(){
            Route::get('dashboard', 'UserController@dashboard');
            Route::post('today-attandence-details', 'UserController@todayattandenceDetails');
            Route::post('today-not-attandence-details', 'UserController@todaynotattandenceDetails');
            Route::post('today-order-details', 'UserController@todayorderDetails');
            Route::post('today-client-details', 'UserController@todayclientDetails');
            Route::get('logout', 'UserController@logout');
            Route::get('email-logs', 'UserController@emailLogs');
            Route::match(['get','post'],'/email-logs/details/{email}', 'UserController@emailLogsDetails');
            Route::get('login-logs', 'UserController@loginLogs');
           
            /* setting */
                Route::get('settings', 'UserController@settings');
                Route::post('profile-settings', 'UserController@profile_settings');
                Route::post('general-settings', 'UserController@general_settings');
                Route::post('change-password', 'UserController@change_password');
                Route::post('email-settings', 'UserController@email_settings');
                Route::post('email-template', 'UserController@email_template');
                Route::post('sms-settings', 'UserController@sms_settings');
                Route::post('footer-settings', 'UserController@footer_settings');
                Route::post('seo-settings', 'UserController@seo_settings');
                Route::post('payment-settings', 'UserController@payment_settings');
                Route::post('signature-settings', 'UserController@signature_settings');
                Route::post('color-settings', 'UserController@color_settings');
            /* setting */
            /* access & permission */
                /* modules */
                    Route::get('modules/list', 'ModulesController@list');
                    Route::match(['get', 'post'], 'modules/add', 'ModulesController@add');
                    Route::match(['get', 'post'], 'modules/edit/{id}', 'ModulesController@edit');
                    Route::get('modules/delete/{id}', 'ModulesController@delete');
                    Route::get('modules/change-status/{id}', 'ModulesController@change_status');
                /* modules */
                /* role */
                    Route::get('role/list', 'RoleController@list');
                    Route::match(['get', 'post'], 'role/add', 'RoleController@add');
                    Route::match(['get', 'post'], 'role/edit/{id}', 'RoleController@edit');
                    Route::get('role/delete/{id}', 'RoleController@delete');
                    Route::get('role/change-status/{id}', 'RoleController@change_status');
                /* role */
                /* sub-user */
                    Route::get('sub-user/list', 'SubUserController@list');
                    Route::match(['get', 'post'], 'sub-user/add', 'SubUserController@add');
                    Route::match(['get', 'post'], 'sub-user/edit/{id}', 'SubUserController@edit');
                    Route::get('sub-user/delete/{id}', 'SubUserController@delete');
                    Route::get('sub-user/change-status/{id}', 'SubUserController@change_status');
                /* sub-user */
                /* give access */
                    Route::get('access/list', 'AccessController@list');
                    Route::match(['get', 'post'], 'access/add', 'AccessController@add');
                    Route::match(['get', 'post'], 'access/edit/{id}', 'AccessController@edit');
                    Route::get('access/delete/{id}', 'AccessController@delete');
                    Route::get('access/change-status/{id}', 'AccessController@change_status');
                /* give access */
            /* access & permission */
            /* master */
                /* product type */
                    Route::get('product-categories/list', 'ProductTypeController@list');
                    Route::match(['get', 'post'], 'product-categories/add', 'ProductTypeController@add');
                    Route::match(['get', 'post'], 'product-categories/edit/{id}', 'ProductTypeController@edit');
                    Route::get('product-categories/delete/{id}', 'ProductTypeController@delete');
                    Route::get('product-categories/change-status/{id}', 'ProductTypeController@change_status');
                /* product type */
                /* product */
                    Route::get('product/list', 'ProductController@list');
                    Route::match(['get', 'post'], 'product/add', 'ProductController@add');
                    Route::match(['get', 'post'], 'product/edit/{id}', 'ProductController@edit');
                    Route::get('product/delete/{id}', 'ProductController@delete');
                    Route::get('product/change-status/{id}', 'ProductController@change_status');
                /* product */
                /* companies */
                    Route::get('companies/list', 'CompaniesController@list');
                    Route::match(['get', 'post'], 'companies/add', 'CompaniesController@add');
                    Route::match(['get', 'post'], 'companies/edit/{id}', 'CompaniesController@edit');
                    Route::get('companies/delete/{id}', 'CompaniesController@delete');
                    Route::get('companies/change-status/{id}', 'CompaniesController@change_status');
                /* companies */
                /* client type */
                    Route::get('client-type/list', 'ClientTypeController@list');
                    Route::match(['get', 'post'], 'client-type/add', 'ClientTypeController@add');
                    Route::match(['get', 'post'], 'client-type/edit/{id}', 'ClientTypeController@edit');
                    Route::get('client-type/delete/{id}', 'ClientTypeController@delete');
                    Route::get('client-type/change-status/{id}', 'ClientTypeController@change_status');
                /* client type */
                /* employee type */
                    Route::get('employee-type/list', 'EmployeeTypeController@list');
                    Route::match(['get', 'post'], 'employee-type/add', 'EmployeeTypeController@add');
                    Route::match(['get', 'post'], 'employee-type/edit/{id}', 'EmployeeTypeController@edit');
                    Route::get('employee-type/delete/{id}', 'EmployeeTypeController@delete');
                    Route::get('employee-type/change-status/{id}', 'EmployeeTypeController@change_status');
                /* employee type */
                /* zone */
                    Route::get('zones/list', 'ZoneController@list');
                    Route::match(['get', 'post'], 'zones/add', 'ZoneController@add');
                    Route::match(['get', 'post'], 'zones/edit/{id}', 'ZoneController@edit');
                    Route::get('zones/delete/{id}', 'ZoneController@delete');
                    Route::get('zones/change-status/{id}', 'ZoneController@change_status');
                /* zone */
                /* region */
                    Route::get('region/list', 'RegionController@list');
                    Route::match(['get', 'post'], 'region/add', 'RegionController@add');
                    Route::match(['get', 'post'], 'region/edit/{id}', 'RegionController@edit');
                    Route::get('region/delete/{id}', 'RegionController@delete');
                    Route::get('region/change-status/{id}', 'RegionController@change_status');
                /* region */
                /* country */
                    Route::get('country/list', 'CountryController@list');
                    Route::match(['get', 'post'], 'country/add', 'CountryController@add');
                    Route::match(['get', 'post'], 'country/edit/{id}', 'CountryController@edit');
                    Route::get('country/delete/{id}', 'CountryController@delete');
                    Route::get('country/change-status/{id}', 'CountryController@change_status');
                /* country */
                /* unit */
                    Route::get('unit/list', 'UnitController@list');
                    Route::match(['get', 'post'], 'unit/add', 'UnitController@add');
                    Route::match(['get', 'post'], 'unit/edit/{id}', 'UnitController@edit');
                    Route::get('unit/delete/{id}', 'UnitController@delete');
                    Route::get('unit/change-status/{id}', 'UnitController@change_status');
                /* unit */
                /* size */
                    Route::get('size/list', 'SizeController@list');
                    Route::match(['get', 'post'], 'size/add', 'SizeController@add');
                    Route::match(['get', 'post'], 'size/edit/{id}', 'SizeController@edit');
                    Route::get('size/delete/{id}', 'SizeController@delete');
                    Route::get('size/change-status/{id}', 'SizeController@change_status');
                /* size */
                /* state */
                    Route::get('state/list', 'StateController@list');
                    Route::match(['get', 'post'], 'state/add', 'StateController@add');
                    Route::match(['get', 'post'], 'state/edit/{id}', 'StateController@edit');
                    Route::get('state/delete/{id}', 'StateController@delete');
                    Route::get('state/change-status/{id}', 'StateController@change_status');
                    Route::get('state/change-home-page-status/{id}', 'StateController@change_home_page_status');
                    Route::post('state/sorting-content', 'StateController@sortingContent');
                /* state */
                /* district */
                    Route::get('district/list', 'DistrictController@list');
                    Route::match(['get', 'post'], 'district/add', 'DistrictController@add');
                    Route::match(['get', 'post'], 'district/edit/{id}', 'DistrictController@edit');
                    Route::get('district/delete/{id}', 'DistrictController@delete');
                    Route::get('district/change-status/{id}', 'DistrictController@change_status');
                    Route::get('district/change-home-page-status/{id}', 'DistrictController@change_home_page_status');
                    Route::post('district/sorting-content', 'DistrictController@sortingContent');
                /* district */
                /* banners */
                    Route::get('banners/list', 'BannerController@list');
                    Route::match(['get', 'post'], 'banners/add', 'BannerController@add');
                    Route::match(['get', 'post'], 'banners/edit/{id}', 'BannerController@edit');
                    Route::get('banners/delete/{id}', 'BannerController@delete');
                    Route::get('banners/change-status/{id}', 'BannerController@change_status');
                /* banners */
                /* quotes */
                    Route::get('quotes/list', 'QuoteController@list');
                    Route::match(['get', 'post'], 'quotes/add', 'QuoteController@add');
                    Route::match(['get', 'post'], 'quotes/edit/{id}', 'QuoteController@edit');
                    Route::get('quotes/delete/{id}', 'QuoteController@delete');
                    Route::get('quotes/change-status/{id}', 'QuoteController@change_status');
                /* quotes */
                /* notification templates */
                    Route::get('notification-templates/list', 'NotificationTemplateController@list');
                    Route::match(['get', 'post'], 'notification-templates/add', 'NotificationTemplateController@add');
                    Route::match(['get', 'post'], 'notification-templates/edit/{id}', 'NotificationTemplateController@edit');
                    Route::get('notification-templates/delete/{id}', 'NotificationTemplateController@delete');
                    Route::get('notification-templates/change-status/{id}', 'NotificationTemplateController@change_status');
                /* notification templates */
            /* master */
            /* employee-department */
                Route::get('employee-details/{slug}/list', 'EmployeeDetailsController@list');
                Route::match(['get', 'post'], 'employee-details/{slug}/add', 'EmployeeDetailsController@add');
                Route::match(['get', 'post'], 'employee-details/{slug}/edit/{id}', 'EmployeeDetailsController@edit');
                Route::match(['get', 'post'], 'employee-details/{slug}/view_details/{id}', 'EmployeeDetailsController@viewDetails');                
                Route::match(['get', 'post'], 'employee-details/{slug}/view_order_details/{id}', 'OrdersController@viewOrderDetails');
                Route::get('employee-details/{slug}/delete/{id}', 'EmployeeDetailsController@delete');
                Route::get('employee-details/{slug}/change-status/{id}', 'EmployeeDetailsController@change_status'); 
                // Route::match(['get', 'post'], 'employee-details/{slug}/employeewiseorderListRecords', 'EmployeeDetailsController@employeewiseorderListRecords');                               
            /* employee-department */
            /* clients */
                Route::get('clients/{slug}/list', 'ClientController@list');
                Route::match(['get', 'post'], 'clients/{slug}/add', 'ClientController@add');
                Route::match(['get', 'post'], 'clients/{slug}/edit/{id}', 'ClientController@edit');
                Route::match(['get', 'post'], 'clients/{slug}/view_details/{id}', 'ClientController@viewDetails');                
                Route::match(['get', 'post'], 'clients/{slug}/view_order_details/{id}', 'OrdersController@viewOrderDetails');
                Route::get('clients/{slug}/delete/{id}', 'ClientController@delete');
                Route::get('clients/{slug}/change-status/{id}', 'ClientController@change_status');                
                Route::match(['get', 'post'], 'clients/{slug}/clientwiseorderListRecords', 'ClientController@clientwiseorderListRecords');
            /* clients */
            /* orders */
                Route::get('orders/{id}', 'OrdersController@list');   
                Route::match(['get', 'post'], 'orders/{slug}/view_order_details/{id}', 'OrdersController@viewOrderDetails');
                Route::post('orders/change-status', 'OrdersController@change_status');
            /* orders */
            
            /* Attandence */
                Route::match(['get', 'post'], 'attandence/list', 'AttandenceController@list');   
                Route::match(['get', 'post'], 'attandence/filter', 'AttandenceController@filter');   
                Route::match(['get', 'post'], 'attandence/view_details/{id}', 'AttandenceController@viewDetails');
                Route::match(['get', 'post'], 'attandence/updateCalendar', 'AttandenceController@generateCalendar')->name('attendance.updateCalendar');
            /* Attandence */
            /* page */
                Route::get('page/list', 'PageController@list');
                Route::match(['get', 'post'], 'page/add', 'PageController@add');
                Route::match(['get', 'post'], 'page/edit/{id}', 'PageController@edit');
                Route::get('page/delete/{id}', 'PageController@delete');
                Route::get('page/change-status/{id}', 'PageController@change_status');
            /* page */
            /* enquiries */
                Route::get('enquiry/list', 'EnquiryController@list');
                Route::get('enquiry/view-details/{id}', 'EnquiryController@details');
                Route::get('enquiry/delete/{id}', 'EnquiryController@delete');
            /* enquiries */
            /* send newsletter */
                /* subscriber */
                    Route::get('subscriber/list', 'SubscriberController@list');
                    Route::match(['get', 'post'], 'subscriber/add', 'SubscriberController@add');
                    Route::match(['get', 'post'], 'subscriber/edit/{id}', 'SubscriberController@edit');
                    Route::get('subscriber/delete/{id}', 'SubscriberController@delete');
                    Route::get('subscriber/change-status/{id}', 'SubscriberController@change_status');
                    Route::get('subscriber/send/{id}', 'SubscriberController@send');
                    Route::post('subscriber/get-user', 'SubscriberController@getUser');
                /* subscriber */
                /* newsletter */
                    Route::get('newsletter/list', 'NewsletterController@list');
                    Route::match(['get', 'post'], 'newsletter/add', 'NewsletterController@add');
                    Route::match(['get', 'post'], 'newsletter/edit/{id}', 'NewsletterController@edit');
                    Route::get('newsletter/delete/{id}', 'NewsletterController@delete');
                    Route::get('newsletter/change-status/{id}', 'NewsletterController@change_status');
                    Route::get('newsletter/send/{id}', 'NewsletterController@send');
                    Route::post('newsletter/get-user', 'NewsletterController@getUser');
                /* newsletter */
            /* send newsletter */
            /* report */
                // attendance
                Route::get('report/attendance-report', 'ReportController@attendanceReport');
                Route::get('report/attendance-report-search', 'ReportController@attendanceReportSearch');
                Route::post('report/get-attendance-details', 'ReportController@getAttendanceDetails');

                // odometer
                Route::get('report/odometer-report', 'ReportController@odometerReport');
                Route::get('report/odometer-report-search', 'ReportController@odometerReportSearch');

                // odometer details
                Route::get('report/odometer-details-report', 'ReportController@odometerDetailsReport');
                 Route::get('report/odometer-all-details-report', 'ReportController@odometerAllDetailsReport');
                Route::get('report/odometer-details-report-search', 'ReportController@odometerDetailsReportSearch');
                Route::post('report/get-odometer-details', 'ReportController@getOdometerDetails');
                Route::post('report/edit-odometer-details', 'ReportController@updateOdometerDetails');
                Route::post('report/odometer-store/{id}', 'ReportController@storeOdometerDetails');
            /* report */
        });
    });
/* Admin Panel */
/* Api */
    Route::prefix('api')->namespace('App\Http\Controllers')->group(function(){
        // Other Version 2 routes
        /* before login */
            Route::match(['get'], '/get-app-setting', 'ApiController@getAppSetting');
            Route::match(['get'], '/get-employee-type', 'ApiController@getEmployeeType');
            Route::match(['get'], '/get-country', 'ApiController@getCountry');
            Route::match(['post'], '/get-state', 'ApiController@getState');
            Route::match(['post'], '/get-district', 'ApiController@getDistrict');
            Route::match(['post'], '/get-static-pages', 'ApiController@getStaticPages');

            Route::match(['post'], '/signin', 'ApiController@signin');
            Route::match(['post'], '/signin-with-mobile', 'ApiController@signinWithMobile');
            Route::match(['post'], '/signin-validate-mobile', 'ApiController@signinValidateMobile');
            Route::match(['post'], '/forgot-password', 'ApiController@forgotPassword');
            Route::match(['post'], '/validate-otp', 'ApiController@validateOtp');
            Route::match(['post'], '/resend-otp', 'ApiController@resendOtp');
            Route::match(['post'], '/reset-password', 'ApiController@resetPassword');
        /* before login */
        /* after login */
            Route::match(['get'], '/signout', 'ApiController@signout');
            Route::match(['get'], '/dashboard', 'ApiController@dashboard');
            Route::match(['post'], '/change-password', 'ApiController@changePassword');
            Route::match(['get'], '/get-profile', 'ApiController@getProfile');
            Route::match(['get'], '/edit-profile', 'ApiController@editProfile');
            Route::match(['post'], '/upload-profile-image', 'ApiController@uploadProfileImage');
            Route::match(['post'], '/update-profile', 'ApiController@updateProfile');
            Route::match(['get'], '/delete-account', 'ApiController@deleteAccount');
            Route::match(['post'], '/get-notification', 'ApiController@getNotification');

            Route::match(['get'], '/get-client-type', 'ApiController@getClientType');
            Route::match(['post'], '/client-list', 'ApiController@clientList');
            Route::match(['post'], '/client-checkin', 'ApiController@clientCheckIn');
            Route::match(['post'], '/get-products', 'ApiController@getProducts');
            Route::match(['post'], '/place-order', 'ApiController@placeOrder');
            Route::match(['post'], '/client-wise-order-list', 'ApiController@clientWiseOrderList');
            Route::match(['post'], '/employee-wise-order-list', 'ApiController@employeeWiseOrderList');
            Route::match(['post'], '/order-details', 'ApiController@orderDetails');
            Route::match(['post'], '/note-list', 'ApiController@noteList');
            Route::match(['get'], '/get-odometer', 'ApiController@getOdoMeter');
            Route::match(['post'], '/update-odometer', 'ApiController@updateOdoMeter');
            Route::match(['post'], '/odometer-list', 'ApiController@odoMeterList');
            Route::match(['get'], '/get-attendance', 'ApiController@getAttendance');
            Route::match(['post'], '/update-attendance', 'ApiController@updateAttendance');
            Route::match(['post'], '/single-date-attendance', 'ApiController@singleDateAttendance');
            Route::match(['post'], '/get-month-attendance', 'ApiController@getMonthAttendance');
            Route::match(['get'], '/all-employee-list', 'ApiController@allEmployeeList');
            Route::match(['post'], '/check-client', 'ApiController@checkClient');
            Route::match(['get'], '/get-district', 'ApiController@getDistrict');
            Route::match(['post'], '/add-client', 'ApiController@addClient');
            Route::match(['get'], '/reports-get-designation', 'ApiController@reportsGetDesignation');
            Route::match(['post'], '/reports-employees', 'ApiController@reportsEmployees');
        /* after login */
    });
/* Api */