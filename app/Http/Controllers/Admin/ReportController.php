<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Attendance;
use App\Models\Odometer;
use App\Models\Admin;
use Auth;
use Session;
use Helper;
use Hash;
use DB;

class ReportController extends Controller
{
    /* attendance report */
        public function attendanceReport(){
            $title                          = 'Attendance Report';
            $page_name                      = 'report.attendance-report';
            $data['rows']                   = DB::table('employees')
                                                        ->join('employee_types', 'employees.employee_type_id', '=', 'employee_types.id')
                                                        ->select('employees.*', 'employee_types.name as employee_type_name')
                                                        ->where('employees.status', '=', 1)
                                                        ->orderBy('employees.id', 'ASC')
                                                        ->get();
            $data['is_search']              = 0;
            $data['month']                  = date('m');
            $data['year']                   = date('Y');
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function attendanceReportSearch(Request $request){
            $postData                       = $request->all();
            $month_year                     = explode("-", $postData['month_year']);
            $month                          = $month_year[1];
            $year                           = $month_year[0];
            $title                          = 'Attendance Report';
            $page_name                      = 'report.attendance-report';
            $data['rows']                   = DB::table('employees')
                                                        ->join('employee_types', 'employees.employee_type_id', '=', 'employee_types.id')
                                                        ->select('employees.*', 'employee_types.name as employee_type_name')
                                                        ->where('employees.status', '=', 1)
                                                        ->orderBy('employees.id', 'ASC')
                                                        ->get();
            $data['is_search']              = 1;
            $data['month']                  = $month;
            $data['year']                   = $year;
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function getAttendanceDetails(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = 'Data Available !!!';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $postData           = $request->all();
            $attn_date          = $postData['date'];
            $uId                = $postData['userId'];
            $name               = $postData['name'];
            $attnDatas          = [];
            $attnList           = Attendance::where('employee_id', '=', $uId)->where('attendance_date', '=', $attn_date)->orderBy('id', 'ASC')->get();
            $tot_attn_time      = 0;
            $isPresent          = 0;
            if($attnList){
                foreach($attnList as $attnRow){
                    if($attnRow->status == 1){
                        $attnDatas[]          = [
                            'punch_date'            => date_format(date_create($attn_date), "M d, Y"),
                            'label'                 => 'IN',
                            'time'                  => date_format(date_create($attnRow->start_timestamp), "h:i A"),
                            'address'               => (($attnRow->start_address != '')?$attnRow->start_address:''),
                            'image'                 => env('UPLOADS_URL').'user/'.$attnRow->start_image,
                            'type'                  => 1
                        ];
                    }
                    if($attnRow->status == 2){
                        $attnDatas[]          = [
                            'punch_date'            => date_format(date_create($attn_date), "M d, Y"),
                            'label'                 => 'IN',
                            'time'                  => date_format(date_create($attnRow->start_timestamp), "h:i A"),
                            'address'               => (($attnRow->start_address != '')?$attnRow->start_address:''),
                            'image'                 => env('UPLOADS_URL').'user/'.$attnRow->start_image,
                            'type'                  => 1
                        ];
                        $attnDatas[]          = [
                            'punch_date'            => date_format(date_create($attn_date), "M d, Y"),
                            'label'                 => 'OUT',
                            'time'                  => (($attnRow->end_timestamp != '')?date_format(date_create($attnRow->end_timestamp), "h:i A"):''),
                            'address'               => (($attnRow->end_address != '')?$attnRow->end_address:''),
                            'image'                 => (($attnRow->end_image != '')?env('UPLOADS_URL').'user/'.$attnRow->end_image:''),
                            'type'                  => 2
                        ];
                    }
                }
            }

            $odometer_list      = Odometer::select('start_km', 'start_image', 'start_timestamp', 'end_km', 'end_image', 'end_timestamp', 'travel_distance', 'status', 'start_address', 'end_address')
                                    ->where('employee_id', $uId)
                                    ->where('odometer_date', '=', $attn_date)
                                    ->orderBy('odometer_date', 'ASC')
                                    ->get();
            $odometer_data      = [];
            if($odometer_list){
                foreach($odometer_list as $odometerRow){
                    $odometer_data[] = [
                        'start_km'              => $odometerRow->start_km,
                        'start_image'           => (($odometerRow->start_image)?env('UPLOADS_URL').'user/'.$odometerRow->start_image:''),
                        'start_timestamp'       => date_format(date_create($odometerRow->start_timestamp), "h:i A"),
                        'start_address'         => $odometerRow->start_address,
                        'end_km'                => $odometerRow->end_km,
                        'end_image'             => (($odometerRow->end_image != '')?env('UPLOADS_URL').'user/'.$odometerRow->end_image:''),
                        'end_timestamp'         => date_format(date_create($odometerRow->end_timestamp), "h:i A"),
                        'end_address'           => $odometerRow->end_address,
                        'travel_distance'       => (($odometerRow->status == 2)?$odometerRow->travel_distance:'NA'),
                    ];
                }
            }
            $data        = [
                'attnDatas'             => $attnDatas,
                'odometer_data'         => $odometer_data,
                'name'                  => $name,
                'attn_date'             => $attn_date,
            ];
            echo $modalHTML = view('admin.maincontents.report.attendance-modal', $data);die;
            // $apiResponse = array('modalHTML' => $modalHTML);
            // $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
    /* attendance report */
    /* odometer report */
        public function odometerReport(){
            $title                          = 'Odometer Report';
            $page_name                      = 'report.odometer-report';
            $data['rows']                   = DB::table('employees')
                                                        ->join('employee_types', 'employees.employee_type_id', '=', 'employee_types.id')
                                                        ->select('employees.*', 'employee_types.name as employee_type_name')
                                                        ->where('employees.status', '=', 1)
                                                        ->orderBy('employees.id', 'ASC')
                                                        ->get();
            $data['is_search']              = 0;
            $data['month']                  = date('m');
            $data['year']                   = date('Y');
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function odometerReportSearch(Request $request){
            $postData                       = $request->all();
            $month_year                     = explode("-", $postData['month_year']);
            $month                          = $month_year[1];
            $year                           = $month_year[0];
            $title                          = 'Odometer Report';
            $page_name                      = 'report.odometer-report';
            $data['rows']                   = DB::table('employees')
                                                        ->join('employee_types', 'employees.employee_type_id', '=', 'employee_types.id')
                                                        ->select('employees.*', 'employee_types.name as employee_type_name')
                                                        ->where('employees.status', '=', 1)
                                                        ->orderBy('employees.id', 'ASC')
                                                        ->get();
            $data['is_search']              = 1;
            $data['month']                  = $month;
            $data['year']                   = $year;
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* odometer report */
    /* odometer details report */
        public function odometerDetailsReport(){
            $title                          = 'Odometer Details Report';
            $page_name                      = 'report.odometer-details-report';
            $data['rows']                   = DB::table('employees')
                                                        ->join('employee_types', 'employees.employee_type_id', '=', 'employee_types.id')
                                                        ->select('employees.*', 'employee_types.name as employee_type_name')
                                                        ->where('employees.status', '=', 1)
                                                        ->orderBy('employees.id', 'ASC')
                                                        ->get();
            $data['is_search']              = 0;
            $data['month']                  = date('m');
            $data['year']                   = date('Y');
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function odometerDetailsReportSearch(Request $request){
            $postData                       = $request->all();
            $month_year                     = explode("-", $postData['month_year']);
            $month                          = $month_year[1];
            $year                           = $month_year[0];
            $title                          = 'Odometer Details Report';
            $page_name                      = 'report.odometer-details-report';
            $data['rows']                   = DB::table('employees')
                                                        ->join('employee_types', 'employees.employee_type_id', '=', 'employee_types.id')
                                                        ->select('employees.*', 'employee_types.name as employee_type_name')
                                                        ->where('employees.status', '=', 1)
                                                        ->orderBy('employees.id', 'ASC')
                                                        ->get();
            $data['is_search']              = 1;
            $data['month']                  = $month;
            $data['year']                   = $year;
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
        public function getOdometerDetails(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = 'Data Available !!!';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $postData           = $request->all();
            $attn_date          = $postData['date'];
            $uId                = $postData['userId'];
            $name               = $postData['name'];

            $odometer_list      = Odometer::select('id', 'start_km', 'start_image', 'start_timestamp', 'end_km', 'end_image', 'end_timestamp', 'travel_distance', 'status', 'start_address', 'end_address')
                                    ->where('employee_id', $uId)
                                    ->where('odometer_date', '=', $attn_date)
                                    ->orderBy('odometer_date', 'ASC')
                                    ->get();
            $odometer_data      = [];
            if($odometer_list){
                foreach($odometer_list as $odometerRow){
                    $odometer_data[] = [
                        'id'                    => $odometerRow->id,
                        'start_km'              => $odometerRow->start_km,
                        'start_image'           => (($odometerRow->start_image)?env('UPLOADS_URL').'user/'.$odometerRow->start_image:''),
                        'start_timestamp'       => date_format(date_create($odometerRow->start_timestamp), "h:i A"),
                        'start_address'         => $odometerRow->start_address,
                        'end_km'                => $odometerRow->end_km,
                        'end_image'             => (($odometerRow->end_image != '')?env('UPLOADS_URL').'user/'.$odometerRow->end_image:''),
                        'end_timestamp'         => date_format(date_create($odometerRow->end_timestamp), "h:i A"),
                        'end_address'           => $odometerRow->end_address,
                        'travel_distance'       => (($odometerRow->status == 2)?$odometerRow->travel_distance:'NA'),
                    ];
                }
            }
            $data        = [
                'odometer_data'         => $odometer_data,
                'name'                  => $name,
                'attn_date'             => $attn_date,
            ];
            echo $modalHTML = view('admin.maincontents.report.odometer-modal', $data);die;
            // $apiResponse = array('modalHTML' => $modalHTML);
            // $this->response_to_json($apiStatus, $apiMessage, $apiResponse, $apiExtraField, $apiExtraData);
        }
        public function updateOdometerDetails(Request $request){
            $apiStatus          = TRUE;
            $apiMessage         = 'Data Available !!!';
            $apiResponse        = [];
            $apiExtraField      = '';
            $apiExtraData       = '';
            $postData           = $request->all();
            $odometerId         = $postData['odometer_id'];     
            $name               = $postData['name'];       
            $odometer           = Odometer::where('id', '=', $odometerId)->first();                
            $odometer_data      = [];    
            if($odometer){
                $odometer_data = [
                    'start_km'              => $odometer->start_km,
                    'start_image'           => (($odometer->start_image)?env('UPLOADS_URL').'user/'.$odometer->start_image:''),
                    'start_timestamp'       => date_format(date_create($odometer->start_timestamp), "h:i A"),
                    'start_address'         => $odometer->start_address,
                    'end_km'                => $odometer->end_km,
                    'end_image'             => (($odometer->end_image != '')?env('UPLOADS_URL').'user/'.$odometer->end_image:''),
                    'end_timestamp'         => date_format(date_create($odometer->end_timestamp), "h:i A"),
                    'end_address'           => $odometer->end_address,
                    'travel_distance'       => (($odometer->status == 2)?$odometer->travel_distance:'NA'),
                ];
            }       
            $data        = [
                'odometer_data'         => $odometer_data,
                'odometerId'            => $odometerId,
                'name'                  => $name,
            ];
            echo $modalHTML = view('admin.maincontents.report.odometer-edit-modal', $data);die;            
        }
    /* odometer details report */
}
