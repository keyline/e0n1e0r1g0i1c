<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Companies;
use App\Models\Client;
use App\Models\ClientCheckIn;
use App\Models\ClientOrder;
use App\Models\ClientType;
use App\Models\Country;
use App\Models\Employees;
use App\Models\EmployeeType;
use App\Models\District;
use App\Models\Role;
use App\Models\State;
use Auth;
use Session;
use Helper;
use Hash;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Client : ',
            'controller'        => 'ClientController',
            'controller_route'  => 'clients',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($slug){
            $data['slug']                   = $slug;
            $data['module']                 = $this->data;
            $title                          = ucfirst($data['slug']).' List';
            $page_name                      = 'client.list';
            $sessionType                    = Session::get('type');
            $data['client_type']            = ClientType::where('status', '!=', 3)->where('slug', '=', $data['slug'])->orderBy('id', 'ASC')->first();
            $data['rows']                   = Client::where('status', '!=', 3)->where('client_type_id', '=', $data['client_type']->id)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request, $slug){
            $data['module']             = $this->data;    
            $data['slug']               = $slug;
            $data['client_type']        = ClientType::where('status', '!=', 3)->where('slug', '=', $data['slug'])->orderBy('id', 'ASC')->first();
            $data['districts']          = District::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
            $data['countries']          = Country::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
            $data['states']             = State::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
                                    
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    // 'email'                 => 'required',
                    'phone'                 => 'required',
                    'whatsapp_no'           => 'required',
                    'address'               => 'required',
                    'district_id'           => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Client::where('name', '=', $postData['name'])->count();
                    if($checkValue <= 0){
                        $sessionData    = Auth::guard('admin')->user();
                        $prefix         = (($data['client_type'])?$data['client_type']->prefix:'');
                        /* profile image */
                            $imageFile      = $request->file('profile_image');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('profile_image', $imageName, 'client', 'image');
                                if($uploadedFile['status']){
                                    $profile_image = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $profile_image = '';
                            }
                        /* profile image */
                        /* generate client no  */
                            $getLastEnquiry = Client::orderBy('id', 'DESC')->first();
                            if($getLastEnquiry){
                                $sl_no                  = $getLastEnquiry->sl_no;
                                $next_sl_no             = $sl_no + 1;
                                $next_sl_no_string      = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                                $client_no            = $prefix.$next_sl_no_string;
                            } else {
                                $next_sl_no             = 1;
                                $next_sl_no_string      = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                                $client_no            = $prefix.$next_sl_no_string;
                            }
                        /* generate client no */
                        $fields = [
                            'company_id'                => session('company_id'),
                            'client_type_id'            => $data['client_type']->id,
                            'sl_no'                     => $next_sl_no,
                            'client_no'                 => $client_no,
                            'name'                      => $postData['name'],
                            'email'                     => $postData['email'],
                            'alt_email'                 => $postData['alt_email'],
                            'phone'                     => $postData['phone'],
                            'whatsapp_no'               => $postData['whatsapp_no'],
                            'short_bio'                 => $postData['short_bio'],
                            'district_id'               => $postData['district_id'],
                            'address'                   => $postData['address'],
                            'country'                   => $postData['country'],
                            'state'                     => $postData['state'],
                            'city'                      => $postData['city'],
                            'locality'                  => $postData['locality'],
                            'street_no'                 => $postData['street_no'],
                            'zipcode'                   => $postData['zipcode'],
                            'latitude'                  => $postData['latitude'],
                            'longitude'                 => $postData['longitude'],
                            'profile_image'             => $profile_image,
                            'created_by'                => $sessionData->id,
                        ];
                        // Helper::pr($fields);
                        Client::insert($fields);
                        return redirect("admin/" . $this->data['controller_route'] ."/".$data['slug']. "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                    } else {
                        return redirect()->back()->with('error_message', $this->data['title'].' Already Exists !!!');
                    }
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;                          
            $title                          = ucfirst($data['slug']).' Add';
            $page_name                      = 'client.add-edit';
            $data['row']                    = [];                         
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $slug, $id, ){
            $data['module']                 = $this->data;
            $data['slug']                   = $slug;            
            $id                             = Helper::decoded($id);
            $title                          = ucfirst($data['slug']).' Update';
            $page_name                      = 'client.add-edit';
            $data['row']                    = Client::where($this->data['primary_key'], '=', $id)->first();
            $data['client_type']            = ClientType::where('status', '!=', 3)->where('slug', '=', $data['slug'])->orderBy('id', 'ASC')->first();
            $data['districts']              = District::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
            $data['countries']              = Country::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
            $data['states']             = State::select('id', 'name')->where('status', '=', 1)->orderBy('name', 'ASC')->get();
            
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    // 'email'                 => 'required',                    
                    'phone'                 => 'required',
                    'whatsapp_no'           => 'required',
                    'address'               => 'required',
                    'district_id'           => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Client::where('name', '=', $postData['name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        $sessionData = Auth::guard('admin')->user();
                        /* profile image */
                            $imageFile      = $request->file('profile_image');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('profile_image', $imageName, 'client', 'image');
                                if($uploadedFile['status']){
                                    $profile_image = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $profile_image = $data['row']->profile_image;
                            }
                        /* profile image */
                        $fields = [
                                'company_id'                => session('company_id'),
                                'client_type_id'            => $data['client_type']->id,
                                'name'                      => $postData['name'],
                                'email'                     => $postData['email'],
                                'alt_email'                 => $postData['alt_email'],
                                'phone'                     => $postData['phone'],
                                'whatsapp_no'               => $postData['whatsapp_no'],
                                'short_bio'                 => $postData['short_bio'],
                                'district_id'               => $postData['district_id'],
                                'address'                   => $postData['address'],
                                'country'                   => $postData['country'],
                                'state'                     => $postData['state'],
                                'city'                      => $postData['city'],
                                'locality'                  => $postData['locality'],
                                'street_no'                 => $postData['street_no'],
                                'zipcode'                   => $postData['zipcode'],
                                'latitude'                  => $postData['latitude'],
                                'longitude'                 => $postData['longitude'],
                                'profile_image'             => $profile_image,
                                'created_by'                => $sessionData->id,
                                'updated_by'                => $sessionData->id,
                                'updated_at'                => date('Y-m-d H:i:s')
                            ];
                        // Helper::pr($fields);
                        Client::where($this->data['primary_key'], '=', $id)->update($fields);                        
                        return redirect("admin/" . $this->data['controller_route'] ."/".$data['slug']. "/list")->with('success_message', $this->data['title']."/".$data['slug'].' Updated Successfully !!!');
                    } else {
                        return redirect()->back()->with('error_message', $this->data['title'].' Already Exists !!!');
                    }
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* edit */
    /* delete */
        public function delete(Request $request, $slug, $id){
            $id                             = Helper::decoded($id);
            $fields = [
                'status'             => 3
            ];
            Client::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/" . $slug . "/list")->with('success_message', ucfirst($slug).' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $slug, $id){
            $id                             = Helper::decoded($id);
            $model                          = Client::find($id);
            if ($model->status == 1)
            {
                $model->status  = 0;
                $msg            = 'Deactivated';
            } else {
                $model->status  = 1;
                $msg            = 'Activated';
            }            
            $model->save();
            return redirect("admin/" . $this->data['controller_route'] . "/" . $slug . "/list")->with('success_message', ucfirst($slug).' '.$msg.' Successfully !!!');
        }
    /* change status */
    // view details
    public function viewDetails($slug, $id)
    {
        \DB::enableQueryLog();
        // dd($id);
        $id                             = Helper::decoded($id);       
        $data['module']                 = $this->data;
        $data['slug']                   = $slug;        
        $page_name                      = 'client.view_details';
        $data['row']                    = Client::where('status', '!=', 3)->where('id', '=', $id)->orderBy('id', 'DESC')->first();     
        $data['order']                  = ClientOrder::where('status', '!=', 3)->where('client_id', '=', $id)->orderBy('id', 'DESC')->get(); 
        $data['checkin']                = ClientCheckIn::where('status', '!=', 3)->where('client_id', '=', $id)->orderBy('id', 'DESC')->get(); 
        // Display the SQL query
            // dd(\DB::getQueryLog());
            //   Helper::pr($data['order']);
        $title                          = $this->data['title'] . ' View Details : ' . (($data['row'])?$data['row']->name:'');
        echo $this->admin_after_login_layout($title, $page_name, $data);
    }
    // view details
    public function viewOrderDetails($slug, $id)
    {
        // dd($id);
        $id                             = Helper::decoded($id);       
        $data['module']                 = $this->data;
        $data['slug']                   = $slug;        
        $page_name                      = 'client.view_order_details';
        $rows                           = DB::table('client_order_details')
                                            ->join('client_orders', 'client_orders.id', '=', 'client_order_details.order_id')
                                            ->join('products', 'products.id', '=', 'client_order_details.product_id')
                                            ->join('units', 'units.id', '=', 'client_order_details.case_unit')
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
    public function clientwiseorderListRecords(Request $request)
    {
        // Retrieve query parameters
        $orderId = $request->query('orderId');
        $name = $request->query('name');

        // Ensure $orderId is present
        if (empty($orderId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order ID is required.'
            ], 400);
        }

        // Use Laravel's query builder for safe and efficient SQL generation
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
                'sizes.name as size_name',
                'units.name as unit_name',
                'created_by_admins.name as created_by',
                'updated_by_admins.name as updated_by'
            )
            ->where('client_order_details.order_id', $orderId)
            ->get();

        // Start building the HTML response
        $html = '<div class="modal-header" style="justify-content: center;">
                    <h6 class="modal-title">Orders Details for <b><u>' . htmlspecialchars($name) . '</u></b></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="table-responsive table-card">
                            <table class="table general_table_style">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order No</th>
                                        <th>Product Name</th>
                                        <th>Order Unit</th>
                                        <th>Order Qty</th>
                                        <th>Rate</th>
                                        <th>Subtotal</th>
                                        <th>Created Info</th>
                                        <th>Updated Info</th>
                                    </tr>
                                </thead>
                                <tbody>';

        // Add rows to the table
        if ($rows->isNotEmpty()) {
            $sl = 1;
            foreach ($rows as $record) {
                $html .= '<tr>
                            <td>' . $sl++ . '</td>
                            <td>' . htmlspecialchars($record->order_no) . '</td>
                            <td>' . htmlspecialchars($record->product_name) . '</td>
                            <td>' . htmlspecialchars($record->size_name) . ' ' . htmlspecialchars($record->unit_name) . '</td>
                            <td>' . htmlspecialchars($record->qty) . '</td>
                            <td>' . htmlspecialchars($record->rate) . '</td>
                            <td>' . htmlspecialchars($record->subtotal) . '</td>
                            <td>' . htmlspecialchars($record->created_by) . '<br>' . date('M d Y h:i A', strtotime($record->created_at)) . '</td>
                            <td>' . htmlspecialchars($record->updated_by) . '<br>' . date('M d Y h:i A', strtotime($record->updated_at)) . '</td>
                        </tr>';
            }
        } else {
            $html .= '<tr>
                        <td colspan="9" class="text-center">No records found for this order.</td>
                    </tr>';
        }

        $html .= '</tbody>
                        </table>
                    </div>
                </div>
            </div>';

        // Return the HTML response
        return response()->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

}
