<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Companies;
use App\Models\Employees;
use App\Models\EmployeeType;
use App\Models\Hotel;
use App\Models\Role;
use Auth;
use Session;
use Helper;
use Hash;

class EmployeeDetailsController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Employee: ',
            'controller'        => 'EmployeeDetailsController',
            'controller_route'  => 'employee-details',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($slug){
            $data['slug']                           = $slug;
            $data['module']                 = $this->data;
            $title                          = ucfirst($data['slug']).' List';
            $page_name                      = 'employee-details.list';
            $sessionType                    = Session::get('type');
            $data['employee_department']    = EmployeeType::where('status', '!=', 3)->where('slug', '=', $data['slug'])->orderBy('id', 'ASC')->first();
            $data['rows']                   = Employees::where('status', '!=', 3)->where('employee_type_id', '=', $data['employee_department']->id)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request, $slug){
            $data['module']           = $this->data;    
            $data['slug']             = $slug;
            $data['employee_department']    = EmployeeType::where('status', '!=', 3)->where('slug', '=', $data['slug'])->orderBy('id', 'ASC')->first();
            //  Helper::pr($data['employee_department']);
            if($data['employee_department']->level == 2)
            {
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('employee_type_id', '=', 1)->orderBy('id', 'ASC')->get();
            }
            else if($data['employee_department']->level == 3)
            {
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('employee_type_id', '=', 2)->orderBy('id', 'ASC')->get();
            }
            else{
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('employee_type_id', '=', 3)->orderBy('id', 'ASC')->get();
            }                        
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'employee_type'         => 'required',
                    'email'                 => 'required',
                    'whatsapp_no'           => 'required',
                    'dob'                  => 'required',
                    'doj'                  => 'required',
                    'phone'                => 'required',                    
                    'password'              => 'required',                    
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Employees::where('name', '=', $postData['name'])->count();
                    if($checkValue <= 0){
                        $sessionData = Auth::guard('admin')->user();
                        /* profile image */
                        $imageFile      = $request->file('image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('image', $imageName, '', 'image');
                            if($uploadedFile['status']){
                                $image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $image = '';
                        }
                        /* profile image */
                        /* generate employee no */  
                        $prefix         = (($data['employee_department'])?$data['employee_department']->prefix:'');                        
                        $getLastEnquiry = Employees::orderBy('id', 'DESC')->first();
                        if($getLastEnquiry){
                            $sl_no              = $getLastEnquiry->sl_no;
                            $next_sl_no         = $sl_no + 1;
                            $next_sl_no_string  = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                            $employee_no         = $prefix.$next_sl_no_string;
                        } else {
                            $next_sl_no         = 1;
                            $next_sl_no_string  = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                            $employee_no         = $prefix.$next_sl_no_string;
                        }
                    /* generate employee no */
                        $fields = [
                            'name'              => $postData['name'],
                            'phone'            => $postData['phone'],
                            'email'             => $postData['email'],
                            'employee_type_id'      => $data['employee_department']->id,
                            'parent_id'             => $postData['parent_id'],
                            'alt_email'             => $postData['alt_email'],
                            'whatsapp_no'           => $postData['whatsapp_no'],
                            'short_bio'             => $postData['short_bio'],
                            'dob'             => $postData['dob'],
                            'doj'             => $postData['doj'],
                            'qualification'             => $postData['qualification'],
                            'address'             => $postData['address'],
                            'password'          => Hash::make($postData['password']),
                            'profile_image'         => $image,
                            'employee_no'           => $employee_no,
                            'created_by'            => $sessionData->id,
                        ];
                        //  Helper::pr($fields);
                        Employees::insert($fields);
                        return redirect("admin/" . $this->data['controller_route'] ."/".$data['slug']. "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                    } else {
                        return redirect()->back()->with('error_message', $this->data['title'].' Already Exists !!!');
                    }
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;                          
            $title                          = $this->data['title'].' '.$data['slug'].' Add';
            $page_name                      = 'employee-details.add-edit';
            $data['row']                    = [];                         
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $slug, $id, ){
            // \DB::enableQueryLog();
            $data['module']                 = $this->data;
            $data['slug']                   = $slug;            
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'employee-details.add-edit';
            $data['row']                    = Employees::where($this->data['primary_key'], '=', $id)->first();
            $data['employee_department']    = EmployeeType::where('status', '!=', 3)->where('slug', '=', $data['slug'])->orderBy('id', 'ASC')->first();
            // Display the SQL query
            // dd(\DB::getQueryLog());
            //   Helper::pr($data['employee_department']);
            if($data['employee_department']->level == 2)
            {
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('employee_type_id', '=', 1)->orderBy('id', 'ASC')->get();
            }
            else if($data['employee_department']->level == 3)
            {
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('employee_type_id', '=', 2)->orderBy('id', 'ASC')->get();
            }
            else{
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('employee_type_id', '=', 3)->orderBy('id', 'ASC')->get();
            }                     

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'employee_type'         => 'required',
                    'email'                 => 'required',
                    'whatsapp_no'           => 'required',
                    'dob'                  => 'required',
                    'doj'                  => 'required',
                    'phone'                => 'required',                                                            
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Employees::where('name', '=', $postData['name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        $sessionData = Auth::guard('admin')->user();
                        /* profile image */
                        $imageFile      = $request->file('image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('image', $imageName, '', 'image');
                            if($uploadedFile['status']){
                                $image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $image = $data['row']->image;
                        }
                        /* profile image */
                    //     /* generate employee no */  
                    //     // $currentMonth   = date('m');
                    //     // $currentYear    = date('y');                          
                    //     $getLastEnquiry = Employees::orderBy('id', 'DESC')->first();
                    //     Helper::pr($getLastEnquiry);
                    //     if($getLastEnquiry){
                    //         $sl_no              = $getLastEnquiry->sl_no;
                    //         $next_sl_no         = $sl_no + 1;
                    //         $next_sl_no_string  = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                    //         $employee_no         = 'ENERGIC/EMP/'.$next_sl_no_string;
                    //     } else {
                    //         $next_sl_no         = 1;
                    //         $next_sl_no_string  = str_pad($next_sl_no, 5, 0, STR_PAD_LEFT);
                    //         $employee_no         = 'ENERGIC/EMP/'.$next_sl_no_string;
                    //     }
                    // /* generate employee no */
                        if($postData['password'] != ''){
                            $fields = [
                                'name'              => $postData['name'],
                                'phone'            => $postData['phone'],
                                'email'             => $postData['email'],
                                'employee_type_id'             => $data['employee_department']->id,
                                'parent_id'             => $postData['parent_id'],
                                'alt_email'             => $postData['alt_email'],
                                'whatsapp_no'             => $postData['whatsapp_no'],
                                'short_bio'             => $postData['short_bio'],
                                'dob'             => $postData['dob'],
                                'doj'             => $postData['doj'],
                                'qualification'             => $postData['qualification'],
                                'password'          => Hash::make($postData['password']),
                                'profile_image'         => $image,
                                'address'               => $postData['address'],
                                // 'employee_no'           => $employee_no,
                                'created_by'            => $sessionData->id,
                            ];
                        } else {
                            $fields = [
                                'name'              => $postData['name'],
                                'phone'            => $postData['phone'],
                                'email'             => $postData['email'],
                                'employee_type_id'             => $data['employee_department']->id,
                                'parent_id'             => $postData['parent_id'],
                                'alt_email'             => $postData['alt_email'],
                                'whatsapp_no'             => $postData['whatsapp_no'],
                                'short_bio'             => $postData['short_bio'],
                                'dob'             => $postData['dob'],
                                'doj'             => $postData['doj'],
                                'qualification'             => $postData['qualification'],
                                'profile_image'         => $image,
                                // 'employee_no'           => $employee_no,
                                'updated_by'            => $sessionData->id,
                                'updated_at'            => date('Y-m-d H:i:s')
                            ];
                        }
                        //   Helper::pr($fields);
                        Employees::where($this->data['primary_key'], '=', $id)->update($fields);                        
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
        public function delete(Request $request , $slug, $id){
            $id                             = Helper::decoded($id);
            $data['slug']             = $slug;
            $fields = [
                'status'             => 3
            ];
            Employees::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] ."/".$data['slug']. "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $slug, $id){
            $id                             = Helper::decoded($id);
            $data['slug']             = $slug;
            $model                          = Employees::find($id);
            if ($model->status == 1)
            {
                $model->status  = 0;
                $msg            = 'Deactivated';
            } else {
                $model->status  = 1;
                $msg            = 'Activated';
            }            
            $model->save();
            return redirect("admin/" . $this->data['controller_route'] ."/".$data['slug']. "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
    // view details
    public function viewDetails($slug, $id)
    {
        // dd($id);
        $id                             = Helper::decoded($id);       
        $data['module']                 = $this->data;
        $data['slug']                   = $slug;        
        $page_name                      = 'employee-details.view_details';
        $data['row']                    = Employees::where('status', '!=', 3)->where('id', '=', $id)->orderBy('id', 'DESC')->first();        
        $data['employee_department']    = EmployeeType::where('status', '=', 1)->where('id', '=', $data['row']->employee_type_id)->orderBy('name', 'ASC')->first();                
        $title                          = $this->data['title'] . ' View Details : ' . (($data['row'])?$data['row']->name:'');
        echo $this->admin_after_login_layout($title, $page_name, $data);
    }
    // view details
}
