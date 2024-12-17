<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Admin;
use App\Models\ClientOrder;
use App\Models\Client;
use App\Models\Employees;
use App\Models\EmployeeType;
use Auth;
use Session;
use Helper;
use Hash;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Orders',
            'controller'        => 'OrdersController',
            'controller_route'  => 'orders',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($slug){
            $getOrderStatus                 = $this->get_order_status($slug);
            $data['module']                 = $this->data;
            $data['order_status']           = $slug;
            $title                          = ucwords($slug) . ' ' . $this->data['title'].' List';
            $page_name                      = 'orders.list';
            $data['rows']                   = ClientOrder::where('status', '=', $getOrderStatus)->orderBy('id', 'DESC')->get();
            $sessionData                    = Auth::guard('admin')->user();
            $data['admin']                  = Admin::where('id', '=', $sessionData->id)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* delete */
        public function delete(Request $request, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3
            ];
            Product::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $order_status                   = $request->order_status;
            if($order_status == 1){
                $order_status_name = 'Submitted';
            } elseif($order_status == 2){
                $order_status_name = 'Approved';
            } elseif($order_status == 3){
                $order_status_name = 'Dispatched';
            } elseif($order_status == 4){
                $order_status_name = 'Billed';
            } elseif($order_status == 5){
                $order_status_name = 'Completed';
            }
            $fields = [
                'status'             => $order_status
            ];
            ClientOrder::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Marked As '.$msg.' Successfully !!!');
        }
    /* change status */
    public function viewOrderDetails($id)
    {
        $id                             = Helper::decoded($id);       
        $data['module']                 = $this->data;
        $page_name                      = 'orders.view_order_details';
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
        $data['order_details']          = ClientOrder::where('status', '=', 1)->where('id', '=', $id)->first();                 
        $data['client_details']         = Client::where('status', '=', 1)->where('id', '=', $data['order_details']->client_id)->first();
        $data['employee_details']       = Employees::where('status', '=', 1)->where('id', '=', $data['order_details']->employee_id)->first();
        $data['employee_types']         = EmployeeType::where('status', '=', 1)->where('id', '=', $data['order_details']->employee_type_id)->first();
        $title                          = $this->data['title'] . ' View Order Details : ' . (($data['order_details'])?$data['order_details']->order_no:'');
        echo $this->admin_after_login_layout($title, $page_name, $data);
    }
    public function get_order_status($slug){
        $order_status = 0;
        if($slug == 'submitted'){
            $order_status = 1;
        } elseif($slug == 'approved'){
            $order_status = 2;
        } elseif($slug == 'dispatch'){
            $order_status = 3;
        } elseif($slug == 'billing'){
            $order_status = 4;
        } elseif($slug == 'completed'){
            $order_status = 5;
        }
        return $order_status;
    }
}
