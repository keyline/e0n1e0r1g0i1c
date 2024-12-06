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
use App\Models\ClientOrder;
use App\Models\ClientType;
use App\Models\Hotel;
use App\Models\Role;
use Auth;
use Session;
use Helper;
use Hash;

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
                                    
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'email'                 => 'required',
                    'phone'                 => 'required',
                    'whatsapp_no'           => 'required',
                    'address'               => 'required'
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
            
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'name'                  => 'required',
                    'email'                 => 'required',
                    'phone'                 => 'required',
                    'whatsapp_no'           => 'required',
                    'address'               => 'required'
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
        $data['order']                  = ClientOrder::where('status', '!=', 3)->where('client_id', '=', $id)->orderBy('id', 'DESC')->first(); 
        // Display the SQL query
            // dd(\DB::getQueryLog());
            //   Helper::pr($data['order']);
        $title                          = $this->data['title'] . ' View Details : ' . (($data['row'])?$data['row']->name:'');
        echo $this->admin_after_login_layout($title, $page_name, $data);
    }
    // view details
}
