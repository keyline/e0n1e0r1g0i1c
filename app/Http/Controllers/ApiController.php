<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\Center;
use App\Models\CenterTimeSlot;
use App\Models\DocumentType;
use App\Models\DeleteAccountRequest;
use App\Models\Label;
use App\Models\Student;
use App\Models\GeneralSetting;
use App\Models\EmailLog;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\Banner;
use App\Models\Notice;
use App\Models\Teacher;
use App\Models\GalleryCategory;
use App\Models\Gallery;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Enquiry;
use App\Models\UserActivity;
use App\Models\Source;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\StudentLabelMark;

use Auth;
use Session;
use Helper;
use Hash;
use DB;
use App\Libraries\CreatorJwt;
use App\Libraries\JWT;
date_default_timezone_set("Asia/Calcutta");
class ApiController extends Controller
{

    /* before login screen */
        public function getAppSetting(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $generalSetting = GeneralSetting::find(1);
                if($generalSetting){
                    $apiResponse = [
                        'site_name'             => $generalSetting->site_name,
                        'site_phone'            => $generalSetting->site_phone,
                        'site_phone2'           => $generalSetting->site_phone2,
                        'site_mail'             => $generalSetting->site_mail,
                        'system_email'          => $generalSetting->system_email,
                        'site_url'              => $generalSetting->site_url,
                        'site_logo'             => env('UPLOADS_URL').$generalSetting->site_logo,
                        'site_footer_logo'      => env('UPLOADS_URL').$generalSetting->site_footer_logo,
                        'site_favicon'          => env('UPLOADS_URL').$generalSetting->site_favicon,
                        'site_address'          => $generalSetting->description,
                        'theme_color'           => $generalSetting->theme_color,
                        'font_color'            => $generalSetting->font_color,
                        'sidebar_bgcolor'       => $generalSetting->sidebar_bgcolor,
                        'header_bgcolor'        => $generalSetting->header_bgcolor,
                        'twitter_profile'       => $generalSetting->twitter_profile,
                        'facebook_profile'      => $generalSetting->facebook_profile,
                        'instagram_profile'     => $generalSetting->instagram_profile,
                        'linkedin_profile'      => $generalSetting->linkedin_profile,
                        'youtube_profile'       => $generalSetting->youtube_profile,
                    ];
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getSource(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $sources = Source::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($sources){
                    foreach ($sources as $row) {
                        $apiResponse[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getCenter(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $centers = Center::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($centers){
                    foreach ($centers as $row) {
                        $apiResponse[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getDocumentType(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $docTypes = DocumentType::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($docTypes){
                    foreach ($docTypes as $row) {
                        $apiResponse[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getLevel(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $levels = Label::select('id', 'name')->where('status', '=', 1)->orderBy('label_no', 'ASC')->get();
                if($levels){
                    foreach ($levels as $row) {
                        $apiResponse[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getCountry(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $countries = Country::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($countries){
                    foreach ($countries as $row) {
                        $apiResponse[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getState(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'country_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $country_id = $requestData['country_id'];
                $countries  = State::select('id', 'name')->where('status', '=', 1)->where('country_id', '=', $country_id)->orderBy('name', 'ASC')->get();
                if($countries){
                    foreach ($countries as $row) {
                        $apiResponse[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getDistrict(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'state_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $state_id = $requestData['state_id'];
                $countries  = District::select('id', 'name')->where('status', '=', 1)->where('state_id', '=', $state_id)->orderBy('name', 'ASC')->get();
                if($countries){
                    foreach ($countries as $row) {
                        $apiResponse[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getStaticPages(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'page_slug'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $page_slug = $requestData['page_slug'];
                $pageContent  = Page::select('page_name', 'page_content')->where('status', '=', 1)->where('page_slug', '=', $page_slug)->first();
                if($pageContent){
                    $apiResponse[] = [
                        'page_name'            => $pageContent->page_name,
                        'page_content'         => $pageContent->page_content
                    ];
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getNotice(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $notices = Notice::select('name', 'description', 'start_date', 'expiry_date', 'uploaded_by', 'notice_date', 'notice_file')->where('status', '=', 1)->orderBy('id', 'DESC')->get();
                if($notices){
                    foreach ($notices as $row) {
                        $apiResponse[] = [
                            'name'            => $row->name,
                            'description'            => $row->description,
                            'start_date'             => date_format(date_create($row->start_date), "M d, Y"),
                            'expiry_date'            => date_format(date_create($row->expiry_date), "M d, Y"),
                            'uploaded_by'            => $row->uploaded_by,
                            'notice_date'            => date_format(date_create($row->notice_date), "M d, Y"),
                            'notice_file'            => env('UPLOADS_URL').'notice/'.$row->notice_file,
                        ];
                    }
                }
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getAllMasters(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $centerData         = [];
                $sourceData         = [];
                $documentTypeData   = [];
                $levelData          = [];
                $countryData        = [];

                $centers            = Center::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($centers){
                    foreach ($centers as $row) {
                        $centerData[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }

                $sources = Source::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($sources){
                    foreach ($sources as $row) {
                        $sourceData[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }

                $docTypes = DocumentType::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($docTypes){
                    foreach ($docTypes as $row) {
                        $documentTypeData[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }

                $levels = Label::select('id', 'name')->where('status', '=', 1)->orderBy('label_no', 'ASC')->get();
                if($levels){
                    foreach ($levels as $row) {
                        $levelData[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }

                $countries = Country::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($countries){
                    foreach ($countries as $row) {
                        $countryData[] = [
                            'label'            => $row->name,
                            'value'            => $row->id
                        ];
                    }
                }

                $apiResponse = [
                    'centers'           => $centerData,
                    'sources'           => $sourceData,
                    'document_types'    => $documentTypeData,
                    'levels'            => $levelData,
                    'countries'         => $countryData,
                ];
                http_response_code(200);
                $apiStatus          = TRUE;
                $apiMessage         = 'Data Available !!!';
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
    /* before login screen */
    /* authentication */
        public function signin(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'email', 'password', 'device_token', 'fcm_token'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $email                      = $requestData['email'];
                $password                   = $requestData['password'];
                $device_type                = $headerData['source'][0];
                $device_token               = $requestData['device_token'];
                $fcm_token                  = $requestData['fcm_token'];
                $checkUser                  = Teacher::where('email', '=', $email)->where('status', '=', 1)->first();
                if($checkUser){
                    if(Hash::check($password, $checkUser->password)){
                        $objOfJwt           = new CreatorJwt();
                        $app_access_token   = $objOfJwt->GenerateToken($checkUser->id, $checkUser->email, $checkUser->phone);
                        $user_id                        = $checkUser->id;
                        $fields     = [
                            'user_id'               => $user_id,
                            'device_type'           => $device_type,
                            'device_token'          => $device_token,
                            'fcm_token'             => $fcm_token,
                            'app_access_token'      => $app_access_token,
                        ];
                        $checkUserTokenExist            = UserDevice::where('user_id', '=', $user_id)->where('published', '=', 1)->where('device_type', '=', $device_type)->where('device_token', '=', $device_token)->first();
                        if(!$checkUserTokenExist){
                            UserDevice::insert($fields);
                        } else {
                            UserDevice::where('id','=',$checkUserTokenExist->id)->update($fields);
                        }
                        $apiResponse = [
                            'user_id'               => $user_id,
                            'name'                  => $checkUser->name,
                            'email'                 => $checkUser->email,
                            'phone'                 => $checkUser->phone,
                            'role'                  => 'TEACHER',
                            'device_type'           => $device_type,
                            'device_token'          => $device_token,
                            'fcm_token'             => $fcm_token,
                            'app_access_token'      => $app_access_token,
                        ];
                        $apiStatus                          = TRUE;
                        $apiMessage                         = 'SignIn Successfully !!!';
                    } else {
                        $apiStatus                          = FALSE;
                        $apiMessage                         = 'Invalid Password !!!';
                    }                   
                } else {
                    $apiStatus                              = FALSE;
                    $apiMessage                             = 'We Don\'t Recognize You !!!';
                }
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function forgotPassword(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'email'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $checkEmail = Teacher::where('email', '=', $requestData['email'])->first();
                if($checkEmail){
                    $remember_token  = rand(1000,9999);
                    Teacher::where('id', '=', $checkEmail->id)->update(['otp' => $remember_token]);
                    $mailData                   = [
                        'id'    => $checkEmail->id,
                        'email' => $checkEmail->email,
                        'otp'   => $remember_token,
                    ];
                    $generalSetting             = GeneralSetting::find('1');
                    $subject                    = $generalSetting->site_name.' :: Forgot Password OTP';
                    $message                    = view('email-templates.otp',$mailData);
                    // echo $message;die;
                    $this->sendMail($requestData['email'], $subject, $message);

                    $apiResponse                        = $mailData;
                    $apiStatus                          = TRUE;
                    http_response_code(200);
                    $apiMessage                         = 'OTP Sent To Email Validation !!!';
                    $apiExtraField                      = 'response_code';
                    $apiExtraData                       = http_response_code();
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'Email Not Registered With Us !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function validateOtp(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'id', 'otp'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $getUser = Teacher::where('id', '=', $requestData['id'])->first();
                if($getUser){
                    $remember_token  = $getUser->otp;
                    if($remember_token == $requestData['otp']){
                        Teacher::where('id', '=', $requestData['id'])->update(['otp' => 0]);
                        // $this->sendMail('subhomoysamanta1989@gmail.com', $requestData['subject'], $requestData['message']);
                        $apiResponse        = [
                            'id'    => $getUser->id,
                            'email' => $getUser->email
                        ];
                        $apiStatus                          = TRUE;
                        http_response_code(200);
                        $apiMessage                         = 'OTP Validated Successfully !!!';
                        $apiExtraField                      = 'response_code';
                        $apiExtraData                       = http_response_code();
                    } else {
                        $apiStatus          = FALSE;
                        http_response_code(400);
                        $apiMessage         = 'OTP Mismatched !!!';
                        $apiExtraField      = 'response_code';
                    }
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'Teacher Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function resendOtp(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $id         = $requestData['id'];
                $getUser    = Teacher::where('id', '=', $id)->first();
                if($getUser){
                    $remember_token = rand(1000,9999);
                    $postData = [
                        'otp'        => $remember_token
                    ];
                    Teacher::where('id', '=', $id)->update($postData);
                    
                    $mailData                   = [
                        'id'    => $getUser->id,
                        'email' => $getUser->email,
                        'otp'   => $remember_token,
                    ];
                    $generalSetting             = GeneralSetting::find('1');
                    $subject                    = $generalSetting->site_name.' :: Resend OTP';
                    $message                    = view('email-templates.otp',$mailData);
                    // echo $message;die;
                    $this->sendMail($getUser->email, $subject, $message);

                    $apiResponse                        = $mailData;
                    $apiStatus                          = TRUE;
                    http_response_code(200);
                    $apiMessage                         = 'OTP Resend !!!';
                    $apiExtraField                      = 'response_code';
                    $apiExtraData                       = http_response_code();
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'Teacher Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function resetPassword(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'id', 'password', 'confirm_password'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $getUser = Teacher::where('id', '=', $requestData['id'])->first();
                if($getUser){
                    if($requestData['password'] == $requestData['confirm_password']){
                        Teacher::where('id', '=', $requestData['id'])->update(['password' => Hash::make($requestData['password'])]);
                        $mailData        = [
                            'id'        => $getUser->id,
                            'name'      => $getUser->first_name.' '.$getUser->last_name,
                            'email'     => $getUser->email
                        ];

                        $generalSetting             = GeneralSetting::find('1');
                        $subject                    = $generalSetting->site_name.' :: Reset Password';
                        $message                    = view('email-templates.change-password',$mailData);
                        // echo $message;die;
                        $this->sendMail($getUser->email, $subject, $message);
                        
                        $apiStatus                          = TRUE;
                        http_response_code(200);
                        $apiMessage                         = 'Password Reset Successfully !!!';
                        $apiExtraField                      = 'response_code';
                        $apiExtraData                       = http_response_code();
                    } else {
                        $apiStatus          = FALSE;
                        http_response_code(400);
                        $apiMessage         = 'Password & Confirm Password Not Matched !!!';
                        $apiExtraField      = 'response_code';
                    }
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(400);
                    $apiMessage         = 'User Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(400);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
    /* authentication */
    /* after login */
        public function signout(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = [];
            $headerData         = $request->header();
            // Helper::pr($headerData);
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    UserDevice::where('app_access_token', '=', $app_access_token)->delete();
                    $apiStatus                      = TRUE;
                    $apiMessage                     = 'Signout Successfully !!!';
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function dashboard(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Teacher::where('id', '=', $uId)->first();
                        if($getUser){
                            $apiResponse = [
                                'user_id'               => $uId,
                                'name'                  => $getUser->name,
                                'email'                 => $getUser->email,
                                'phone'                 => $getUser->phone,
                                'role'                  => 'TEACHER',
                                'app_access_token'      => $app_access_token,
                            ];
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Data Available !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function changePassword(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $old_password               = $requestData['old_password'];
                $new_password               = $requestData['new_password'];
                $confirm_password           = $requestData['confirm_password'];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = Teacher::where('id', '=', $uId)->first();
                    if($getUser){
                        if(Hash::check($old_password, $getUser->password)){
                            if($new_password == $confirm_password){
                                if($new_password != $old_password){
                                    $fields = [
                                        'password'                  => Hash::make($new_password)
                                    ];
                                    Teacher::where('id', '=', $uId)->update($fields);
                                    // new password send mail
                                        $generalSetting                 = GeneralSetting::find('1');
                                        $subject                        = $generalSetting->site_name.' Change Password';
                                        $mailData['name']               = $getUser->name;
                                        $mailData['email']              = $getUser->email;
                                        $html                           = view('email-templates/change-password', $mailData);
                                        $this->sendMail($getUser->email, $subject, $html);
                                        // echo $html;die;
                                    // new password send mail
                                    $apiStatus          = TRUE;
                                    $apiMessage         = 'Password Updated Successfully !!!';
                                } else {
                                    $apiStatus          = FALSE;
                                    $apiMessage         = 'Current & New Password Should Not Be Same !!!';
                                }
                            } else {
                                $apiStatus          = FALSE;
                                $apiMessage         = 'New & Confirm Password Doesn\'t Matched !!!';
                            }
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'Current Password Doesn\'t Matched !!!';
                        }
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function getProfile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = Teacher::where('id', '=', $uId)->first();
                    if($getUser){
                        $getCountry     = Country::select('name')->where('id', '=', $getUser->country_id)->first();
                        $getState       = State::select('name')->where('id', '=', $getUser->state_id)->first();
                        $getDistrict    = District::select('name')->where('id', '=', $getUser->district_id)->first();
                        $getDocType     = DocumentType::select('name')->where('id', '=', $getUser->doc_type_id)->first();
                        $profileData    = [
                            'teacher_no'            => $getUser->teacher_no,
                            'name'                  => $getUser->name,
                            'address'               => $getUser->address,
                            'country_id'            => (($getCountry)?$getCountry->name:''),
                            'state_id'              => (($getState)?$getState->name:''),
                            'district_id'           => (($getDistrict)?$getDistrict->name:''),
                            'locality'              => $getUser->locality,
                            'pincode'               => $getUser->pincode,
                            'landmark'              => $getUser->landmark,
                            'email'                 => $getUser->email,
                            'phone'                 => $getUser->phone,
                            'alt_phone'             => (($getUser->alt_phone != '')?$getUser->alt_phone:''),
                            'whatsapp_no'           => $getUser->whatsapp_no,
                            'doc_type_id'           => (($getDocType)?$getDocType->name:''),
                            'id_proof'              => (($getUser->id_proof != '')?env('UPLOADS_URL').'teacher/'.$getUser->id_proof:env('NO_USER_IMAGE')),
                            'member_since'          => date_format(date_create($getUser->member_since), "M d, Y"),
                            'qualification'         => (($getUser->qualification != '')?$getUser->qualification:''),
                            'created_at'            => date_format(date_create($getUser->created_at), "M d, Y h:i A"),
                            'profile_image'         => (($getUser->photo != '')?env('UPLOADS_URL').'teacher/'.$getUser->photo:env('NO_USER_IMAGE')),
                        ];
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                        $apiResponse        = $profileData;
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function editProfile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = Teacher::where('id', '=', $uId)->first();
                    if($getUser){
                        $getCountry     = Country::select('name')->where('id', '=', $getUser->country_id)->first();
                        $getState       = State::select('name')->where('id', '=', $getUser->state_id)->first();
                        $getDistrict    = District::select('name')->where('id', '=', $getUser->district_id)->first();
                        $getDocType     = DocumentType::select('name')->where('id', '=', $getUser->doc_type_id)->first();
                        $profileData    = [
                            'teacher_no'            => $getUser->teacher_no,
                            'name'                  => $getUser->name,
                            'address'               => $getUser->address,
                            'country_id'            => $getUser->country_id,
                            'state_id'              => $getUser->state_id,
                            'district_id'           => $getUser->district_id,
                            'locality'              => $getUser->locality,
                            'pincode'               => $getUser->pincode,
                            'landmark'              => $getUser->landmark,
                            'email'                 => $getUser->email,
                            'phone'                 => $getUser->phone,
                            'alt_phone'             => (($getUser->alt_phone != '')?$getUser->alt_phone:''),
                            'whatsapp_no'           => $getUser->whatsapp_no,
                            'doc_type_id'           => $getUser->doc_type_id,
                            'id_proof'              => (($getUser->profile_image != '')?env('UPLOADS_URL').'teacher/'.$getUser->id_proof:env('NO_USER_IMAGE')),
                            'member_since'          => $getUser->member_since,
                            'qualification'         => (($getUser->qualification != '')?$getUser->qualification:''),
                            'profile_image'         => (($getUser->profile_image != '')?env('UPLOADS_URL').'teacher/'.$getUser->profile_image:env('NO_USER_IMAGE')),
                        ];
                        $apiStatus          = TRUE;
                        $apiMessage         = 'Data Available !!!';
                        $apiResponse        = $profileData;
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function updateProfile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();       
            $requiredFields     = ['key', 'source', 'name', 'member_since', 'phone_no', 'whatsapp_no', 'address', 'city', 'landmark', 'country', 'state', 'district', 'pin_code', 'qualification'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = Teacher::where('id', '=', $uId)->first();
                    if($getUser){
                        /* teacher photo */
                            // $teacher_image  = $requestData['teacher_image'];
                            // if(!empty($teacher_image)){
                            //     $teacher_image      = $teacher_image;
                            //     $upload_type        = $teacher_image['type'];
                            //     if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                            //         $upload_base64      = $teacher_image['base64'];
                            //         $img                = $upload_base64;
                            //         $proof_type         = $teacher_image['type'];
                            //         if($proof_type == 'image/png'){
                            //             $extn = 'png';
                            //         } elseif($proof_type == 'image/jpg'){
                            //             $extn = 'jpg';
                            //         } elseif($proof_type == 'image/jpeg'){
                            //             $extn = 'jpeg';
                            //         } elseif($proof_type == 'image/gif'){
                            //             $extn = 'gif';
                            //         } else {
                            //             $extn = 'png';
                            //         }
                            //         $data               = base64_decode($img);
                            //         $fileName           = uniqid() . '.' . $extn;
                            //         $file               = 'public/uploads/student/' . $fileName;
                            //         $success            = file_put_contents($file, $data);
                            //         $photo              = $fileName;
                            //     } else {
                            //         $apiStatus          = FALSE;
                            //         http_response_code(404);
                            //         $apiMessage         = 'Please Upload Image !!!';
                            //         $apiExtraField      = 'response_code';
                            //         $apiExtraData       = http_response_code();
                            //     }
                            // } else {
                            //     $photo           = $getUser->photo;
                            // }
                        /* teacher photo */
                        /* teacher id proof */
                            // $teacher_id_image  = $requestData['teacher_id_image'];
                            // if(!empty($teacher_id_image)){
                            //     $teacher_id_image      = $teacher_id_image;
                            //     $upload_type        = $teacher_id_image['type'];
                            //     if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                            //         $upload_base64      = $teacher_id_image['base64'];
                            //         $img                = $upload_base64;
                            //         $proof_type         = $teacher_id_image['type'];
                            //         if($proof_type == 'image/png'){
                            //             $extn = 'png';
                            //         } elseif($proof_type == 'image/jpg'){
                            //             $extn = 'jpg';
                            //         } elseif($proof_type == 'image/jpeg'){
                            //             $extn = 'jpeg';
                            //         } elseif($proof_type == 'image/gif'){
                            //             $extn = 'gif';
                            //         } else {
                            //             $extn = 'png';
                            //         }
                            //         $data               = base64_decode($img);
                            //         $fileName           = uniqid() . '.' . $extn;
                            //         $file               = 'public/uploads/student/' . $fileName;
                            //         $success            = file_put_contents($file, $data);
                            //         $id_proof           = $fileName;
                            //     } else {
                            //         $apiStatus          = FALSE;
                            //         http_response_code(404);
                            //         $apiMessage         = 'Please Upload Image !!!';
                            //         $apiExtraField      = 'response_code';
                            //         $apiExtraData       = http_response_code();
                            //     }
                            // } else {
                            //     $id_proof           = $getUser->id_proof;
                            // }
                        /* teacher id proof */
                        $postData = [
                                    'name'                  => $requestData['name'],
                                    'address'               => $requestData['address'],
                                    'country_id'            => $requestData['country'],
                                    'state_id'              => $requestData['state'],
                                    'district_id'           => $requestData['district'],
                                    'locality'              => $requestData['city'],
                                    'pincode'               => $requestData['pin_code'],
                                    'landmark'              => $requestData['landmark'],
                                    'phone'                 => $requestData['phone_no'],
                                    'alt_phone'             => $requestData['alternate_phone'],
                                    'whatsapp_no'           => $requestData['whatsapp_no'],
                                    // 'doc_type_id'           => $requestData['doc_type_id'],
                                    // 'id_proof'              => $id_proof,
                                    // 'photo'                 => $photo,
                                    'member_since'          => $requestData['member_since'],
                                    'qualification'         => $requestData['qualification'],
                                ];
                        // Helper::pr($postData);
                        Teacher::where('id', '=', $uId)->update($postData);
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Profile Updated Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'Teacher Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function uploadProfileImage(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['profile_image'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $request->header('Authorization');
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = Teacher::where('id', '=', $uId)->first();
                    if($getUser){
                        $profile_image  = $requestData['profile_image'];
                        // if($profile_image != ''){
                        //     /* upload profile image */        
                        //         $upload_file    = $profile_image;
                        //         Helper::pr($upload_file);
                        //         // $img            = $upload_file['base64'];
                        //         $img            = str_replace('data:image/jpeg;base64,', '', $upload_file);
                        //         $img            = str_replace(' ', '+', $img);
                        //         $data           = base64_decode($img);
                        //         $fileName       = uniqid() . '.jpg';
                        //         $file           = 'public/uploads/teacher/' . $fileName;
                        //         $success        = file_put_contents($file, $data);
                        //         $profile_image  = $fileName;
                        //     /* upload profile image */
                        // } else {
                        //     $profile_image = $getUser->profile_image;
                        // }
                        if(!empty($profile_image)){
                            $profile_image      = $profile_image;
                            $upload_type        = $profile_image['type'];
                            if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                $upload_base64      = $profile_image['base64'];
                                $img                = $upload_base64;
                                $proof_type         = $profile_image['type'];
                                if($proof_type == 'image/png'){
                                    $extn = 'png';
                                } elseif($proof_type == 'image/jpg'){
                                    $extn = 'jpg';
                                } elseif($proof_type == 'image/jpeg'){
                                    $extn = 'jpeg';
                                } elseif($proof_type == 'image/gif'){
                                    $extn = 'gif';
                                } else {
                                    $extn = 'png';
                                }
                                $data               = base64_decode($img);
                                $fileName           = uniqid() . '.' . $extn;
                                $file               = 'public/uploads/teacher/' . $fileName;
                                $success            = file_put_contents($file, $data);
                                $profile_image      = $fileName;
                            } else {
                                $apiStatus          = FALSE;
                                http_response_code(404);
                                $apiMessage         = 'Please Upload Image !!!';
                                $apiExtraField      = 'response_code';
                                $apiExtraData       = http_response_code();
                            }
                        } else {
                            $profile_image = $getUser->photo;
                        }
                        $postData = [
                                    'photo'         => $profile_image
                                ];
                        // Helper::pr($postData);
                        Teacher::where('id', '=', $uId)->update($postData);
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Profile Image Uploaded Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        $apiMessage         = 'User Not Found !!!';
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                }                                               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }

        public function studentList(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Teacher::where('id', '=', $uId)->first();
                        if($getUser){
                            $assigned_center_id = json_decode($getUser->assigned_center_id);
                            if(!empty($assigned_center_id)){
                                for($c=0;$c<count($assigned_center_id);$c++){
                                    $getStudents    = Student::where('center_id', '=', $assigned_center_id[$c])->where('status', '=', 1)->get();
                                    if($getStudents){
                                        foreach($getStudents as $getStudent){
                                            $getCenter      = Center::select('name')->where('id', '=', $getStudent->center_id)->first();
                                            $getLevel       = Label::select('name')->where('id', '=', $getStudent->current_label_id)->first();
                                            $apiResponse[]  = [
                                                'user_id'               => $getStudent->id,
                                                'name'                  => $getStudent->name,
                                                'email'                 => $getStudent->email,
                                                'phone'                 => $getStudent->phone,
                                                'student_no'            => $getStudent->student_no,
                                                'center'                => (($getCenter)?$getCenter->name:''),
                                                'profile_image'         => (($getStudent->student_photo != '')?env('UPLOADS_URL').'student/'.$getStudent->student_photo:env('NO_USER_IMAGE')),
                                                'level'                 => (($getLevel)?$getLevel->name:''),
                                            ];
                                        }
                                    }
                                }
                            }
                            
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Data Available !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function studentDetail(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'student_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $student_id = $requestData['student_id'];
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Student::where('id', '=', $student_id)->first();
                        if($getUser){
                            $getCenter      = Center::select('name')->where('id', '=', $getUser->center_id)->first();
                            $getLevel       = Label::select('name')->where('id', '=', $getUser->current_label_id)->first();
                            $getCountry     = Country::select('name')->where('id', '=', $getUser->country_id)->first();
                            $getState       = State::select('name')->where('id', '=', $getUser->state_id)->first();
                            $getDistrict    = District::select('name')->where('id', '=', $getUser->district_id)->first();
                            $getSource      = Source::select('name')->where('id', '=', $getUser->source_id)->first();
                            $getStudentDocType     = DocumentType::select('name')->where('id', '=', $getUser->student_doc_type_id)->first();
                            $getGuardianDocType     = DocumentType::select('name')->where('id', '=', $getUser->guardian_doc_type_id)->first();
                            $apiResponse = [
                                'user_id'                       => $student_id,
                                'student_no'                    => $getUser->student_no,
                                'center_id'                     => (($getCenter)?$getCenter->name:''),
                                'name'                          => $getUser->name,
                                'address'                       => $getUser->address,
                                'country_id'                    => (($getCountry)?$getCountry->name:''),
                                'state_id'                      => (($getState)?$getState->name:''),
                                'district_id'                   => (($getDistrict)?$getDistrict->name:''),
                                'locality'                      => $getUser->locality,
                                'pincode'                       => $getUser->pincode,
                                'landmark'                      => $getUser->landmark,
                                'email'                         => $getUser->email,
                                'phone'                         => $getUser->phone,
                                'alt_phone'                     => (($getUser->alt_phone != '')?$getUser->alt_phone:''),
                                'whatsapp_no'                   => $getUser->whatsapp_no,
                                'dob'                           => date_format(date_create($getUser->dob), "M d, Y"),
                                'guardian_name'                 => $getUser->guardian_name,
                                'guardian_relation'             => $getUser->guardian_relation,
                                'source_id'                     => (($getSource)?$getSource->name:''),
                                'student_doc_type_id'           => (($getStudentDocType)?$getStudentDocType->name:''),
                                'student_id_proof'              => (($getUser->student_id_proof != '')?env('UPLOADS_URL').'student/'.$getUser->student_id_proof:env('NO_USER_IMAGE')),
                                'guardian_doc_type_id'          => (($getGuardianDocType)?$getGuardianDocType->name:''),
                                'guardian_id_proof'             => (($getUser->guardian_id_proof != '')?env('UPLOADS_URL').'student/'.$getUser->guardian_id_proof:env('NO_USER_IMAGE')),
                                'student_photo'                 => (($getUser->student_photo != '')?env('UPLOADS_URL').'student/'.$getUser->student_photo:env('NO_USER_IMAGE')),
                                'current_label_id'              => (($getLevel)?$getLevel->name:''),
                                'current_label_marks'           => $getUser->current_label_marks,
                                'created_at'                    => date_format(date_create($getUser->created_at), "M d, Y h:i A"),
                            ];
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Data Available !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function addStudent(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'student_name', 'guardian_name', 'guradian_relation', 'dob', 'phone_no', 'whatsapp_no', 'address', 'city', 'landmark', 'country', 'state', 'district', 'pin_code', 'center', 'level', 'source', 'email'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Teacher::where('id', '=', $uId)->first();
                        if($getUser){
                            /* center no generate */
                                $getLastEnquiry = Student::orderBy('id', 'DESC')->first();
                                if($getLastEnquiry){
                                    $sl_no              = $getLastEnquiry->sl_no;
                                    $next_sl_no         = $sl_no + 1;
                                    $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                                    $student_no         = 'KZE-S-'.$next_sl_no_string;
                                } else {
                                    $next_sl_no         = 1;
                                    $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                                    $student_no         = 'KZE-S-'.$next_sl_no_string;
                                }
                            /* center no generate */
                            /* student photo */
                                $student_image  = $requestData['student_image'];
                                if(!empty($student_image)){
                                    $student_image      = $student_image;
                                    $upload_type        = $student_image['type'];
                                    if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                        $upload_base64      = $student_image['base64'];
                                        $img                = $upload_base64;
                                        $proof_type         = $student_image['type'];
                                        if($proof_type == 'image/png'){
                                            $extn = 'png';
                                        } elseif($proof_type == 'image/jpg'){
                                            $extn = 'jpg';
                                        } elseif($proof_type == 'image/jpeg'){
                                            $extn = 'jpeg';
                                        } elseif($proof_type == 'image/gif'){
                                            $extn = 'gif';
                                        } else {
                                            $extn = 'png';
                                        }
                                        $data               = base64_decode($img);
                                        $fileName           = uniqid() . '.' . $extn;
                                        $file               = 'public/uploads/student/' . $fileName;
                                        $success            = file_put_contents($file, $data);
                                        $student_image      = $fileName;
                                    } else {
                                        $apiStatus          = FALSE;
                                        http_response_code(404);
                                        $apiMessage         = 'Please Upload Image !!!';
                                        $apiExtraField      = 'response_code';
                                        $apiExtraData       = http_response_code();
                                    }
                                } else {
                                    $apiStatus          = FALSE;
                                    http_response_code(404);
                                    $apiMessage         = 'Please Upload Image !!!';
                                    $apiExtraField      = 'response_code';
                                    $apiExtraData       = http_response_code();
                                }
                            /* student photo */
                            /* student id proof */
                                $student_id_image  = $requestData['student_id_image'];
                                if(!empty($student_id_image)){
                                    $student_id_image      = $student_id_image;
                                    $upload_type        = $student_id_image['type'];
                                    if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                        $upload_base64      = $student_id_image['base64'];
                                        $img                = $upload_base64;
                                        $proof_type         = $student_id_image['type'];
                                        if($proof_type == 'image/png'){
                                            $extn = 'png';
                                        } elseif($proof_type == 'image/jpg'){
                                            $extn = 'jpg';
                                        } elseif($proof_type == 'image/jpeg'){
                                            $extn = 'jpeg';
                                        } elseif($proof_type == 'image/gif'){
                                            $extn = 'gif';
                                        } else {
                                            $extn = 'png';
                                        }
                                        $data               = base64_decode($img);
                                        $fileName           = uniqid() . '.' . $extn;
                                        $file               = 'public/uploads/student/' . $fileName;
                                        $success            = file_put_contents($file, $data);
                                        $student_id_image      = $fileName;
                                    } else {
                                        $apiStatus          = FALSE;
                                        http_response_code(404);
                                        $apiMessage         = 'Please Upload Image !!!';
                                        $apiExtraField      = 'response_code';
                                        $apiExtraData       = http_response_code();
                                    }
                                } else {
                                    $apiStatus          = FALSE;
                                    http_response_code(404);
                                    $apiMessage         = 'Please Upload Image !!!';
                                    $apiExtraField      = 'response_code';
                                    $apiExtraData       = http_response_code();
                                }
                            /* student id proof */
                            /* guardian id proof */
                                $guardian_id_image  = $requestData['guardian_id_image'];
                                if(!empty($guardian_id_image)){
                                    $guardian_id_image      = $guardian_id_image;
                                    $upload_type        = $guardian_id_image['type'];
                                    if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                        $upload_base64      = $guardian_id_image['base64'];
                                        $img                = $upload_base64;
                                        $proof_type         = $guardian_id_image['type'];
                                        if($proof_type == 'image/png'){
                                            $extn = 'png';
                                        } elseif($proof_type == 'image/jpg'){
                                            $extn = 'jpg';
                                        } elseif($proof_type == 'image/jpeg'){
                                            $extn = 'jpeg';
                                        } elseif($proof_type == 'image/gif'){
                                            $extn = 'gif';
                                        } else {
                                            $extn = 'png';
                                        }
                                        $data               = base64_decode($img);
                                        $fileName           = uniqid() . '.' . $extn;
                                        $file               = 'public/uploads/student/' . $fileName;
                                        $success            = file_put_contents($file, $data);
                                        $guardian_id_image      = $fileName;
                                    } else {
                                        $apiStatus          = FALSE;
                                        http_response_code(404);
                                        $apiMessage         = 'Please Upload Image !!!';
                                        $apiExtraField      = 'response_code';
                                        $apiExtraData       = http_response_code();
                                    }
                                } else {
                                    $apiStatus          = FALSE;
                                    http_response_code(404);
                                    $apiMessage         = 'Please Upload Image !!!';
                                    $apiExtraField      = 'response_code';
                                    $apiExtraData       = http_response_code();
                                }
                            /* guardian id proof */
                            $postData       = $requestData;
                            $dob            = date_format(date_create($postData['dob']), "Y-m-d");
                            $fields         = [
                                'sl_no'                     => $next_sl_no,
                                'student_no'                => $student_no,
                                'center_id'                 => $postData['center'],
                                'name'                      => $postData['student_name'],
                                'address'                   => $postData['address'],
                                'country_id'                => $postData['country'],
                                'state_id'                  => $postData['state'],
                                'district_id'               => $postData['district'],
                                'locality'                  => $postData['city'],
                                'pincode'                   => $postData['pin_code'],
                                'landmark'                  => $postData['landmark'],
                                'email'                     => $postData['email'],
                                'phone'                     => $postData['phone_no'],
                                'alt_phone'                 => $postData['alternate_phone'],
                                'whatsapp_no'               => $postData['whatsapp_no'],
                                'created_by'                => (($getUser)?$getUser->name:''),
                                'dob'                       => $dob,
                                'guardian_name'             => $postData['guardian_name'],
                                'guardian_relation'         => $postData['guradian_relation'],
                                'source_id'                 => $postData['source'],
                                'student_doc_type_id'       => $postData['student_id_type'],
                                'student_id_proof'          => $student_id_image,
                                'guardian_doc_type_id'      => $postData['guardian_id_type'],
                                'guardian_id_proof'         => $guardian_id_image,
                                'student_photo'             => $student_image,
                                'current_label_id'          => $postData['level'],
                                'current_label_marks'       => $postData['label_marks'],
                                'status'                    => 0,
                            ];
                            // Helper::pr($fields);
                            $student_id = Student::insertGetId($fields);
                            /* student label wise marks */
                                if($postData['label_marks'] != ''){
                                    $checkStudentMarks = StudentLabelMark::where('student_id', '=', $student_id)->where('label_id', '=', $postData['level'])->first();
                                    if($checkStudentMarks){
                                        // edit
                                        $fields2 = [
                                            'label_marks' => $postData['label_marks']
                                        ];
                                        StudentLabelMark::where('id', '=', $checkStudentMarks->id)->update($fields2);
                                    } else {
                                        // add
                                        $fields2 = [
                                            'student_id'    => $student_id,
                                            'label_id'      => $postData['level'],
                                            'label_marks'   => $postData['label_marks']
                                        ];
                                        StudentLabelMark::insert($fields2);
                                    }
                                }
                            /* student label wise marks */
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Student Added Successfully !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'Teacher Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function editStudent(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'student_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $student_id = $requestData['student_id'];
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Student::where('id', '=', $student_id)->first();
                        if($getUser){
                            // $getCenter      = Center::select('name')->where('id', '=', $getUser->center_id)->first();
                            // $getLevel       = Label::select('name')->where('id', '=', $getUser->current_label_id)->first();
                            // $getCountry     = Country::select('name')->where('id', '=', $getUser->country_id)->first();
                            // $getState       = State::select('name')->where('id', '=', $getUser->state_id)->first();
                            // $getDistrict    = District::select('name')->where('id', '=', $getUser->district_id)->first();
                            // $getSource      = Source::select('name')->where('id', '=', $getUser->source_id)->first();
                            // $getStudentDocType     = DocumentType::select('name')->where('id', '=', $getUser->student_doc_type_id)->first();
                            // $getGuardianDocType     = DocumentType::select('name')->where('id', '=', $getUser->guardian_doc_type_id)->first();
                            $apiResponse = [
                                'user_id'                       => $student_id,
                                'student_no'                    => $getUser->student_no,
                                'center_id'                     => $getUser->center_id,
                                'name'                          => $getUser->name,
                                'address'                       => $getUser->address,
                                'country_id'                    => $getUser->country_id,
                                'state_id'                      => $getUser->state_id,
                                'district_id'                   => $getUser->district_id,
                                'locality'                      => $getUser->locality,
                                'pincode'                       => $getUser->pincode,
                                'landmark'                      => $getUser->landmark,
                                'email'                         => $getUser->email,
                                'phone'                         => $getUser->phone,
                                'alt_phone'                     => (($getUser->alt_phone != '')?$getUser->alt_phone:''),
                                'whatsapp_no'                   => $getUser->whatsapp_no,
                                'dob'                           => $getUser->dob,
                                'guardian_name'                 => $getUser->guardian_name,
                                'guardian_relation'             => $getUser->guardian_relation,
                                'source_id'                     => $getUser->source_id,
                                'student_doc_type_id'           => $getUser->student_doc_type_id,
                                'student_id_proof'              => (($getUser->student_id_proof != '')?env('UPLOADS_URL').'student/'.$getUser->student_id_proof:env('NO_USER_IMAGE')),
                                'guardian_doc_type_id'          => $getUser->guardian_doc_type_id,
                                'guardian_id_proof'             => (($getUser->guardian_id_proof != '')?env('UPLOADS_URL').'student/'.$getUser->guardian_id_proof:env('NO_USER_IMAGE')),
                                'student_photo'                 => (($getUser->student_photo != '')?env('UPLOADS_URL').'student/'.$getUser->student_photo:env('NO_USER_IMAGE')),
                                'current_label_id'              => $getUser->current_label_id,
                                'current_label_marks'           => $getUser->current_label_marks,
                            ];
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Data Available !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function updateStudent(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'student_id', 'student_name', 'guardian_name', 'guradian_relation', 'dob', 'phone_no', 'whatsapp_no', 'address', 'city', 'landmark', 'country', 'state', 'district', 'pin_code', 'center', 'level', 'source', 'email'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Teacher::where('id', '=', $uId)->first();
                        if($getUser){
                            $student_id     = $requestData['student_id'];
                            $getStudent     = Student::where('id', '=', $student_id)->first();
                            if($getStudent){
                                /* student photo */
                                    $student_image  = $requestData['student_image'];
                                    if(!empty($student_image)){
                                        $student_image      = $student_image;
                                        $upload_type        = $student_image['type'];
                                        if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                            $upload_base64      = $student_image['base64'];
                                            $img                = $upload_base64;
                                            $proof_type         = $student_image['type'];
                                            if($proof_type == 'image/png'){
                                                $extn = 'png';
                                            } elseif($proof_type == 'image/jpg'){
                                                $extn = 'jpg';
                                            } elseif($proof_type == 'image/jpeg'){
                                                $extn = 'jpeg';
                                            } elseif($proof_type == 'image/gif'){
                                                $extn = 'gif';
                                            } else {
                                                $extn = 'png';
                                            }
                                            $data               = base64_decode($img);
                                            $fileName           = uniqid() . '.' . $extn;
                                            $file               = 'public/uploads/student/' . $fileName;
                                            $success            = file_put_contents($file, $data);
                                            $student_image      = $fileName;
                                        } else {
                                            $apiStatus          = FALSE;
                                            http_response_code(404);
                                            $apiMessage         = 'Please Upload Image !!!';
                                            $apiExtraField      = 'response_code';
                                            $apiExtraData       = http_response_code();
                                        }
                                    } else {
                                        $student_image      = $getStudent->student_photo;
                                    }
                                /* student photo */
                                /* student id proof */
                                    $student_id_image  = $requestData['student_id_image'];
                                    if(!empty($student_id_image)){
                                        $student_id_image      = $student_id_image;
                                        $upload_type        = $student_id_image['type'];
                                        if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                            $upload_base64      = $student_id_image['base64'];
                                            $img                = $upload_base64;
                                            $proof_type         = $student_id_image['type'];
                                            if($proof_type == 'image/png'){
                                                $extn = 'png';
                                            } elseif($proof_type == 'image/jpg'){
                                                $extn = 'jpg';
                                            } elseif($proof_type == 'image/jpeg'){
                                                $extn = 'jpeg';
                                            } elseif($proof_type == 'image/gif'){
                                                $extn = 'gif';
                                            } else {
                                                $extn = 'png';
                                            }
                                            $data               = base64_decode($img);
                                            $fileName           = uniqid() . '.' . $extn;
                                            $file               = 'public/uploads/student/' . $fileName;
                                            $success            = file_put_contents($file, $data);
                                            $student_id_image      = $fileName;
                                        } else {
                                            $apiStatus          = FALSE;
                                            http_response_code(404);
                                            $apiMessage         = 'Please Upload Image !!!';
                                            $apiExtraField      = 'response_code';
                                            $apiExtraData       = http_response_code();
                                        }
                                    } else {
                                        $student_id_image      = $getStudent->student_id_proof;
                                    }
                                /* student id proof */
                                /* guardian id proof */
                                    $guardian_id_image  = $requestData['guardian_id_image'];
                                    if(!empty($guardian_id_image)){
                                        $guardian_id_image      = $guardian_id_image;
                                        $upload_type        = $guardian_id_image['type'];
                                        if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                            $upload_base64      = $guardian_id_image['base64'];
                                            $img                = $upload_base64;
                                            $proof_type         = $guardian_id_image['type'];
                                            if($proof_type == 'image/png'){
                                                $extn = 'png';
                                            } elseif($proof_type == 'image/jpg'){
                                                $extn = 'jpg';
                                            } elseif($proof_type == 'image/jpeg'){
                                                $extn = 'jpeg';
                                            } elseif($proof_type == 'image/gif'){
                                                $extn = 'gif';
                                            } else {
                                                $extn = 'png';
                                            }
                                            $data               = base64_decode($img);
                                            $fileName           = uniqid() . '.' . $extn;
                                            $file               = 'public/uploads/student/' . $fileName;
                                            $success            = file_put_contents($file, $data);
                                            $guardian_id_image      = $fileName;
                                        } else {
                                            $apiStatus          = FALSE;
                                            http_response_code(404);
                                            $apiMessage         = 'Please Upload Image !!!';
                                            $apiExtraField      = 'response_code';
                                            $apiExtraData       = http_response_code();
                                        }
                                    } else {
                                        $guardian_id_image      = $getStudent->guardian_id_proof;
                                    }
                                /* guardian id proof */
                                $postData       = $requestData;
                                $dob            = date_format(date_create($postData['dob']), "Y-m-d");
                                $fields         = [
                                    'center_id'                 => $postData['center'],
                                    'name'                      => $postData['student_name'],
                                    'address'                   => $postData['address'],
                                    'country_id'                => $postData['country'],
                                    'state_id'                  => $postData['state'],
                                    'district_id'               => $postData['district'],
                                    'locality'                  => $postData['city'],
                                    'pincode'                   => $postData['pin_code'],
                                    'landmark'                  => $postData['landmark'],
                                    'email'                     => $postData['email'],
                                    'phone'                     => $postData['phone_no'],
                                    'alt_phone'                 => $postData['alternate_phone'],
                                    'whatsapp_no'               => $postData['whatsapp_no'],
                                    'created_by'                => (($getUser)?$getUser->name:''),
                                    'dob'                       => $dob,
                                    'guardian_name'             => $postData['guardian_name'],
                                    'guardian_relation'         => $postData['guradian_relation'],
                                    'source_id'                 => $postData['source'],
                                    'student_doc_type_id'       => $postData['student_id_type'],
                                    'student_id_proof'          => $student_id_image,
                                    'guardian_doc_type_id'      => $postData['guardian_id_type'],
                                    'guardian_id_proof'         => $guardian_id_image,
                                    'student_photo'             => $student_image,
                                    'current_label_id'          => $postData['level'],
                                    'current_label_marks'       => $postData['label_marks'],
                                ];
                                // Helper::pr($fields);
                                $student_id = Student::where('id', '=', $student_id)->update($fields);
                                /* student label wise marks */
                                    if($postData['label_marks'] != ''){
                                        $checkStudentMarks = StudentLabelMark::where('student_id', '=', $student_id)->where('label_id', '=', $postData['level'])->first();
                                        if($checkStudentMarks){
                                            // edit
                                            $fields2 = [
                                                'label_marks' => $postData['label_marks']
                                            ];
                                            StudentLabelMark::where('id', '=', $checkStudentMarks->id)->update($fields2);
                                        } else {
                                            // add
                                            $fields2 = [
                                                'student_id'    => $student_id,
                                                'label_id'      => $postData['level'],
                                                'label_marks'   => $postData['label_marks']
                                            ];
                                            StudentLabelMark::insert($fields2);
                                        }
                                    }
                                /* student label wise marks */
                                $apiStatus          = TRUE;
                                $apiMessage         = 'Student Updated Successfully !!!';
                            } else {
                                $apiStatus          = FALSE;
                                $apiMessage         = 'Student Not Found !!!';
                            }
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'Teacher Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function deleteAccount(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Teacher::where('id', '=', $uId)->first();
                        if($getUser){
                            $fields = [
                                'user_type'                 => 'teacher',
                                'entity_name'               => $getUser->name,
                                'email'                     => $getUser->email,
                                'is_email_verify'           => 1,
                                'phone'                     => $getUser->phone,
                                'is_phone_verify'           => 1,
                            ];
                            DeleteAccountRequest::insert($fields);

                            $apiStatus          = TRUE;
                            $apiMessage         = 'Account Delete Requests Submitted Successfully !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function myCenter(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $checkUserTokenExist        = UserDevice::where('app_access_token', '=', $app_access_token)->where('published', '=', 1)->first();
                if($checkUserTokenExist){
                    $getTokenValue              = $this->tokenAuth($app_access_token);
                    if($getTokenValue['status']){
                        $uId        = $getTokenValue['data'][1];
                        $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                        $getUser    = Teacher::where('id', '=', $uId)->first();
                        if($getUser){
                            // Helper::pr($getUser);
                            $assigned_center_id = json_decode($getUser->assigned_center_id);
                            if(!empty($assigned_center_id)){
                                for($c=0;$c<count($assigned_center_id);$c++){
                                    $center_id = $assigned_center_id[$c];
                                    $getCenter    = Center::where('id', '=', $center_id)->first();
                                    if($getCenter){
                                        $current_time   = date('H:i');
                                        $getNextClass   = CenterTimeSlot::select('start_time')->where('center_id', '=', $center_id)->where('start_time', '>=', $current_time)->first();
                                        $getAllClass    = CenterTimeSlot::select('start_time', 'end_time')->where('center_id', '=', $center_id)->get();
                                        $all_class      = [];
                                        if($getAllClass){
                                            foreach($getAllClass as $getAllClass){
                                                $all_class[]      = [
                                                    'start_time'    => $getAllClass->start_time,
                                                    'end_time'      => $getAllClass->end_time,
                                                ];
                                            }
                                        }
                                        
                                        $apiResponse[]  = [
                                            'center_no'             => $getCenter->center_no,
                                            'center_name'           => $getCenter->name,
                                            'center_address'        => $getCenter->address,
                                            'center_phone'          => $getCenter->phone,
                                            'center_whatsapp_no'    => $getCenter->whatsapp_no,
                                            'next_class'            => (($getNextClass)?date('l').' '.date_format(date_create($getNextClass->start_time), "h:i A"):''),
                                            'all_class'             => $all_class
                                        ];
                                    }
                                }
                            }
                            $apiStatus          = TRUE;
                            $apiMessage         = 'Data Available !!!';
                        } else {
                            $apiStatus          = FALSE;
                            $apiMessage         = 'User Not Found !!!';
                        }
                    } else {
                        $apiStatus                      = FALSE;
                        $apiMessage                     = $getTokenValue['data'];
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = 'Something Went Wrong !!!';
                }               
            } else {
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
    /* after login */

    /*
    Get http response code
    Author : Subhomoy
    */
    private function getResponseCode($code = NULL){
        if ($code !== NULL) {
            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Unauthenticated Request !!!'; break;
                case 401: $text = 'Token Not Found !!!'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Token Has Expired !!!'; break;
                case 404: $text = 'User Not Found !!!'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'All Data Are Not Present !!!'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
            }
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            header($protocol . ' ' . $code . ' ' . $text);
            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
            $text = '';
        }
        return $text;
    }
    /*
    Generate JWT tokens for authentication
    Author : Subhomoy
    */
    private static function generateToken($userId, $email, $phone){
        $token      = array(
            'id'                => $userId,
            'email'             => $email,
            'phone'             => $phone,
            'exp'               => time() + (30 * 24 * 60 * 60) // 30 days
        );
        // pr($token);
        return JWT::encode($token, TOKEN_SECRET, 'HS256');
    }
    /*
    Check Authentication
    Author : Subhomoy
    */
    private function tokenAuth($appAccessToken){
        $headers = apache_request_headers();
        if (isset($appAccessToken) && !empty($appAccessToken)) :
            $userdata = $this->matchToken($appAccessToken);
            // pr($userdata);
            if ($userdata['status']) :
                $checkToken =  UserDevice::where('user_id', '=', $userdata['data']->id)->where('app_access_token', '=', $appAccessToken)->first();
                // echo $this->db->last_query();
                // pr($userdata);
                if (!empty($checkToken)) :
                    if ($userdata['data']->exp && $userdata['data']->exp > time()) :
                        $tokenStatus = array(TRUE, $userdata['data']->id, $userdata['data']->email, $userdata['data']->phone, $userdata['data']->exp);
                    else :
                        $tokenStatus = array(FALSE, 'Token Has Expired 1 !!!');
                    endif;
                else :
                    $tokenStatus = array(FALSE, 'Token Has Expired 2 !!!');
                endif;
            else :
                $tokenStatus = array(FALSE, 'Token Not Found !!!');
            endif;
        else :
            $tokenStatus = array(FALSE, 'Token Not Found In Request !!!');
        endif;
        if ($tokenStatus[0]) :
            $this->userId           = $tokenStatus[1];
            $this->userEmail        = $tokenStatus[2];
            $this->userMobile       = $tokenStatus[3];
            $this->userExpiry       = $tokenStatus[4];
            // pr($tokenStatus);
            return array('status' => TRUE, 'data' => $tokenStatus);
        else :
            return array('status' => FALSE, 'data' => $tokenStatus[1]);
            // $this->response_to_json(FALSE, $tokenStatus[1]);
        endif;
    }
    /*
    Match JWT token with user token saved in database
    Author : Subhomoy
    */
    private static function matchToken($token){
        // try{
        //     // $decoded    = JWT::decode($token, TOKEN_SECRET, 'HS256');
        //     $decoded    = JWT::decode($token, new Key(TOKEN_SECRET, 'HS256'));
        //     // pr($decoded);
        // } catch (\Exception $e) {
        //     //echo 'Caught exception: ',  $e->getMessage(), "\n";
        //     return array('status' => FALSE, 'data' => '');
        // }
        
        // return array('status' => TRUE, 'data' => $decoded);


        try{
            $key = "1234567890qwertyuiopmnbvcxzasdfghjkl";
            $decoded = JWT::decode($token, $key, array('HS256'));
            // $decodedData = (array) $decoded;
        } catch (\Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            return array('status' => FALSE, 'data' => '');
        }
        return array('status' => TRUE, 'data' => $decoded);
    }
}
