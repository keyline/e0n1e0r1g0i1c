<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\GalleryCategory;
use App\Models\Gallery;
use Auth;
use Session;
use Helper;
use Hash;

class GalleryController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Gallery',
            'controller'        => 'GalleryController',
            'controller_route'  => 'gallery',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'gallery.list';
            $data['rows']                   = Gallery::where('status', '!=', 3)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'category_id'               => 'required',
                    'title'                     => 'required',
                    'gallery_image'             => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* gallery image */
                        $imageFile      = $request->file('gallery_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('gallery_image', $imageName, 'gallery', 'image');
                            if($uploadedFile['status']){
                                $gallery_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            return redirect()->back()->with(['error_message' => 'Please Upload Gallery Image !!!']);
                        }
                    /* gallery image */
                    $fields = [
                        'category_id'       => $postData['category_id'],
                        'type'              => 'IMAGE',
                        'title'             => $postData['title'],
                        'gallery_image'     => $gallery_image,
                    ];
                    Gallery::insert($fields);
                    return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Inserted Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' Add';
            $page_name                      = 'gallery.add-edit';
            $data['row']                    = [];
            $data['cats']                   = GalleryCategory::select('id', 'name')->where('status', '=', 1)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'gallery.add-edit';
            $data['row']                    = Gallery::where($this->data['primary_key'], '=', $id)->first();
            $data['cats']                   = GalleryCategory::select('id', 'name')->where('status', '=', 1)->get();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'category_id'               => 'required',
                    'title'                     => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* gallery image */
                        $imageFile      = $request->file('gallery_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('gallery_image', $imageName, 'gallery', 'image');
                            if($uploadedFile['status']){
                                $gallery_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $gallery_image = $data['row']->gallery_image;
                        }
                    /* gallery image */
                    $fields = [
                        'category_id'       => $postData['category_id'],
                        'type'              => 'IMAGE',
                        'title'             => $postData['title'],
                        'gallery_image'     => $gallery_image,
                    ];
                    Gallery::where($this->data['primary_key'], '=', $id)->update($fields);
                    return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
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
            Gallery::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Gallery::find($id);
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
