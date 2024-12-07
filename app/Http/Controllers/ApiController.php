<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Banner;
use App\Models\Country;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\ClientCheckIn;
use App\Models\ClientOrder;
use App\Models\ClientOrderDetail;
use App\Models\State;
use App\Models\District;
use App\Models\DeleteAccountRequest;
use App\Models\GeneralSetting;
use App\Models\EmailLog;
use App\Models\Employees;
use App\Models\EmployeeType;
use App\Models\Notification;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\Size;
use App\Models\Unit;
use App\Models\Enquiry;
use App\Models\UserActivity;
use App\Models\User;
use App\Models\UserDevice;

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
                http_response_code(200);
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
                $checkUser                  = Employees::where('email', '=', $email)->where('status', '=', 1)->first();
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
                        $getEmployeeType        = EmployeeType::select('name')->where('id', '=', $checkUser->employee_type_id)->first();
                        $apiResponse            = [
                            'user_id'               => $user_id,
                            'name'                  => $checkUser->name,
                            'email'                 => $checkUser->email,
                            'phone'                 => $checkUser->phone,
                            'employee_type_name'    => (($getEmployeeType)?$getEmployeeType->name:''),
                            'employee_type_id'      => $checkUser->employee_type_id,
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
        public function signinWithMobile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['phone'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $phone                      = $requestData['phone'];
                $checkUser                  = Employees::where('phone', '=', $phone)->where('status', '=', 1)->first();
                if($checkUser){
                    $remember_token  = rand(1000,9999);
                    Employees::where('id', '=', $checkUser->id)->update(['otp' => $remember_token]);
                    $mailData                   = [
                        'id'    => $checkUser->id,
                        'email' => $checkUser->email,
                        'phone' => $checkUser->phone,
                        'otp'   => $remember_token,
                    ];
                    $generalSetting             = GeneralSetting::find('1');
                    $subject                    = $generalSetting->site_name.' :: SignIn Validate OTP';
                    $message                    = view('email-templates.otp',$mailData);
                    $this->sendMail($checkUser->email, $subject, $message);

                    /* email log save */
                        $postData2 = [
                            'name'                  => $checkUser->name,
                            'email'                 => $checkUser->email,
                            'subject'               => $subject,
                            'message'               => $message
                        ];
                        EmailLog::insert($postData2);
                    /* email log save */
                    /* send sms */
                        $name       = $checkUser->name;
                        $message    = "Dear ".$name.", ".$remember_token." is your verification OTP for ProTime Manager at KEYLINE. Do not share this OTP with anyone for security reasons.";
                        $mobileNo   = (($checkUser)?$checkUser->phone:'');
                        $this->sendSMS($mobileNo,$message);
                    /* send sms */
                    $apiResponse                        = $mailData;
                    $apiStatus                          = TRUE;
                    $apiMessage                         = 'OTP Sent To Email & Phone Validation !!!';
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
        public function signinValidateMobile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['phone', 'otp', 'device_token'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $phone                      = $requestData['phone'];
                $otp                        = $requestData['otp'];
                $device_type                = $headerData['source'][0];
                $device_token               = $requestData['device_token'];
                $fcm_token                  = $requestData['fcm_token'];
                $checkUser                  = Employees::where('phone', '=', $phone)->where('status', '=', 1)->first();
                if($checkUser){
                    if($checkUser->otp == $otp){
                        $objOfJwt               = new CreatorJwt();
                        $app_access_token       = $objOfJwt->GenerateToken($checkUser->id, $checkUser->email, $checkUser->phone);
                        $user_id                = $checkUser->id;
                        Employees::where('id', '=', $user_id)->update(['otp' => 0]);
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
                        $getEmployeeType        = EmployeeType::select('name')->where('id', '=', $checkUser->employee_type_id)->first();
                        $apiResponse            = [
                            'user_id'               => $user_id,
                            'name'                  => $checkUser->name,
                            'email'                 => $checkUser->email,
                            'phone'                 => $checkUser->phone,
                            'employee_type_name'    => (($getEmployeeType)?$getEmployeeType->name:''),
                            'employee_type_id'      => $checkUser->employee_type_id,
                            'device_type'           => $device_type,
                            'device_token'          => $device_token,
                            'fcm_token'             => $fcm_token,
                            'app_access_token'      => $app_access_token,
                        ];
                        $apiStatus                          = TRUE;
                        $apiMessage                         = 'SignIn Successfully !!!';
                    } else {
                        $apiStatus          = FALSE;
                        http_response_code(200);
                        $apiMessage         = 'OTP Mismatched !!!';
                        $apiExtraField      = 'response_code';
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
                $checkEmail = Employees::where('email', '=', $requestData['email'])->first();
                if($checkEmail){
                    $remember_token  = rand(1000,9999);
                    Employees::where('id', '=', $checkEmail->id)->update(['otp' => $remember_token]);
                    $mailData                   = [
                        'id'    => $checkEmail->id,
                        'email' => $checkEmail->email,
                        'otp'   => $remember_token,
                    ];
                    $generalSetting             = GeneralSetting::find('1');
                    $subject                    = $generalSetting->site_name.' :: Forgot Password OTP';
                    $message                    = view('email-templates.otp',$mailData);
                    $this->sendMail($requestData['email'], $subject, $message);

                    /* email log save */
                        $postData2 = [
                            'name'                  => $checkEmail->name,
                            'email'                 => $checkEmail->email,
                            'subject'               => $subject,
                            'message'               => $message
                        ];
                        EmailLog::insert($postData2);
                    /* email log save */

                    $apiResponse                        = $mailData;
                    $apiStatus                          = TRUE;
                    http_response_code(200);
                    $apiMessage                         = 'OTP Sent To Email Validation !!!';
                    $apiExtraField                      = 'response_code';
                    $apiExtraData                       = http_response_code();
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(200);
                    $apiMessage         = 'Email Not Registered With Us !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(200);
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
                $getUser = Employees::where('id', '=', $requestData['id'])->first();
                if($getUser){
                    $remember_token  = $getUser->otp;
                    if($remember_token == $requestData['otp']){
                        Employees::where('id', '=', $requestData['id'])->update(['otp' => 0]);
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
                        http_response_code(200);
                        $apiMessage         = 'OTP Mismatched !!!';
                        $apiExtraField      = 'response_code';
                    }
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(200);
                    $apiMessage         = 'Teacher Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(200);
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
                $getUser    = Employees::where('id', '=', $id)->first();
                if($getUser){
                    $remember_token = rand(1000,9999);
                    $postData = [
                        'otp'        => $remember_token
                    ];
                    Employees::where('id', '=', $id)->update($postData);
                    
                    $mailData                   = [
                        'id'    => $getUser->id,
                        'email' => $getUser->email,
                        'otp'   => $remember_token,
                    ];
                    $generalSetting             = GeneralSetting::find('1');
                    $subject                    = $generalSetting->site_name.' :: Resend OTP';
                    $message                    = view('email-templates.otp',$mailData);
                    $this->sendMail($getUser->email, $subject, $message);

                    /* email log save */
                        $postData2 = [
                            'name'                  => $getUser->name,
                            'email'                 => $getUser->email,
                            'subject'               => $subject,
                            'message'               => $message
                        ];
                        EmailLog::insert($postData2);
                    /* email log save */

                    $apiResponse                        = $mailData;
                    $apiStatus                          = TRUE;
                    http_response_code(200);
                    $apiMessage                         = 'OTP Resend !!!';
                    $apiExtraField                      = 'response_code';
                    $apiExtraData                       = http_response_code();
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(200);
                    $apiMessage         = 'Teacher Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(200);
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
                $getUser = Employees::where('id', '=', $requestData['id'])->first();
                if($getUser){
                    if($requestData['password'] == $requestData['confirm_password']){
                        Employees::where('id', '=', $requestData['id'])->update(['password' => Hash::make($requestData['password'])]);
                        $mailData        = [
                            'id'        => $getUser->id,
                            'name'      => $getUser->name,
                            'email'     => $getUser->email
                        ];

                        $generalSetting             = GeneralSetting::find('1');
                        $subject                    = $generalSetting->site_name.' :: Reset Password';
                        $message                    = view('email-templates.change-password',$mailData);
                        $this->sendMail($getUser->email, $subject, $message);

                        /* email log save */
                            $postData2 = [
                                'name'                  => $getUser->name,
                                'email'                 => $getUser->email,
                                'subject'               => $subject,
                                'message'               => $message
                            ];
                            EmailLog::insert($postData2);
                        /* email log save */
                        
                        $apiStatus                          = TRUE;
                        http_response_code(200);
                        $apiMessage                         = 'Password Reset Successfully !!!';
                        $apiExtraField                      = 'response_code';
                        $apiExtraData                       = http_response_code();
                    } else {
                        $apiStatus          = FALSE;
                        http_response_code(200);
                        $apiMessage         = 'Password & Confirm Password Not Matched !!!';
                        $apiExtraField      = 'response_code';
                    }
                } else {
                    $apiStatus          = FALSE;
                    http_response_code(200);
                    $apiMessage         = 'User Not Found !!!';
                    $apiExtraField      = 'response_code';
                    $apiExtraData       = http_response_code();
                }
            } else {
                http_response_code(200);
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
                        $getUser    = Employees::where('id', '=', $uId)->first();
                        if($getUser){
                            $bannerImages = [];
                            $banners = Banner::select('banner_image')->where('status', '=', 1)->orderBy('id', 'DESC')->get();
                            if($banners){
                                foreach($banners as $banner){
                                    $bannerImages[] = env('UPLOADS_URL').'banners/'.$banner->banner_image;
                                }
                            }
                            $apiResponse = [
                                'bannerImages' => $bannerImages
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
                    $getUser    = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        if(Hash::check($old_password, $getUser->password)){
                            if($new_password == $confirm_password){
                                if($new_password != $old_password){
                                    $fields = [
                                        'password'                  => Hash::make($new_password)
                                    ];
                                    Employees::where('id', '=', $uId)->update($fields);
                                    // new password send mail
                                        $generalSetting                 = GeneralSetting::find('1');
                                        $subject                        = $generalSetting->site_name.' Change Password';
                                        $mailData['name']               = $getUser->name;
                                        $mailData['email']              = $getUser->email;
                                        $message                        = view('email-templates/change-password', $mailData);
                                        $this->sendMail($getUser->email, $subject, $message);
                                    // new password send mail
                                    /* email log save */
                                        $postData2 = [
                                            'name'                  => $getUser->name,
                                            'email'                 => $getUser->email,
                                            'subject'               => $subject,
                                            'message'               => $message
                                        ];
                                        EmailLog::insert($postData2);
                                    /* email log save */
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
                    $getUser    = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        $getEmployeeType     = EmployeeType::select('name')->where('id', '=', $getUser->employee_type_id)->first();
                        $profileData    = [
                            'employee_no'           => $getUser->employee_no,
                            'employee_type_id'      => (($getEmployeeType)?$getEmployeeType->name:''),
                            'name'                  => $getUser->name,
                            'email'                 => $getUser->email,
                            'alt_email'             => $getUser->alt_email,
                            'phone'                 => $getUser->phone,
                            'whatsapp_no'           => $getUser->whatsapp_no,
                            'short_bio'             => $getUser->short_bio,
                            'dob'                   => (($getUser->dob != '')?date_format(date_create($getUser->dob), "M d, Y"):''),
                            'doj'                   => (($getUser->doj != '')?date_format(date_create($getUser->doj), "M d, Y"):''),
                            'qualification'         => (($getUser->qualification != '')?$getUser->qualification:''),
                            'created_at'            => date_format(date_create($getUser->created_at), "M d, Y h:i A"),
                            'profile_image'         => (($getUser->profile_image != '')?env('UPLOADS_URL').'user/'.$getUser->profile_image:env('NO_USER_IMAGE')),
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
                    $getUser    = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        $getEmployeeType     = EmployeeType::select('name')->where('id', '=', $getUser->employee_type_id)->first();
                        $profileData    = [
                            'employee_type_id'      => $getUser->employee_type_id,
                            'name'                  => $getUser->name,
                            'email'                 => $getUser->email,
                            'alt_email'             => $getUser->alt_email,
                            'phone'                 => $getUser->phone,
                            'whatsapp_no'           => $getUser->whatsapp_no,
                            'short_bio'             => $getUser->short_bio,
                            'dob'                   => $getUser->dob,
                            'doj'                   => $getUser->doj,
                            'qualification'         => (($getUser->qualification != '')?$getUser->qualification:''),
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
        public function getEmployeeType(Request $request){
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
                $employeeTypes = EmployeeType::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                if($employeeTypes){
                    foreach ($employeeTypes as $row) {
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
                http_response_code(200);
                $apiStatus          = FALSE;
                $apiMessage         = $this->getResponseCode(http_response_code());
                $apiExtraField      = 'response_code';
                $apiExtraData       = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function updateProfile(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'name', 'whatsapp_no'];
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
                    $getUser    = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        $postData = [
                                    // 'employee_type_id'          => $requestData['employee_type_id'],
                                    'name'                      => $requestData['name'],
                                    'alt_email'                 => $requestData['alt_email'],
                                    'whatsapp_no'               => $requestData['whatsapp_no'],
                                    'short_bio'                 => $requestData['short_bio'],
                                    // 'dob'                       => $requestData['dob'],
                                    // 'doj'                       => $requestData['doj'],
                                    'qualification'             => $requestData['qualification'],
                                ];
                        Employees::where('id', '=', $uId)->update($postData);
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Profile Updated Successfully !!!';
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
                    $getUser    = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        $profile_image  = $requestData['profile_image'];
                        if(!empty($profile_image)){
                            $profile_image      = $profile_image;
                            $upload_type        = $profile_image[0]['type'];
                            if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                $upload_base64      = $profile_image[0]['base64'];
                                $img                = $upload_base64;
                                $proof_type         = $profile_image[0]['type'];
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
                                $file               = 'public/uploads/user/' . $fileName;
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
                            $profile_image = $getUser->profile_image;
                        }
                        $postData = [
                                    'profile_image'         => $profile_image
                                ];
                        Employees::where('id', '=', $uId)->update($postData);
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
                        $getUser    = Employees::where('id', '=', $uId)->first();
                        if($getUser){
                            $getEmployeeType     = EmployeeType::select('name')->where('id', '=', $getUser->employee_type_id)->first();
                            $fields = [
                                'user_type'                 => (($getEmployeeType)?$getEmployeeType->name:''),
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
        public function getNotification(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['page_no'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                $page_no                    = $requestData['page_no'];
                if($getTokenValue['status']){
                    $uId        = $getTokenValue['data'][1];
                    $expiry     = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser    = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        $limit          = 15; // per page elements
                        if($page_no == 1){
                            $offset = 0;
                        } else {
                            $offset = (($limit * $page_no) - $limit); // ((15 * 3) - 15)
                        }
                        $notifications    = Notification::select('id', 'title', 'description', 'send_timestamp', 'users')->where('to_users', '=', $uId)->where('status', '=', 1)->where('is_send', '=', 1)->orderBy('id', 'DESC')->offset($offset)->limit($limit)->get();
                        if($notifications){
                            foreach($notifications as $notification){
                                $users = json_decode($notification->users);
                                if(in_array($uId, $users)){
                                    $apiResponse[]        = [
                                        'id'                    => $notification->id,
                                        'title'                 => $notification->title,
                                        'description'           => $notification->description,
                                        'send_timestamp'        => date_format(date_create($notification->send_timestamp), "M d, Y h:i A"),
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
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function getClientType(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = [];
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
                    $getUser    = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        $client_types    = ClientType::select('id', 'name', 'slug', 'theme_color', 'prefix')->where('status', '=', 1)->orderBy('id', 'ASC')->get();
                        if($client_types){
                            foreach($client_types as $client_type){
                                $apiResponse[]        = [
                                    'id'                    => $client_type->id,
                                    'name'                  => $client_type->name,
                                    'slug'                  => $client_type->slug,
                                    'theme_color'           => $client_type->theme_color,
                                    'prefix'                => $client_type->prefix,
                                ];
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
                $apiStatus          = FALSE;
                $apiMessage         = 'Unauthenticate Request !!!';
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse);
        }
        public function clientList(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'client_type_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId            = $getTokenValue['data'][1];
                    $expiry         = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser        = Employees::where('id', '=', $uId)->first();
                    $client_type_id = $requestData['client_type_id'];
                    if($getUser){
                        $clients    = Client::select('id', 'name', 'email', 'phone', 'address')->where('status', '=', 1)->where('client_type_id', '=', $client_type_id)->orderBy('name', 'ASC')->get();
                        if($clients){
                            foreach($clients as $client){
                                $apiResponse[]        = [
                                    'client_id'             => $client->id,
                                    'name'                  => $client->name,
                                    'email'                 => $client->email,
                                    'phone'                 => $client->phone,
                                    'address'               => $client->address,
                                ];
                            }
                        }
                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Data Available !!!';
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
        public function clientCheckIn(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'client_id', 'checkin_image', 'latitude', 'longitude'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId            = $getTokenValue['data'][1];
                    $expiry         = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser        = Employees::where('id', '=', $uId)->first();
                    $client_id      = $requestData['client_id'];
                    $latitude       = $requestData['latitude'];
                    $longitude      = $requestData['longitude'];
                    if($getUser){
                        $employee_type_id   = $getUser->employee_type_id;
                        $getClient          = Client::select('id', 'name', 'client_type_id')->where('status', '=', 1)->where('id', '=', $client_id)->first();
                        if($getClient){
                            /* upload checkin image */
                                $checkin_image  = $requestData['checkin_image'];
                                if(!empty($checkin_image)){
                                    $checkin_image      = $checkin_image;
                                    $upload_type        = $checkin_image[0]['type'];
                                    if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                        $upload_base64      = $checkin_image[0]['base64'];
                                        $img                = $upload_base64;
                                        $proof_type         = $checkin_image[0]['type'];
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
                                        $file               = 'public/uploads/user/' . $fileName;
                                        $success            = file_put_contents($file, $data);
                                        $checkin_image      = $fileName;

                                        $fields = [
                                            'employee_type_id'      => $employee_type_id,
                                            'employee_id'           => $uId,
                                            'client_type_id'        => $getClient->client_type_id,
                                            'client_id'             => $client_id,
                                            'checkin_image'         => $checkin_image,
                                            'latitude'              => $latitude,
                                            'longitude'             => $longitude,
                                            'created_by'            => $uId,
                                            'updated_by'            => $uId,
                                        ];
                                        // Helper::pr($fields);
                                        ClientCheckIn::insert($fields);
                                        $apiStatus                  = TRUE;
                                        $apiMessage                 = $getUser->name . ' Checked-in To ' . $getClient->name . ' Successfully !!!';
                                        http_response_code(200);
                                        $apiExtraField              = 'response_code';
                                        $apiExtraData               = http_response_code();
                                    } else {
                                        $apiStatus          = FALSE;
                                        http_response_code(200);
                                        $apiMessage         = 'Please Upload Image !!!';
                                        $apiExtraField      = 'response_code';
                                        $apiExtraData       = http_response_code();
                                    }
                                } else {
                                    $apiStatus          = FALSE;
                                    http_response_code(200);
                                    $apiMessage         = 'Please Upload Image !!!';
                                    $apiExtraField      = 'response_code';
                                    $apiExtraData       = http_response_code();
                                }
                            /* upload checkin image */
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Client Not Found !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        }
                    } else {
                        $apiStatus                  = FALSE;
                        $apiMessage                 = 'User Not Found !!!';
                        http_response_code(404);
                        $apiExtraField              = 'response_code';
                        $apiExtraData               = http_response_code();
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(404);
                    $apiExtraField                  = 'response_code';
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus                      = FALSE;
                $apiMessage                     = 'Unauthenticate Request !!!';
                http_response_code(404);
                $apiExtraField                  = 'response_code';
                $apiExtraData                   = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function getProducts(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'client_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId            = $getTokenValue['data'][1];
                    $expiry         = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser        = Employees::where('id', '=', $uId)->first();
                    $client_id      = $requestData['client_id'];
                    if($getUser){
                        $employee_type_id   = $getUser->employee_type_id;
                        $getClient          = Client::select('id', 'name', 'client_type_id')->where('status', '=', 1)->where('id', '=', $client_id)->first();
                        if($getClient){
                            $getProductCats = ProductCategories::select('id', 'category_name')->where('status', '=', 1)->orderBy('category_name', 'ASC')->get();
                            if($getProductCats){
                                foreach($getProductCats as $getProductCat){
                                    $products       = [];
                                    $getProducts    = DB::table('products')
                                                        ->join('sizes', 'products.size_id', '=', 'sizes.id')
                                                        ->join('units', 'products.unit_id', '=', 'units.id')
                                                        ->select('products.*', 'sizes.name as size_name', 'units.name as unit_name')
                                                        ->where('products.category_id', '=', $getProductCat->id)
                                                        ->where('products.status', '=', 1)
                                                        ->orderBy('products.name', 'ASC')
                                                        ->get();
                                    if($getProducts){
                                        foreach($getProducts as $getProduct){
                                            $products[]       = [
                                                'product_id'    => $getProduct->id,
                                                'short_desc'    => $getProduct->short_desc,
                                                'retail_price'  => number_format($getProduct->retail_price,2),
                                                'product_name'  => $getProduct->name,
                                                'product_slug'  => $getProduct->product_slug,
                                                'size_name'     => $getProduct->size_name,
                                                'unit_name'     => $getProduct->unit_name,
                                            ];
                                        }
                                    }
                                    $apiResponse[]  = [
                                        'category_id'   => $getProductCat->id,
                                        'category_name' => $getProductCat->category_name,
                                        'products'      => $products,
                                    ];
                                }
                            }

                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Data Available !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Client Not Found !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        }
                    } else {
                        $apiStatus                  = FALSE;
                        $apiMessage                 = 'User Not Found !!!';
                        http_response_code(404);
                        $apiExtraField              = 'response_code';
                        $apiExtraData               = http_response_code();
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(404);
                    $apiExtraField                  = 'response_code';
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus                      = FALSE;
                $apiMessage                     = 'Unauthenticate Request !!!';
                http_response_code(404);
                $apiExtraField                  = 'response_code';
                $apiExtraData                   = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function placeOrder(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'client_id', 'products', 'order_image', 'client_signature', 'latitude', 'longitude'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId                    = $getTokenValue['data'][1];
                    $expiry                 = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser                = Employees::where('id', '=', $uId)->first();
                    $client_id              = $requestData['client_id'];
                    $products               = $requestData['products'];
                    $order_image            = $requestData['order_image'];
                    $client_signature       = $requestData['client_signature'];
                    $latitude               = $requestData['latitude'];
                    $longitude              = $requestData['longitude'];
                    if($getUser){
                        $employee_type_id   = $getUser->employee_type_id;
                        $getClient          = Client::select('id', 'name', 'client_type_id')->where('status', '=', 1)->where('id', '=', $client_id)->first();
                        if($getClient){
                            if(empty($products)){
                                $apiStatus                  = FALSE;
                                $apiMessage                 = 'Atleast One Product Needed For Place Order !!!';
                                http_response_code(200);
                                $apiExtraField              = 'response_code';
                                $apiExtraData               = http_response_code();
                            } else {
                                if(empty($order_image)){
                                    $apiStatus                  = FALSE;
                                    $apiMessage                 = 'Order Image Is Needed For Place Order !!!';
                                    http_response_code(200);
                                    $apiExtraField              = 'response_code';
                                    $apiExtraData               = http_response_code();
                                } else {
                                    if(empty($client_signature)){
                                        $apiStatus                  = FALSE;
                                        $apiMessage                 = 'Client Signature Is Needed For Place Order !!!';
                                        http_response_code(200);
                                        $apiExtraField              = 'response_code';
                                        $apiExtraData               = http_response_code();
                                    } else {
                                        /* order images */
                                            $order_image        = $order_image;
                                            $orderImages        = [];
                                            if(!empty($order_image)){
                                                for($k=0;$k<count($order_image);$k++){
                                                    $upload_type        = $order_image[$k]['type'];
                                                    if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                                        $upload_base64      = $order_image[$k]['base64'];
                                                        $img                = $upload_base64;
                                                        $proof_type         = $order_image[$k]['type'];
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
                                                        $file               = 'public/uploads/user/' . $fileName;
                                                        $success            = file_put_contents($file, $data);
                                                        $order_img          = $fileName;
                                                        $orderImages[]      = $order_img;
                                                    }
                                                }
                                            }
                                        /* order images */
                                        /* client signature */
                                            $client_signature           = $client_signature;
                                            $upload_type                = $client_signature[0]['type'];
                                            if($upload_type == 'image/jpeg' || $upload_type == 'image/jpg' || $upload_type == 'image/png' || $upload_type == 'image/gif'){
                                                $upload_base64      = $client_signature[0]['base64'];
                                                $img                = $upload_base64;
                                                $proof_type         = $client_signature[0]['type'];
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
                                                $file               = 'public/uploads/user/' . $fileName;
                                                $success            = file_put_contents($file, $data);
                                                $client_sig         = $fileName;

                                                /* generate order no  */
                                                    $getLastEnquiry             = ClientOrder::orderBy('id', 'DESC')->first();
                                                    if($getLastEnquiry){
                                                        $sl_no                  = $getLastEnquiry->sl_no;
                                                        $next_sl_no             = $sl_no + 1;
                                                        $next_sl_no_string      = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                                                        $order_no               = $next_sl_no_string;
                                                    } else {
                                                        $next_sl_no             = 1;
                                                        $next_sl_no_string      = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                                                        $order_no               = $next_sl_no_string;
                                                    }
                                                /* generate order no */
                                                /* order place */
                                                    $fields1                     = [
                                                        'sl_no'                 => $next_sl_no,
                                                        'order_no'              => $order_no,
                                                        'employee_type_id'      => $uId,
                                                        'employee_id'           => $employee_type_id,
                                                        'client_type_id'        => $getClient->client_type_id,
                                                        'client_id'             => $client_id,
                                                        'order_images'          => json_encode($orderImages),
                                                        'client_signature'      => $client_sig,
                                                        'latitude'              => $latitude,
                                                        'longitude'             => $longitude,
                                                        'created_by'            => $uId,
                                                        'updated_by'            => $uId,
                                                    ];
                                                    $order_id   = ClientOrder::insertGetId($fields1);
                                                    $order_amt  = 0;
                                                    if(!empty($products)){
                                                        foreach($products as $product){
                                                            $getProduct          = Product::select('id', 'retail_price', 'size_id', 'unit_id')->where('id', '=', $product['product_id'])->first();
                                                            if($getProduct){
                                                                $rate       = $getProduct->retail_price;
                                                                $subtotal   = ($rate * $product['qty']);
                                                                $fields2                     = [
                                                                    'order_id'              => $order_id,
                                                                    'product_id'            => $product['product_id'],
                                                                    'qty'                   => $product['qty'],
                                                                    'rate'                  => $rate,
                                                                    'subtotal'              => $subtotal,
                                                                    'size_id'               => $getProduct->size_id,
                                                                    'unit_id'               => $getProduct->unit_id,
                                                                    'created_by'            => $uId,
                                                                    'updated_by'            => $uId,
                                                                ];
                                                                ClientOrderDetail::insert($fields2);
                                                            }
                                                            $order_amt += $subtotal;
                                                        }
                                                    }
                                                    $net_total = $order_amt;
                                                    ClientOrder::where('id', '=', $order_id)->update(['net_total' => $net_total]);

                                                    $apiResponse                = [
                                                        'order_no'          => $order_no,
                                                        'net_total'         => number_format($net_total,2),
                                                        'order_timestamp'   => date('M d, Y h:i A'),
                                                    ];
                                                    $apiStatus                  = TRUE;
                                                    $apiMessage                 = $getUser->name . ' Order Placed To ' . $getClient->name . ' Successfully !!!';
                                                    http_response_code(200);
                                                    $apiExtraField              = 'response_code';
                                                    $apiExtraData               = http_response_code();
                                                /* order place */
                                            } else {
                                                $apiStatus                  = FALSE;
                                                $apiMessage                 = 'Client Signature Need To Be Image !!!';
                                                http_response_code(200);
                                                $apiExtraField              = 'response_code';
                                                $apiExtraData               = http_response_code();
                                            }
                                        /* client signature */
                                    }
                                }
                            }
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Client Not Found !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        }
                    } else {
                        $apiStatus                  = FALSE;
                        $apiMessage                 = 'User Not Found !!!';
                        http_response_code(404);
                        $apiExtraField              = 'response_code';
                        $apiExtraData               = http_response_code();
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(404);
                    $apiExtraField                  = 'response_code';
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus                      = FALSE;
                $apiMessage                     = 'Unauthenticate Request !!!';
                http_response_code(404);
                $apiExtraField                  = 'response_code';
                $apiExtraData                   = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function clientWiseOrderList(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'client_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId            = $getTokenValue['data'][1];
                    $expiry         = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser        = Employees::where('id', '=', $uId)->first();
                    $client_id      = $requestData['client_id'];
                    if($getUser){
                        $employee_type_id   = $getUser->employee_type_id;
                        $getClient          = Client::select('id', 'name', 'client_type_id')->where('status', '=', 1)->where('id', '=', $client_id)->first();
                        if($getClient){
                            $getOrders = DB::table('client_orders')
                                                ->join('employees', 'client_orders.employee_id', '=', 'employees.id')
                                                ->join('employee_types', 'client_orders.employee_type_id', '=', 'employee_types.id')
                                                ->select('client_orders.*', 'employees.name as employee_name', 'employee_types.name as employee_type_name')
                                                ->where('client_orders.client_id', '=', $client_id)
                                                ->orderBy('client_orders.id', 'DESC')
                                                ->get();
                            if($getOrders){
                                foreach($getOrders as $getOrder){
                                    $apiResponse[]  = [
                                        'order_id'              => $getOrder->id,
                                        'employee_name'         => $getOrder->employee_name,
                                        'employee_type_name'    => $getOrder->employee_type_name,
                                        'order_no'              => $getOrder->order_no,
                                        'net_total'             => number_format($getOrder->net_total,2),
                                        'order_timestamp'       => date_format(date_create($getOrder->order_timestamp), "M d, y h:i A"),
                                    ];
                                }
                            }

                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Data Available !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Client Not Found !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        }
                    } else {
                        $apiStatus                  = FALSE;
                        $apiMessage                 = 'User Not Found !!!';
                        http_response_code(404);
                        $apiExtraField              = 'response_code';
                        $apiExtraData               = http_response_code();
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(404);
                    $apiExtraField                  = 'response_code';
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus                      = FALSE;
                $apiMessage                     = 'Unauthenticate Request !!!';
                http_response_code(404);
                $apiExtraField                  = 'response_code';
                $apiExtraData                   = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function employeeWiseOrderList(Request $request)
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
                    $uId            = $getTokenValue['data'][1];
                    $expiry         = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser        = Employees::where('id', '=', $uId)->first();
                    if($getUser){
                        $getOrders = DB::table('client_orders')
                                            ->join('clients', 'client_orders.client_id', '=', 'clients.id')
                                            ->join('client_types', 'client_orders.client_type_id', '=', 'client_types.id')
                                            ->select('client_orders.*', 'clients.name as client_name', 'client_types.name as client_type_name')
                                            ->where('client_orders.employee_id', '=', $uId)
                                            ->orderBy('client_orders.id', 'DESC')
                                            ->get();
                        if($getOrders){
                            foreach($getOrders as $getOrder){
                                $apiResponse[]  = [
                                    'order_id'              => $getOrder->id,
                                    'client_name'           => $getOrder->client_name,
                                    'client_type_name'      => $getOrder->client_type_name,
                                    'order_no'              => $getOrder->order_no,
                                    'net_total'             => number_format($getOrder->net_total,2),
                                    'order_timestamp'       => date_format(date_create($getOrder->order_timestamp), "M d, y h:i A"),
                                ];
                            }
                        }

                        $apiStatus                  = TRUE;
                        $apiMessage                 = 'Data Available !!!';
                        http_response_code(200);
                        $apiExtraField              = 'response_code';
                        $apiExtraData               = http_response_code();
                    } else {
                        $apiStatus                  = FALSE;
                        $apiMessage                 = 'User Not Found !!!';
                        http_response_code(404);
                        $apiExtraField              = 'response_code';
                        $apiExtraData               = http_response_code();
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(404);
                    $apiExtraField                  = 'response_code';
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus                      = FALSE;
                $apiMessage                     = 'Unauthenticate Request !!!';
                http_response_code(404);
                $apiExtraField                  = 'response_code';
                $apiExtraData                   = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function orderDetails(Request $request)
        {
            $apiStatus          = TRUE;
            $apiMessage         = '';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $requestData        = $request->all();
            $requiredFields     = ['key', 'source', 'order_id'];
            $headerData         = $request->header();
            if (!$this->validateArray($requiredFields, $requestData)){
                $apiStatus          = FALSE;
                $apiMessage         = 'All Data Are Not Present !!!';
            }
            if($headerData['key'][0] == env('PROJECT_KEY')){
                $app_access_token           = $headerData['authorization'][0];
                $getTokenValue              = $this->tokenAuth($app_access_token);
                if($getTokenValue['status']){
                    $uId            = $getTokenValue['data'][1];
                    $expiry         = date('d/m/Y H:i:s', $getTokenValue['data'][4]);
                    $getUser        = Employees::where('id', '=', $uId)->first();
                    $order_id       = $requestData['order_id'];
                    if($getUser){
                        $getOrder   = DB::table('client_orders')
                                            ->join('employees', 'client_orders.employee_id', '=', 'employees.id')
                                            ->join('employee_types', 'client_orders.employee_type_id', '=', 'employee_types.id')
                                            ->join('clients', 'client_orders.client_id', '=', 'clients.id')
                                            ->join('client_types', 'client_orders.client_type_id', '=', 'client_types.id')
                                            ->select('client_orders.*', 'employees.name as employee_name', 'employee_types.name as employee_type_name', 'clients.name as client_name', 'client_types.name as client_type_name')
                                            ->where('client_orders.id', '=', $order_id)
                                            ->first();
                        if($getOrders){
                            $apiResponse[]  = [
                                'order_id'              => $getOrder->id,
                                'employee_name'         => $getOrder->employee_name,
                                'employee_type_name'    => $getOrder->employee_type_name,
                                'order_no'              => $getOrder->order_no,
                                'latitude'              => $getOrder->latitude,
                                'longitude'             => $getOrder->longitude,
                                'client_signature'      => env('UPLOADS_URL').'user/'.$getOrder->client_signature,
                                'net_total'             => number_format($getOrder->net_total,2),
                                'order_timestamp'       => date_format(date_create($getOrder->order_timestamp), "M d, y h:i A"),
                            ];

                            $apiStatus                  = TRUE;
                            $apiMessage                 = 'Data Available !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        } else {
                            $apiStatus                  = FALSE;
                            $apiMessage                 = 'Order Not Found !!!';
                            http_response_code(200);
                            $apiExtraField              = 'response_code';
                            $apiExtraData               = http_response_code();
                        }
                    } else {
                        $apiStatus                  = FALSE;
                        $apiMessage                 = 'User Not Found !!!';
                        http_response_code(404);
                        $apiExtraField              = 'response_code';
                        $apiExtraData               = http_response_code();
                    }
                } else {
                    $apiStatus                      = FALSE;
                    $apiMessage                     = $getTokenValue['data'];
                    http_response_code(404);
                    $apiExtraField                  = 'response_code';
                    $apiExtraData                   = http_response_code();
                }                                               
            } else {
                $apiStatus                      = FALSE;
                $apiMessage                     = 'Unauthenticate Request !!!';
                http_response_code(404);
                $apiExtraField                  = 'response_code';
                $apiExtraData                   = http_response_code();
            }
            $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
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
