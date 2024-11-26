<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Center;
use App\Models\DocumentType;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\CenterTimeSlot;
use App\Models\FranchiseOwner;

use Auth;
use Session;
use Helper;
use Hash;
class FranchiseOwnerController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Franchise Owner',
            'controller'        => 'FranchiseOwnerController',
            'controller_route'  => 'franchise-owner',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'franchise-owner.list';
            $data['rows']                   = FranchiseOwner::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
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
                ];
                if($this->validate($request, $rules)){
                    $checkValue = FranchiseOwner::where('name', '=', $postData['name'])->count();
                    if($checkValue <= 0){
                        /* pan document */
                            $imageFile      = $request->file('id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('id_proof', $imageName, 'franchise_owner', 'custom');
                                if($uploadedFile['status']){
                                    $id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                // return redirect()->back()->with(['error_message' => 'Please Upload ID Proof !!!']);
                                $id_proof = '';
                            }
                        /* pan document */
                        /* aadhar document */
                            $imageFile      = $request->file('aadhar_doc');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('aadhar_doc', $imageName, 'franchise_owner', 'custom');
                                if($uploadedFile['status']){
                                    $aadhar_doc = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                // return redirect()->back()->with(['error_message' => 'Please Upload ID Proof !!!']);
                                $aadhar_doc = '';
                            }
                        /* aadhar document */
                        /* qualification document */
                            $imageFile      = $request->file('qualification_doc');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('qualification_doc', $imageName, 'franchise_owner', 'custom');
                                if($uploadedFile['status']){
                                    $qualification_doc = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                // return redirect()->back()->with(['error_message' => 'Please Upload ID Proof !!!']);
                                $qualification_doc = '';
                            }
                        /* qualification document */
                        /* Photo */
                            $imageFile      = $request->file('photo');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('photo', $imageName, 'franchise_owner', 'image');
                                if($uploadedFile['status']){
                                    $photo = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $photo = '';
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
                            'aadhar_doc_id'             => $postData['aadhar_doc_id'],
                            'aadhar_doc'                => $aadhar_doc,
                            'qualification_doc_id'      => $postData['qualification_doc_id'],
                            'qualification_doc'         => $qualification_doc,
                            'photo'                     => $photo,
                            'created_by'                => 'ADMIN',
                        ];
                        FranchiseOwner::insert($fields);
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
            $page_name                      = 'franchise-owner.add-edit';
            $data['row']                    = [];
            $data['docTypes']               = DocumentType::select('id', 'name')->where('status', '=', 1)->get();
            $data['countries']              = Country::select('id', 'name')->where('status', '=', 1)->get();
            $data['states']                 = State::select('id', 'name')->where('status', '=', 1)->get();
            $data['districts']              = District::select('id', 'name')->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'franchise-owner.add-edit';
            $data['row']                    = FranchiseOwner::where($this->data['primary_key'], '=', $id)->first();
            $data['docTypes']               = DocumentType::select('id', 'name')->where('status', '=', 1)->get();
            $data['countries']              = Country::select('id', 'name')->where('status', '=', 1)->get();
            $data['states']                 = State::select('id', 'name')->where('status', '=', 1)->get();
            $data['districts']              = District::select('id', 'name')->where('status', '=', 1)->get();
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
                ];
                if($this->validate($request, $rules)){
                    $checkValue = FranchiseOwner::where('name', '=', $postData['name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        /* pan document */
                            $imageFile      = $request->file('id_proof');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('id_proof', $imageName, 'franchise_owner', 'custom');
                                if($uploadedFile['status']){
                                    $id_proof = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $id_proof = $data['row']->id_proof;
                            }
                        /* pan document */
                        /* aadhar document */
                            $imageFile      = $request->file('aadhar_doc');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('aadhar_doc', $imageName, 'franchise_owner', 'custom');
                                if($uploadedFile['status']){
                                    $aadhar_doc = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                // return redirect()->back()->with(['error_message' => 'Please Upload ID Proof !!!']);
                                $aadhar_doc = $data['row']->aadhar_doc;
                            }
                        /* aadhar document */
                        /* qualification document */
                            $imageFile      = $request->file('qualification_doc');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('qualification_doc', $imageName, 'franchise_owner', 'custom');
                                if($uploadedFile['status']){
                                    $qualification_doc = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                // return redirect()->back()->with(['error_message' => 'Please Upload ID Proof !!!']);
                                $qualification_doc = $data['row']->qualification_doc;
                            }
                        /* qualification document */
                        /* Photo */
                            $imageFile      = $request->file('photo');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('photo', $imageName, 'franchise_owner', 'image');
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
                            'aadhar_doc_id'             => $postData['aadhar_doc_id'],
                            'aadhar_doc'                => $aadhar_doc,
                            'qualification_doc_id'      => $postData['qualification_doc_id'],
                            'qualification_doc'         => $qualification_doc,
                            'photo'                     => $photo,
                            'updated_by'                => 'ADMIN',
                        ];
                        FranchiseOwner::where($this->data['primary_key'], '=', $id)->update($fields);
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
            FranchiseOwner::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = FranchiseOwner::find($id);
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
