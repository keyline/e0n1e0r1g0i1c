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
            /* setting */
            /* access & permission */
                /* module */
                    Route::get('module/list', 'ModuleController@list');
                    Route::match(['get', 'post'], 'module/add', 'ModuleController@add');
                    Route::match(['get', 'post'], 'module/edit/{id}', 'ModuleController@edit');
                    Route::get('module/delete/{id}', 'ModuleController@delete');
                    Route::get('module/change-status/{id}', 'ModuleController@change_status');
                /* module */
                /* sub users */
                    Route::get('sub-user/list', 'SubUserController@list');
                    Route::match(['get', 'post'], 'sub-user/add', 'SubUserController@add');
                    Route::match(['get', 'post'], 'sub-user/edit/{id}', 'SubUserController@edit');
                    Route::get('sub-user/delete/{id}', 'SubUserController@delete');
                    Route::get('sub-user/change-status/{id}', 'SubUserController@change_status');
                /* sub users */
                /* give access */
                    Route::get('access/list', 'AccessController@list');
                    Route::match(['get', 'post'], 'access/add', 'AccessController@add');
                    Route::match(['get', 'post'], 'access/edit/{id}', 'AccessController@edit');
                    Route::get('access/delete/{id}', 'AccessController@delete');
                    Route::get('access/change-status/{id}', 'AccessController@change_status');
                /* give access */
            /* access & permission */
            /* master */
                /* parent category */
                    Route::get('parent-category/list', 'ParentCategoryController@list');
                    Route::match(['get', 'post'], 'parent-category/add', 'ParentCategoryController@add');
                    Route::match(['get', 'post'], 'parent-category/edit/{id}', 'ParentCategoryController@edit');
                    Route::get('parent-category/delete/{id}', 'ParentCategoryController@delete');
                    Route::get('parent-category/change-status/{id}', 'ParentCategoryController@change_status');
                    Route::get('parent-category/change-feature/{id}', 'ParentCategoryController@change_feature');
                /* parent category */
                /* sub category */
                    Route::get('sub-category/list', 'SubCategoryController@list');
                    Route::match(['get', 'post'], 'sub-category/add', 'SubCategoryController@add');
                    Route::match(['get', 'post'], 'sub-category/edit/{id}', 'SubCategoryController@edit');
                    Route::get('sub-category/delete/{id}', 'SubCategoryController@delete');
                    Route::get('sub-category/change-status/{id}', 'SubCategoryController@change_status');
                    Route::get('sub-category/change-feature/{id}', 'SubCategoryController@change_feature');
                /* sub category */
                /* faq category */
                    Route::get('faq-category/list', 'FaqCategoryController@list');
                    Route::match(['get', 'post'], 'faq-category/add', 'FaqCategoryController@add');
                    Route::match(['get', 'post'], 'faq-category/edit/{id}', 'FaqCategoryController@edit');
                    Route::get('faq-category/delete/{id}', 'FaqCategoryController@delete');
                    Route::get('faq-category/change-status/{id}', 'FaqCategoryController@change_status');
                    Route::get('faq-category/change-home-page-status/{id}', 'FaqCategoryController@change_home_page_status');
                /* faq category */
                /* faq */
                    Route::get('faq/list', 'FaqController@list');
                    Route::match(['get', 'post'], 'faq/add', 'FaqController@add');
                    Route::match(['get', 'post'], 'faq/edit/{id}', 'FaqController@edit');
                    Route::get('faq/delete/{id}', 'FaqController@delete');
                    Route::get('faq/change-status/{id}', 'FaqController@change_status');
                    Route::get('faq/change-home-page-status/{id}', 'FaqController@change_home_page_status');
                    Route::post('faq/sorting-content', 'FaqController@sortingContent');
                /* faq */

                /* document type */
                    Route::get('document-type/list', 'DocumentTypeController@list');
                    Route::match(['get', 'post'], 'document-type/add', 'DocumentTypeController@add');
                    Route::match(['get', 'post'], 'document-type/edit/{id}', 'DocumentTypeController@edit');
                    Route::get('document-type/delete/{id}', 'DocumentTypeController@delete');
                    Route::get('document-type/change-status/{id}', 'DocumentTypeController@change_status');
                /* document type */
                /* user type */
                    Route::get('user-type/list', 'UserTypeController@list');
                    Route::match(['get', 'post'], 'user-type/add', 'UserTypeController@add');
                    Route::match(['get', 'post'], 'user-type/edit/{id}', 'UserTypeController@edit');
                    Route::get('user-type/delete/{id}', 'UserTypeController@delete');
                    Route::get('user-type/change-status/{id}', 'UserTypeController@change_status');
                /* user type */
                /* center type */
                    Route::get('center-type/list', 'CenterTypeController@list');
                    Route::match(['get', 'post'], 'center-type/add', 'CenterTypeController@add');
                    Route::match(['get', 'post'], 'center-type/edit/{id}', 'CenterTypeController@edit');
                    Route::get('center-type/delete/{id}', 'CenterTypeController@delete');
                    Route::get('center-type/change-status/{id}', 'CenterTypeController@change_status');
                /* center type */
                /* product type */
                Route::get('product-type/list', 'ProductTypeController@list');
                Route::match(['get', 'post'], 'product-type/add', 'ProductTypeController@add');
                Route::match(['get', 'post'], 'product-type/edit/{id}', 'ProductTypeController@edit');
                Route::get('product-type/delete/{id}', 'ProductTypeController@delete');
                Route::get('product-type/change-status/{id}', 'ProductTypeController@change_status');
            /* product type */
                /* religion */
                    Route::get('religion/list', 'ReligionController@list');
                    Route::match(['get', 'post'], 'religion/add', 'ReligionController@add');
                    Route::match(['get', 'post'], 'religion/edit/{id}', 'ReligionController@edit');
                    Route::get('religion/delete/{id}', 'ReligionController@delete');
                    Route::get('religion/change-status/{id}', 'ReligionController@change_status');
                /* religion */
                /* caste category */
                    Route::get('caste-category/list', 'CasteCategoryController@list');
                    Route::match(['get', 'post'], 'caste-category/add', 'CasteCategoryController@add');
                    Route::match(['get', 'post'], 'caste-category/edit/{id}', 'CasteCategoryController@edit');
                    Route::get('caste-category/delete/{id}', 'CasteCategoryController@delete');
                    Route::get('caste-category/change-status/{id}', 'CasteCategoryController@change_status');
                /* caste category */
                /* country */
                    Route::get('country/list', 'CountryController@list');
                    Route::match(['get', 'post'], 'country/add', 'CountryController@add');
                    Route::match(['get', 'post'], 'country/edit/{id}', 'CountryController@edit');
                    Route::get('country/delete/{id}', 'CountryController@delete');
                    Route::get('country/change-status/{id}', 'CountryController@change_status');
                /* country */
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
                /* state */
                /* source */
                    Route::get('source/list', 'SourceController@list');
                    Route::match(['get', 'post'], 'source/add', 'SourceController@add');
                    Route::match(['get', 'post'], 'source/edit/{id}', 'SourceController@edit');
                    Route::get('source/delete/{id}', 'SourceController@delete');
                    Route::get('source/change-status/{id}', 'SourceController@change_status');
                    Route::get('source/change-home-page-status/{id}', 'SourceController@change_home_page_status');
                    Route::post('source/sorting-content', 'SourceController@sortingContent');
                /* source */
                /* session year */
                    Route::get('session-year/list', 'SessionYearController@list');
                    Route::match(['get', 'post'], 'session-year/add', 'SessionYearController@add');
                    Route::match(['get', 'post'], 'session-year/edit/{id}', 'SessionYearController@edit');
                    Route::get('session-year/delete/{id}', 'SessionYearController@delete');
                    Route::get('session-year/change-status/{id}', 'SessionYearController@change_status');
                    Route::get('session-year/change-home-page-status/{id}', 'SessionYearController@change_home_page_status');
                    Route::post('session-year/sorting-content', 'SessionYearController@sortingContent');
                /* session year */
                /* label */
                    Route::get('label/list', 'LabelController@list');
                    Route::match(['get', 'post'], 'label/add', 'LabelController@add');
                    Route::match(['get', 'post'], 'label/edit/{id}', 'LabelController@edit');
                    Route::get('label/delete/{id}', 'LabelController@delete');
                    Route::get('label/change-status/{id}', 'LabelController@change_status');
                /* label */
            /* master */
            /* franchise owners */
                Route::get('franchise-owner/list', 'FranchiseOwnerController@list');
                Route::match(['get', 'post'], 'franchise-owner/add', 'FranchiseOwnerController@add');
                Route::match(['get', 'post'], 'franchise-owner/edit/{id}', 'FranchiseOwnerController@edit');
                Route::get('franchise-owner/delete/{id}', 'FranchiseOwnerController@delete');
                Route::get('franchise-owner/change-status/{id}', 'FranchiseOwnerController@change_status');
            /* franchise owners */
            /* own center */
                Route::get('own-center/list', 'OwnCenterController@list');
                Route::match(['get', 'post'], 'own-center/add', 'OwnCenterController@add');
                Route::match(['get', 'post'], 'own-center/edit/{id}', 'OwnCenterController@edit');
                Route::get('own-center/delete/{id}', 'OwnCenterController@delete');
                Route::get('own-center/change-status/{id}', 'OwnCenterController@change_status');
                Route::get('own-center/slot-time/{id}', 'OwnCenterController@slot_time');
                Route::post('own-center/slot-time/{id}', 'OwnCenterController@slot_time');
            /* own center */
            /* franchise center */
                Route::get('franchise-center/list', 'FranchiseCenterController@list');
                Route::match(['get', 'post'], 'franchise-center/add', 'FranchiseCenterController@add');
                Route::match(['get', 'post'], 'franchise-center/edit/{id}', 'FranchiseCenterController@edit');
                Route::get('franchise-center/delete/{id}', 'FranchiseCenterController@delete');
                Route::get('franchise-center/change-status/{id}', 'FranchiseCenterController@change_status');
                Route::get('franchise-center/slot-time/{id}', 'OwnCenterController@slot_time');
                Route::post('franchise-center/slot-time/{id}', 'OwnCenterController@slot_time');
            /* franchise center */
            /* teacher center */
                Route::get('teacher/list', 'TeacherController@list');
                Route::match(['get', 'post'], 'teacher/add', 'TeacherController@add');
                Route::match(['get', 'post'], 'teacher/edit/{id}', 'TeacherController@edit');
                Route::get('teacher/delete/{id}', 'TeacherController@delete');
                Route::get('teacher/change-status/{id}', 'TeacherController@change_status');
            /* teacher center */
            /* student center */
                Route::get('student/list', 'StudentController@list');
                Route::match(['get', 'post'], 'student/add', 'StudentController@add');
                Route::match(['get', 'post'], 'student/edit/{id}', 'StudentController@edit');
                Route::match(['get', 'post'], 'student/view-details/{id}', 'StudentController@viewDetails');
                Route::get('student/delete/{id}', 'StudentController@delete');
                Route::get('student/change-status/{id}', 'StudentController@change_status');
                Route::get('student/label-marks/{id}', 'StudentController@label_marks');
            /* student center */
            /* home page */
                /* banner */
                    Route::get('banner/list', 'BannerController@list');
                    Route::match(['get', 'post'], 'banner/add', 'BannerController@add');
                    Route::match(['get', 'post'], 'banner/edit/{id}', 'BannerController@edit');
                    Route::get('banner/delete/{id}', 'BannerController@delete');
                    Route::get('banner/change-status/{id}', 'BannerController@change_status');
                /* banner */
                /* testimonial */
                    Route::get('testimonial/list', 'TestimonialController@list');
                    Route::match(['get', 'post'], 'testimonial/add', 'TestimonialController@add');
                    Route::match(['get', 'post'], 'testimonial/edit/{id}', 'TestimonialController@edit');
                    Route::get('testimonial/delete/{id}', 'TestimonialController@delete');
                    Route::get('testimonial/change-status/{id}', 'TestimonialController@change_status');
                /* testimonial */
                /* gallery cayegory */
                    Route::get('gallery-category/list', 'GalleryCategoryController@list');
                    Route::match(['get', 'post'], 'gallery-category/add', 'GalleryCategoryController@add');
                    Route::match(['get', 'post'], 'gallery-category/edit/{id}', 'GalleryCategoryController@edit');
                    Route::get('gallery-category/delete/{id}', 'GalleryCategoryController@delete');
                    Route::get('gallery-category/change-status/{id}', 'GalleryCategoryController@change_status');
                /* gallery cayegory */
                /* gallery */
                    Route::get('gallery/list', 'GalleryController@list');
                    Route::match(['get', 'post'], 'gallery/add', 'GalleryController@add');
                    Route::match(['get', 'post'], 'gallery/edit/{id}', 'GalleryController@edit');
                    Route::get('gallery/delete/{id}', 'GalleryController@delete');
                    Route::get('gallery/change-status/{id}', 'GalleryController@change_status');
                /* gallery */
                /* section 2 */
                    Route::get('home-page-section2/list', 'HomePageSection2Controller@list');
                    Route::match(['get', 'post'], 'home-page-section2/add', 'HomePageSection2Controller@add');
                    Route::match(['get', 'post'], 'home-page-section2/edit/{id}', 'HomePageSection2Controller@edit');
                    Route::get('home-page-section2/delete/{id}', 'HomePageSection2Controller@delete');
                    Route::get('home-page-section2/change-status/{id}', 'HomePageSection2Controller@change_status');
                /* section 2 */
                /* section 5 */
                    Route::match(['get', 'post'], 'home-page/list', 'HomePageSectionController@list');
                /* section 5 */
                /* section 5 */
                    Route::get('home-page-section5/list', 'HomePageSection5Controller@list');
                    Route::match(['get', 'post'], 'home-page-section5/add', 'HomePageSection5Controller@add');
                    Route::match(['get', 'post'], 'home-page-section5/edit/{id}', 'HomePageSection5Controller@edit');
                    Route::get('home-page-section5/delete/{id}', 'HomePageSection5Controller@delete');
                    Route::get('home-page-section5/change-status/{id}', 'HomePageSection5Controller@change_status');
                /* section 5 */
            /* home page */
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
            /* notifications */
                Route::get('notification/list', 'NotificationController@list');
                Route::match(['get', 'post'], 'notification/add', 'NotificationController@add');
                Route::match(['get', 'post'], 'notification/edit/{id}', 'NotificationController@edit');
                Route::get('notification/delete/{id}', 'NotificationController@delete');
                Route::get('notification/change-status/{id}', 'NotificationController@change_status');
                Route::get('notification/send/{id}', 'NotificationController@send');
                Route::post('notification/get-user', 'NotificationController@getUser');
            /* notifications */
            /* notice */
                Route::get('notice/list', 'NoticeController@list');
                Route::match(['get', 'post'], 'notice/add', 'NoticeController@add');
                Route::match(['get', 'post'], 'notice/edit/{id}', 'NoticeController@edit');
                Route::get('notice/delete/{id}', 'NoticeController@delete');
                Route::get('notice/change-status/{id}', 'NoticeController@change_status');
            /* notice */
            /* newsletter */
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
            /* newsletter */
        });
    });
/* Admin Panel */
/* Api */
    Route::prefix('api')->namespace('App\Http\Controllers')->group(function(){
        // Other Version 2 routes
        /* before login */
            Route::match(['get'], '/get-app-setting', 'ApiController@getAppSetting');
            Route::match(['get'], '/get-source', 'ApiController@getSource');
            Route::match(['get'], '/get-center', 'ApiController@getCenter');
            Route::match(['get'], '/get-document-type', 'ApiController@getDocumentType');
            Route::match(['get'], '/get-level', 'ApiController@getLevel');
            Route::match(['get'], '/get-country', 'ApiController@getCountry');
            Route::match(['post'], '/get-state', 'ApiController@getState');
            Route::match(['post'], '/get-district', 'ApiController@getDistrict');
            Route::match(['post'], '/get-static-pages', 'ApiController@getStaticPages');
            Route::match(['get'], '/get-notice', 'ApiController@getNotice');
            Route::match(['get'], '/get-all-masters', 'ApiController@getAllMasters');

            Route::match(['post'], '/signin', 'ApiController@signin');
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
            Route::match(['post'], '/update-profile', 'ApiController@updateProfile');
            Route::match(['get'], '/delete-account', 'ApiController@deleteAccount');
            Route::match(['get'], '/student-list', 'ApiController@studentList');
            Route::match(['post'], '/student-detail', 'ApiController@studentDetail');
            Route::match(['post'], '/add-student', 'ApiController@addStudent');
            Route::match(['post'], '/edit-student', 'ApiController@editStudent');
            Route::match(['post'], '/update-student', 'ApiController@updateStudent');
            Route::match(['post'], '/upload-profile-image', 'ApiController@uploadProfileImage');
            Route::match(['get'], '/my-center', 'ApiController@myCenter');
        /* after login */
    });
/* Api */