<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Center;
use App\Models\Teacher;
use App\Models\DocumentType;
use App\Models\Country;
use App\Models\State;
use App\Models\District;

use Auth;
use Session;
use Helper;
use Hash;
class TeacherController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Teacher',
            'controller'        => 'TeacherController',
            'controller_route'  => 'teacher',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'teacher.list';
            $data['rows']                   = Teacher::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                      => 'required',
                    'address'                   => 'required',
                    'country_id'                => 'required',
                    'state_id'                  => 'required',
                    'district_id'               => 'required',
                    'locality'                  => 'required',
                    'pincode'                   => 'required',
                    'landmark'                  => 'required',
                    'email'                     => 'required',
                    'password'                  => 'required',
                    'phone'                     => 'required',
                    'whatsapp_no'               => 'required',
                    'doc_type_id'               => 'required',
                    'id_proof'                  => 'required',
                    'member_since'              => 'required',
                    'assigned_center_id'        => 'required',
                    'qualification'             => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Teacher::where('name', '=', $postData['name'])->count();
                    if($checkValue <= 0){
                        /* id proof */
                            $imageFile      = $request->file('id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('id_proof', $imageName, 'teacher', 'custom');
                                if($uploadedFile['status']){
                                    $id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                return redirect()->back()->with(['error_message' => 'Please Upload ID Proof !!!']);
                            }
                        /* id proof */
                        /* Photo */
                            $imageFile      = $request->file('photo');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('photo', $imageName, 'teacher', 'image');
                                if($uploadedFile['status']){
                                    $photo = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $photo = '';
                            }
                        /* Photo */
                        /* center no generate */
                            $getLastEnquiry = Teacher::orderBy('id', 'DESC')->first();
                            if($getLastEnquiry){
                                $sl_no              = $getLastEnquiry->sl_no;
                                $next_sl_no         = $sl_no + 1;
                                $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                                $teacher_no         = 'KZE-T-'.$next_sl_no_string;
                            } else {
                                $next_sl_no         = 1;
                                $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                                $teacher_no         = 'KZE-T-'.$next_sl_no_string;
                            }
                        /* center no generate */
                        $fields = [
                            'sl_no'                     => $next_sl_no,
                            'teacher_no'                => $teacher_no,
                            'name'                      => $postData['name'],
                            'address'                   => $postData['address'],
                            'country_id'                => $postData['country_id'],
                            'state_id'                  => $postData['state_id'],
                            'district_id'               => $postData['district_id'],
                            'locality'                  => $postData['locality'],
                            'pincode'                   => $postData['pincode'],
                            'landmark'                  => $postData['landmark'],
                            'email'                     => $postData['email'],
                            'password'                  => Hash::make($postData['password']),
                            'phone'                     => $postData['phone'],
                            'alt_phone'                 => $postData['alt_phone'],
                            'whatsapp_no'               => $postData['whatsapp_no'],
                            'doc_type_id'               => $postData['doc_type_id'],
                            'id_proof'                  => $id_proof,
                            'created_by'                => 'ADMIN',
                            'member_since'              => $postData['member_since'],
                            'qualification'             => $postData['qualification'],
                            'assigned_center_id'        => json_encode($postData['assigned_center_id']),
                            'photo'                     => $photo,
                        ];
                        Teacher::insert($fields);
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
            $page_name                      = 'teacher.add-edit';
            $data['row']                    = [];
            $data['docTypes']               = DocumentType::select('id', 'name')->where('status', '=', 1)->get();
            $data['countries']              = Country::select('id', 'name')->where('status', '=', 1)->get();
            $data['states']                 = State::select('id', 'name')->where('status', '=', 1)->get();
            $data['districts']              = District::select('id', 'name')->where('status', '=', 1)->get();
            $data['centers']                = Center::select('id', 'name')->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'teacher.add-edit';
            $data['row']                    = Teacher::where($this->data['primary_key'], '=', $id)->first();
            $data['docTypes']               = DocumentType::select('id', 'name')->where('status', '=', 1)->get();
            $data['countries']              = Country::select('id', 'name')->where('status', '=', 1)->get();
            $data['states']                 = State::select('id', 'name')->where('status', '=', 1)->get();
            $data['districts']              = District::select('id', 'name')->where('status', '=', 1)->get();
            $data['centers']                = Center::select('id', 'name')->where('status', '=', 1)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                      => 'required',
                    'address'                   => 'required',
                    'country_id'                => 'required',
                    'state_id'                  => 'required',
                    'district_id'               => 'required',
                    'locality'                  => 'required',
                    'pincode'                   => 'required',
                    'landmark'                  => 'required',
                    'email'                     => 'required',
                    'phone'                     => 'required',
                    'whatsapp_no'               => 'required',
                    'doc_type_id'               => 'required',
                    'member_since'              => 'required',
                    'assigned_center_id'        => 'required',
                    'qualification'             => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Teacher::where('name', '=', $postData['name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        /* id proof */
                            $imageFile      = $request->file('id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('id_proof', $imageName, 'teacher', 'custom');
                                if($uploadedFile['status']){
                                    $id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $id_proof = $data['row']->id_proof;
                            }
                        /* id proof */
                        /* Photo */
                            $imageFile      = $request->file('photo');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('photo', $imageName, 'teacher', 'image');
                                if($uploadedFile['status']){
                                    $photo = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $photo = $data['row']->photo;
                            }
                        /* Photo */
                        $fields = [
                            'name'                      => $postData['name'],
                            'address'                   => $postData['address'],
                            'country_id'                => $postData['country_id'],
                            'state_id'                  => $postData['state_id'],
                            'district_id'               => $postData['district_id'],
                            'locality'                  => $postData['locality'],
                            'pincode'                   => $postData['pincode'],
                            'landmark'                  => $postData['landmark'],
                            'email'                     => $postData['email'],
                            'phone'                     => $postData['phone'],
                            'alt_phone'                 => $postData['alt_phone'],
                            'whatsapp_no'               => $postData['whatsapp_no'],
                            'doc_type_id'               => $postData['doc_type_id'],
                            'id_proof'                  => $id_proof,
                            'updated_by'                => 'ADMIN',
                            'member_since'              => $postData['member_since'],
                            'qualification'             => $postData['qualification'],
                            'assigned_center_id'        => json_encode($postData['assigned_center_id']),
                            'photo'                     => $photo,
                        ];
                        Teacher::where($this->data['primary_key'], '=', $id)->update($fields);
                        if($postData['password'] != ''){
                            $fields2 = [
                                'password'                  => Hash::make($postData['password'])
                            ];
                            Teacher::where($this->data['primary_key'], '=', $id)->update($fields2);
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
            Teacher::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Teacher::find($id);
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
