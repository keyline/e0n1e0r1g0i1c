<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\ClientOrder;
use App\Models\Client;
use App\Models\Employees;
use App\Models\EmployeeType;
use Auth;
use Session;
use Helper;
use Hash;
use Illuminate\Support\Facades\DB;

class AttandenceController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Attandence',
            'controller'        => 'AttandenceController',
            'controller_route'  => 'attandence',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(Request $request){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'attandence.list';
            // if($request->isMethod('post')){
            //     $postData = $request->all();
            //     Helper::pr($postData);
            // }
            $currentDate = date('Y-m-d');
            // Split the date into an array
            $dateParts = explode('-', $currentDate);

            // Extract the year and month
            $year = $dateParts[0]; // 2024
            $month = $dateParts[1]; // 12            
            $data['rows']               = Employees::where('status', '=', 1)->orderBy('id', 'ASC')->get();
            
            //  Helper::pr($data['rows']);
            
            $data['employee_types']         = EmployeeType::where('status', '=', 1)->orderBy('id', 'ASC')->get();
            $sessionData = Auth::guard('admin')->user();
            $data['admin'] = Admin::where('id', '=', $sessionData->id)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */    
    public function viewOrderDetails($id)
    {
        // dd($id);
        $id                             = Helper::decoded($id);       
        $data['module']                 = $this->data;
        // $data['slug']                   = $slug;        
        $page_name                      = 'attandence.view_order_details';
        $rows = DB::table('client_order_details')
            ->join('client_orders', 'client_orders.id', '=', 'client_order_details.order_id')
            ->join('products', 'products.id', '=', 'client_order_details.product_id')
            ->join('sizes', 'sizes.id', '=', 'client_order_details.size_id')
            ->join('units', 'units.id', '=', 'client_order_details.unit_id')
            ->join('admins as created_by_admins', 'created_by_admins.id', '=', 'client_order_details.created_by')
            ->join('admins as updated_by_admins', 'updated_by_admins.id', '=', 'client_order_details.updated_by')
            ->select(
                'client_order_details.*',
                'client_orders.order_no',
                'products.name as product_name',
                'products.short_desc as product_short_desc',
                'sizes.name as size_name',
                'units.name as unit_name',
                'created_by_admins.name as created_by',
                'updated_by_admins.name as updated_by'
            )
            ->where('client_order_details.order_id', $id)
            ->get();

        $data['row']                    = $rows;   
        $data['order_details']    = ClientOrder::where('status', '=', 1)->where('id', '=', $id)->first();                 
        $data['client_details']    = Client::where('status', '=', 1)->where('id', '=', $data['order_details']->client_id)->first();                 
        $data['employee_details']    = Employees::where('status', '=', 1)->where('id', '=', $data['order_details']->employee_id)->first();                 
        $data['employee_types']    = EmployeeType::where('status', '=', 1)->where('id', '=', $data['order_details']->employee_type_id)->first();                 
        // Helper::pr($data['order_details'])  ;  
        $title                          = $this->data['title'] . ' View Order Details : ' . (($data['order_details'])?$data['order_details']->order_no:'');
        echo $this->admin_after_login_layout($title, $page_name, $data);
    }

    public function filter(Request $request)
{
    $month = $request->input('month') ?? date('m'); // Default to current month
    $year = $request->input('year') ?? date('Y'); // Default to current year
    $department = $request->input('department') ?? 'all';
    $inactiveStaff = $request->input('inactiveStaff') ?? 0;
    DB::enableQueryLog();
    $query = DB::table('employees')
        ->leftJoin('attendances', function($join) use ($month, $year) {
            $join->on('employees.id', '=', 'attendances.employee_id')
                ->whereMonth('attendances.attendance_date', $month)
                ->whereYear('attendances.attendance_date', $year);
        })
        ->join('employee_types', 'employee_types.id', '=', 'employees.employee_type_id')
        ->select(
            'employees.id as employee_id',
            'employees.name as employee_name',
            'employees.phone',
            'employees.profile_image',
            'employees.employee_no',
            'employees.status as employee_status',
            'employee_types.name as employee_dept',
            DB::raw('COUNT(attendances.attendance_date) as attendance_count')
        )
        ->groupBy('employees.id', 'employees.name', 'employees.phone', 'employees.profile_image', 'employees.employee_no', 'employees.status', 'employee_types.name');

    if ($department !== 'all') {
        $query->where('employees.employee_type_id', $department);
    }

    if ($inactiveStaff) {
        $query->where('employees.status', '=', '0'); // Assuming '0' means inactive
    }

    $employees = $query->get();
    // dd(DB::getQueryLog());

    return response()->json($employees);
}

}
