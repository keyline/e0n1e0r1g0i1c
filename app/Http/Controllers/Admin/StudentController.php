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
use App\Models\Student;
use App\Models\DocumentType;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\Source;
use App\Models\Label;
use App\Models\StudentLabelMark;

use Auth;
use Session;
use Helper;
use Hash;
use DB;
class StudentController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Student',
            'controller'        => 'StudentController',
            'controller_route'  => 'student',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'student.list';
            $data['rows']                   = Student::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            $data['rows']                   = DB::table('students')
                                                ->join('sources', 'students.source_id', '=', 'sources.id')
                                                ->join('centers', 'students.center_id', '=', 'centers.id')
                                                ->select('students.*', 'sources.name as source_name', 'centers.name as center_name')
                                                ->where('students.status', '!=', 3)
                                                ->orderBy('id', 'DESC')
                                                ->get();
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
                    'phone'                     => 'required',
                    'whatsapp_no'               => 'required',
                    'student_doc_type_id'       => 'required',
                    'student_id_proof'          => 'required',
                    'center_id'                 => 'required',
                    'dob'                       => 'required',
                    'guardian_name'             => 'required',
                    'guardian_relation'         => 'required',
                    'source_id'                 => 'required',
                    'guardian_doc_type_id'      => 'required',
                    'guardian_id_proof'         => 'required',
                    'current_label_id'          => 'required',
                    // 'current_label_marks'       => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Student::where('name', '=', $postData['name'])->count();
                    if($checkValue <= 0){
                        /* student id proof */
                            $imageFile      = $request->file('student_id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('student_id_proof', $imageName, 'student', 'custom');
                                if($uploadedFile['status']){
                                    $student_id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                return redirect()->back()->with(['error_message' => 'Please Upload Student ID Proof !!!']);
                            }
                        /* student id proof */
                        /* guardian id proof */
                            $imageFile      = $request->file('guardian_id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('guardian_id_proof', $imageName, 'student', 'custom');
                                if($uploadedFile['status']){
                                    $guardian_id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                return redirect()->back()->with(['error_message' => 'Please Upload Guardian ID Proof !!!']);
                            }
                        /* guardian id proof */
                        /* student Photo */
                            $imageFile      = $request->file('student_photo');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('student_photo', $imageName, 'student', 'image');
                                if($uploadedFile['status']){
                                    $student_photo = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $student_photo = '';
                            }
                        /* student Photo */
                        /* center no generate */
                            $getLastEnquiry = Student::orderBy('id', 'DESC')->first();
                            if($getLastEnquiry){
                                $sl_no              = $getLastEnquiry->sl_no;
                                $next_sl_no         = $sl_no + 1;
                                $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                                $student_no         = 'KZE-S-'.$next_sl_no_string;
                            } else {
                                $next_sl_no         = 1;
                                $next_sl_no_string  = str_pad($next_sl_no, 7, 0, STR_PAD_LEFT);
                                $student_no         = 'KZE-S-'.$next_sl_no_string;
                            }
                        /* center no generate */
                        $fields = [
                            'sl_no'                     => $next_sl_no,
                            'student_no'                => $student_no,
                            'center_id'                 => $postData['center_id'],
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
                            'created_by'                => 'ADMIN',
                            'dob'                       => $postData['dob'],
                            'guardian_name'             => $postData['guardian_name'],
                            'guardian_relation'         => $postData['guardian_relation'],
                            'source_id'                 => $postData['source_id'],
                            'student_doc_type_id'       => $postData['student_doc_type_id'],
                            'student_id_proof'          => $student_id_proof,
                            'guardian_doc_type_id'      => $postData['guardian_doc_type_id'],
                            'guardian_id_proof'         => $guardian_id_proof,
                            'student_photo'             => $student_photo,
                            'current_label_id'          => $postData['current_label_id'],
                            'current_label_marks'       => $postData['current_label_marks'],
                        ];
                        $student_id = Student::insertGetId($fields);
                        /* student label wise marks */
                            $checkStudentMarks = StudentLabelMark::where('student_id', '=', $student_id)->where('label_id', '=', $postData['current_label_id'])->first();
                            if($checkStudentMarks){
                                // edit
                                $fields2 = [
                                    'label_marks' => $postData['current_label_marks']
                                ];
                                StudentLabelMark::where('id', '=', $checkStudentMarks->id)->update($fields2);
                            } else {
                                // add
                                $fields2 = [
                                    'student_id'    => $student_id,
                                    'label_id'      => $postData['current_label_id'],
                                    'label_marks'   => $postData['current_label_marks']
                                ];
                                StudentLabelMark::insert($fields2);
                            }
                        /* student label wise marks */
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
            $page_name                      = 'student.add-edit';
            $data['row']                    = [];
            $data['docTypes']               = DocumentType::select('id', 'name')->where('status', '=', 1)->get();
            $data['countries']              = Country::select('id', 'name')->where('status', '=', 1)->get();
            $data['states']                 = State::select('id', 'name')->where('status', '=', 1)->get();
            $data['districts']              = District::select('id', 'name')->where('status', '=', 1)->get();
            $data['sources']                = Source::select('id', 'name')->where('status', '=', 1)->get();
            $data['centers']                = Center::select('id', 'name')->where('status', '=', 1)->get();
            $data['labels']                 = Label::select('id', 'name', 'label_no')->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'student.add-edit';
            $data['row']                    = Student::where($this->data['primary_key'], '=', $id)->first();
            $data['docTypes']               = DocumentType::select('id', 'name')->where('status', '=', 1)->get();
            $data['countries']              = Country::select('id', 'name')->where('status', '=', 1)->get();
            $data['states']                 = State::select('id', 'name')->where('status', '=', 1)->get();
            $data['districts']              = District::select('id', 'name')->where('status', '=', 1)->get();
            $data['sources']                = Source::select('id', 'name')->where('status', '=', 1)->get();
            $data['centers']                = Center::select('id', 'name')->where('status', '=', 1)->get();
            $data['labels']                 = Label::select('id', 'name', 'label_no')->where('status', '=', 1)->get();
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
                    'student_doc_type_id'       => 'required',
                    'center_id'                 => 'required',
                    'dob'                       => 'required',
                    'guardian_name'             => 'required',
                    'guardian_relation'         => 'required',
                    'source_id'                 => 'required',
                    'guardian_doc_type_id'      => 'required',
                    'current_label_id'          => 'required',
                    // 'current_label_marks'       => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Student::where('name', '=', $postData['name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        /* student id proof */
                            $imageFile      = $request->file('student_id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('student_id_proof', $imageName, 'student', 'custom');
                                if($uploadedFile['status']){
                                    $student_id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $student_id_proof = $data['row']->student_id_proof;
                            }
                        /* student id proof */
                        /* guardian id proof */
                            $imageFile      = $request->file('guardian_id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('guardian_id_proof', $imageName, 'student', 'custom');
                                if($uploadedFile['status']){
                                    $guardian_id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $guardian_id_proof = $data['row']->guardian_id_proof;
                            }
                        /* guardian id proof */
                        /* Photo */
                            $imageFile      = $request->file('student_photo');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('student_photo', $imageName, 'student', 'image');
                                if($uploadedFile['status']){
                                    $student_photo = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $student_photo = $data['row']->student_photo;
                            }
                        /* Photo */
                        $fields = [
                            'center_id'                 => $postData['center_id'],
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
                            'updated_by'                => 'ADMIN',
                            'dob'                       => $postData['dob'],
                            'guardian_name'             => $postData['guardian_name'],
                            'guardian_relation'         => $postData['guardian_relation'],
                            'source_id'                 => $postData['source_id'],
                            'student_doc_type_id'       => $postData['student_doc_type_id'],
                            'student_id_proof'          => $student_id_proof,
                            'guardian_doc_type_id'      => $postData['guardian_doc_type_id'],
                            'guardian_id_proof'         => $guardian_id_proof,
                            'student_photo'             => $student_photo,
                            'current_label_id'          => $postData['current_label_id'],
                            'current_label_marks'       => $postData['current_label_marks'],
                        ];
                        Student::where($this->data['primary_key'], '=', $id)->update($fields);
                        /* student label wise marks */
                            $student_id = $id;
                            $checkStudentMarks = StudentLabelMark::where('student_id', '=', $student_id)->where('label_id', '=', $postData['current_label_id'])->first();
                            if($checkStudentMarks){
                                // edit
                                $fields2 = [
                                    'label_marks' => $postData['current_label_marks']
                                ];
                                StudentLabelMark::where('id', '=', $checkStudentMarks->id)->update($fields2);
                            } else {
                                // add
                                $fields2 = [
                                    'student_id'    => $student_id,
                                    'label_id'      => $postData['current_label_id'],
                                    'label_marks'   => $postData['current_label_marks']
                                ];
                                StudentLabelMark::insert($fields2);
                            }
                        /* student label wise marks */
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
            Student::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Student::find($id);
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
    /* label wise marks */
        public function label_marks(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $page_name                      = 'student.label-marks';
            $data['student']                = Student::where($this->data['primary_key'], '=', $id)->first();
            $title                          = 'Level Marks : ' . (($data['student'])?$data['student']->name:'');
            $data['rows']                   = StudentLabelMark::where('status', '=', 1)->where('student_id', '=', $id)->orderBy('label_id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* label wise marks */
    /* view details */
        public function viewDetails(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $page_name                      = 'student.view-details';
            $data['student']                = Student::where($this->data['primary_key'], '=', $id)->first();
            $title                          = 'View Details : ' . (($data['student'])?$data['student']->name:'');
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* view details */
}
