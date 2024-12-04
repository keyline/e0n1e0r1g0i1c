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
            'title'             => 'Employee Details of ',
            'controller'        => 'EmployeeDetailsController',
            'controller_route'  => 'employee-details',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list($slug){
            $data['slug']                           = $slug;
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].''.$data['slug'].' List';
            $page_name                      = 'employee-details.list';
            $sessionType                    = Session::get('type');
            $data['rows']                   = Employees::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request, $slug){
            $data['module']           = $this->data;    
            $data['slug']             = $slug;
            $data['employee_department']    = EmployeeType::where('status', '!=', 3)->where('slug', '=', $data['slug'])->orderBy('id', 'ASC')->first();
            // Helper::pr($data['employee_type']);
            if($data['employee_department']->level == 2)
            {
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('parent_id', '=', 0)->orderBy('id', 'ASC')->get();
            }
            else if($data['employee_department']->level == 3)
            {
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('parent_id', '=', 1)->orderBy('id', 'ASC')->get();
            }
            else{
                $data['parent_id']        = Employees::where('status', '!=', 3)->where('parent_id', '=', 2)->orderBy('id', 'ASC')->get();
            }
            
            // Helper::pr($data['employee_type']);
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'mobile'                => 'required',
                    'email'                 => 'required',
                    'password'              => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Employees::where('name', '=', $postData['name'])->count();
                    if($checkValue <= 0){
                        $sessionData = Auth::guard('admin')->user();
                        $fields = [
                            'name'              => $postData['name'],
                            'mobile'            => $postData['mobile'],
                            'email'             => $postData['email'],
                            'role_id'             => $postData['role'],
                            'password'          => Hash::make($postData['password']),
                            'created_by'            => $sessionData->id,
                        ];
                        Employees::insert($fields);
                        return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
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
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $data['role']                   = Role::where('status', '!=', 3)->orderBy('id', 'ASC')->get();   
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'employee-details.add-edit';
            $data['row']                    = Employees::where($this->data['primary_key'], '=', $id)->first();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'mobile'                => 'required',
                    'email'                 => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Employees::where('name', '=', $postData['name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        $sessionData = Auth::guard('admin')->user();
                        if($postData['password'] != ''){
                            $fields = [
                                'name'                  => $postData['name'],
                                'mobile'                => $postData['mobile'],
                                'email'                 => $postData['email'],
                                'role_id'             => $postData['role'],
                                'password'              => Hash::make($postData['password']),
                                'updated_by'            => $sessionData->id,
                                'updated_at'            => date('Y-m-d H:i:s')
                            ];
                        } else {
                            $fields = [
                                'name'                  => $postData['name'],
                                'mobile'                => $postData['mobile'],
                                'email'                 => $postData['email'],
                                'role_id'             => $postData['role'],
                                'updated_by'            => $sessionData->id,
                                'updated_at'            => date('Y-m-d H:i:s')
                            ];
                        }
                        Employees::where($this->data['primary_key'], '=', $id)->update($fields);
                        $admin = Employees::where('id', '=', $id)->first();
                        $company_id = $admin ? $admin->company_id : null;
                        if($company_id!= 0){
                        $company = Companies::where('id', '=', $company_id)->first(); 
                        // dd($company);
                        $fields2 = [
                            'name'                  => $postData['name'],
                            'phone'                => $postData['mobile'],  
                            'updated_by'            => $sessionData->id,                          
                            'updated_at'            => date('Y-m-d H:i:s')
                        ];                        
                        Companies::where('id', '=', $company->id)->update($fields2);
                    }
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
            Employees::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
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
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' '.$msg.' Successfully !!!');
        }
    /* change status */
}