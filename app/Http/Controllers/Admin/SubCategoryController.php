<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\Category;
use Auth;
use Session;
use Helper;
use Hash;
class SubCategoryController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Sub Category',
            'controller'        => 'SubCategoryController',
            'controller_route'  => 'sub-category',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'sub-category.list';
            $data['rows']                   = Category::where('status', '!=', 3)->where('parent_id', '!=', 0)->orderBy('id', 'DESC')->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
    /* add */
        public function add(Request $request){
            $data['module']           = $this->data;
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'parent_id'                 => 'required',
                    'category_name'             => 'required',
                    'cover_image'               => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Category::where('category_name', '=', $postData['category_name'])->count();
                    if($checkValue <= 0){
                        /* cover image */
                            $imageFile      = $request->file('cover_image');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('cover_image', $imageName, 'category', 'image');
                                if($uploadedFile['status']){
                                    $cover_image = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                return redirect()->back()->with(['error_message' => 'Please Upload Cover Image !!!']);
                            }
                        /* cover image */
                        /* banner image */
                            $imageFile      = $request->file('banner_image');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('banner_image', $imageName, 'category', 'image');
                                if($uploadedFile['status']){
                                    $banner_image = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                return redirect()->back()->with(['error_message' => 'Please Upload Cover Image !!!']);
                            }
                        /* banner image */
                        $fields = [
                            'parent_id'             => $postData['parent_id'],
                            'category_name'         => $postData['category_name'],
                            'slug'                  => Helper::clean($postData['category_name']),
                            'cover_image'           => $cover_image,
                            'banner_image'          => $banner_image,
                            'short_description'     => $postData['short_description'],
                            'description'           => $postData['description'],
                            'meta_title'            => $postData['meta_title'],
                            'meta_description'      => $postData['meta_description'],
                            'meta_keywords'         => $postData['meta_keywords'],
                        ];
                        Category::insert($fields);
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
            $page_name                      = 'sub-category.add-edit';
            $data['row']                    = [];
            $data['cats']                   = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* add */
    /* edit */
        public function edit(Request $request, $id){
            $data['module']                 = $this->data;
            $id                             = Helper::decoded($id);
            $title                          = $this->data['title'].' Update';
            $page_name                      = 'sub-category.add-edit';
            $data['row']                    = Category::where($this->data['primary_key'], '=', $id)->first();
            $data['cats']                   = Category::select('id', 'category_name')->where('status', '=', 1)->where('parent_id', '=', 0)->get();
            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'parent_id'                 => 'required',
                    'category_name'             => 'required',
                ];
                if($this->validate($request, $rules)){
                    $checkValue = Category::where('category_name', '=', $postData['category_name'])->where('id', '!=', $id)->count();
                    if($checkValue <= 0){
                        /* cover image */
                            $imageFile      = $request->file('cover_image');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('cover_image', $imageName, 'category', 'image');
                                if($uploadedFile['status']){
                                    $cover_image = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $cover_image = $data['row']->cover_image;
                            }
                        /* cover image */
                        /* banner image */
                            $imageFile      = $request->file('banner_image');
                            if($imageFile != ''){
                                $imageName      = $imageFile->getClientOriginalName();
                                $uploadedFile   = $this->upload_single_file('banner_image', $imageName, 'category', 'image');
                                if($uploadedFile['status']){
                                    $banner_image = $uploadedFile['newFilename'];
                                } else {
                                    return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                                }
                            } else {
                                $banner_image = $data['row']->banner_image;
                            }
                        /* banner image */
                        $fields = [
                            'parent_id'             => $postData['parent_id'],
                            'category_name'         => $postData['category_name'],
                            'slug'                  => Helper::clean($postData['category_name']),
                            'cover_image'           => $cover_image,
                            'banner_image'          => $banner_image,
                            'short_description'     => $postData['short_description'],
                            'description'           => $postData['description'],
                            'meta_title'            => $postData['meta_title'],
                            'meta_description'      => $postData['meta_description'],
                            'meta_keywords'         => $postData['meta_keywords'],
                        ];
                        Category::where($this->data['primary_key'], '=', $id)->update($fields);
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
            Category::where($this->data['primary_key'], '=', $id)->update($fields);
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Deleted Successfully !!!');
        }
    /* delete */
    /* change status */
        public function change_status(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Category::find($id);
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
    /* change feature */
        public function change_feature(Request $request, $id){
            $id                             = Helper::decoded($id);
            $model                          = Category::find($id);
            if ($model->is_feature == 1)
            {
                $model->is_feature  = 0;
                $msg            = 'Non-Featured';
            } else {
                $model->is_feature  = 1;
                $msg            = 'Featured';
            }            
            $model->save();
            return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Marked As '.$msg.' Successfully !!!');
        }
    /* change feature */
}
