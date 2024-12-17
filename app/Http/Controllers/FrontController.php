<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\OpenAiAuth;
use Illuminate\Http\Request;
use PHPExperts\RESTSpeaker\RESTSpeaker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\Center;
use App\Models\CenterTimeSlot;
use App\Models\Student;
use App\Models\GeneralSetting;
use App\Models\EmailLog;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\Banner;
use App\Models\Teacher;
use App\Models\GalleryCategory;
use App\Models\Gallery;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Enquiry;
use App\Models\UserActivity;
use App\Models\Source;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\UserDevice;

use Auth;
use Session;
use Helper;
use Hash;
use stripe;

class FrontController extends Controller
{
    /* home */
        public function home(){
            echo '<h1 style="text-align:center;">Application is under construction !!!</h1>';
        }
    /* home */
    /* page */
        public function page($slug){
            $data['generalSetting']             = GeneralSetting::find('1');
            $data['page']                       = Page::where('page_slug', '=', $slug)->first();
            $data['title']                      = (($data['page'])?$data['page']->page_name:"Page");
            $page_name                          = 'page-content';
            return view('front.page-content', $data);
        }
    /* page */
    public function cron_for_attendance_notification(){
        /* throw notification */
            $getTemplate = $this->getNotificationTemplates('ATTENDANCE');
            if($getTemplate){
                $getUserFCMTokens   = DB::table('user_devices')
                                        ->select('fcm_token', DB::raw('MIN(user_id) as user_id'))
                                        ->where('fcm_token', '!=', '')
                                        ->groupBy('fcm_token')
                                        ->get();
                $tokens             = [];
                $type               = 'attendance';
                if($getUserFCMTokens){
                    foreach($getUserFCMTokens as $getUserFCMToken){
                        $employee_id        = $getUserFCMToken->user_id;
                        $response           = $this->sendCommonPushNotification($getUserFCMToken->fcm_token, $getTemplate['title'], $getTemplate['description'], $type);
                        $users[]            = $employee_id;
                        $notificationFields = [
                            'title'             => $getTemplate['title'],
                            'description'       => $getTemplate['description'],
                            'to_users'          => $employee_id,
                            'users'             => json_encode($users),
                            'is_send'           => 1,
                            'send_timestamp'    => date('Y-m-d H:i:s'),
                        ];
                        Notification::insert($notificationFields);
                    }
                }
            }
            echo "Attendance notification";
        /* throw notification */
    }
    public function getNotificationTemplates($notificationType){
        $returnArray                    = [];
        $getRandomNotificationTemplate  = NotificationTemplate::select('title', 'description')->where('status', '=', 1)->where('type', '=', $notificationType)->inRandomOrder()->first();
        if($getRandomNotificationTemplate){
            $returnArray                = [
                'title'         => $getRandomNotificationTemplate->title,
                'description'   => $getRandomNotificationTemplate->description,
            ];
        }
        return $returnArray;
    }
}
