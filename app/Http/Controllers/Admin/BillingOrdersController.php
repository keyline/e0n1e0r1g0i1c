<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Admin;
use App\Models\ClientOrder;
use App\Models\Client;
use App\Models\Employees;
use App\Models\EmployeeType;
use App\Models\ProductCategories;
use App\Models\Size;
use App\Models\Unit;
use Auth;
use Session;
use Helper;
use Hash;
use Illuminate\Support\Facades\DB;

class BillingOrdersController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Billing Orders',
            'controller'        => 'BillingOrdersController',
            'controller_route'  => 'billing_orders',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'billing_orders.list';
            $data['rows']                   = ClientOrder::where('status', '=', 4)->orderBy('id', 'DESC')->get();
            $sessionData = Auth::guard('admin')->user();
            $data['admin'] = Admin::where('id', '=', $sessionData->id)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            $data['product_cat']      = ProductCategories::where('status', '=', 1)->orderBy('category_name', 'ASC')->get();
            $data['unit']                    = Unit::where('status', '=', 1)->orderBy('name', 'ASC')->get();
            $data['size']                    = Size::where('status', '=', 1)->orderBy('name', 'ASC')->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'           => 'required',                                       
                ];                
                if($this->validate($request, $rules)){
                    $checkValue = Product::where('name', '=', $postData['name'])->count();
                    if($checkValue <= 0){
                        $sessionData = Auth::guard('admin')->user();
                        /* page image */
                        $imageFile      = $request->file('product_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('product_image', $imageName, 'product', 'image');
                            if($uploadedFile['status']){
                                $product_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $product_image = '';
                        }
                        /* page image */
                        $fields = [
                            'name'                  => $postData['name'],
                            'product_slug'          => Helper::clean($postData['name']),
                            'product_image'         => $product_image,
                            'short_desc'            => $postData['short_desc'],
                            'category_id'           => $postData['product_category'],
                            'markup_price'          => $postData['markup_price'],
                            'retail_price'          => $postData['retail_price'],
                            'unit_id'               => $postData['unit_id'],
                            'size_id'               => $postData['size_id'],
                            'created_by'            => $sessionData->id,
                            'company_id'            => $sessionData->company_id,
                        ];
                        Product::insert($fields);
                        return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                    } else {
                        return redirect()->back()->with('error_message', $this->data['title'].' Already Exists !!!');
                    }
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'product.add-edit';
            $data['row']                    = [];
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $data['product_cat']            = ProductCategories::where('status', '=', 1)->orderBy('category_name', 'ASC')->get();
            $data['unit']                    = Unit::where('status', '=', 1)->orderBy('name', 'ASC')->get();
            $data['size']                    = Size::where('status', '=', 1)->orderBy('name', 'ASC')->get();
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'product.add-edit';
            $data['row']                    = Product::where($this->data['primary_key'], '=', $id)->first();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'           => 'required',                    
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Product::where('name', '=', $postData['name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        $sessionData = Auth::guard('admin')->user();
                        /* page image */
                        $imageFile      = $request->file('product_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('product_image', $imageName, 'product', 'image');
                            if($uploadedFile['status']){
                                $product_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $product_image = $data['row']->product_image;
                        }
                        /* page image */
                        $fields = [
                            'name'                  => $postData['name'],
                            'product_slug'          => Helper::clean($postData['name']),
                            'product_image'         => $product_image,
                            'short_desc'            => $postData['short_desc'],
                            'category_id'           => $postData['product_category'],
                            'markup_price'          => $postData['markup_price'],
                            'retail_price'          => $postData['retail_price'],
                            'unit_id'               => $postData['unit_id'],
                            'size_id'               => $postData['size_id'],
                            'company_id'            => $sessionData->company_id,
                            'updated_by'            => $sessionData->id,
                            'updated_at'            => date('Y-m-d H:i:s')
                        ];
                        Product::where($this->data['primary_key'], '=', $id)->update($fields);
                        return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
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
            $model                          = Product::find($id);
            if ($model->status == 1)
            {
                $model->status  = 0;
                $msg            = 'Deactivated';
            } else {
                $model->status  = 1;
                $msg            = 'Activated';
            }            
            $model->save();
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
    public function viewOrderDetails($id)
    {
        // dd($id);
        $id                             = Helper::decoded($id);       
        $data['module']                 = $this->data;
        // $data['slug']                   = $slug;        
        $page_name                      = 'billing_orders.view_order_details';
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
}
