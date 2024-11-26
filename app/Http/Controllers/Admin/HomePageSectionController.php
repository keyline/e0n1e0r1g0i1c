<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\GeneralSetting;
use App\Models\HomePage;
use Auth;
use Session;
use Helper;
use Hash;

class HomePageSectionController extends Controller
{
    public function __construct()
    {        
        $this->data = array(
            'title'             => 'Home Page Section',
            'controller'        => 'HomePageSectionController',
            'controller_route'  => 'home-page',
            'primary_key'       => 'id',
        );
    }
    /* list */
        public function list(Request $request){
            $data['module']                 = $this->data;
            $title                          = $this->data['title'].' List';
            $page_name                      = 'home-page.list';
            $data['row']                   = HomePage::where('id', '=', 1)->first();

            if($request->isMethod('post')){
                $postData = $request->all();
                $rules = [
                    'sec2_title'                      => 'required',
                    'sec3_title'                      => 'required',
                    'sec4_title'                      => 'required',
                    'sec5_title'                      => 'required',
                    'sec6_title'                      => 'required',
                ];
                if($this->validate($request, $rules)){
                    /* home page video */
                        $imageFile      = $request->file('sec5_video_cover_image');
                        if($imageFile != ''){
                            $imageName      = $imageFile->getClientOriginalName();
                            $uploadedFile   = $this->upload_single_file('sec5_video_cover_image', $imageName, 'home_page', 'image');
                            if($uploadedFile['status']){
                                $sec5_video_cover_image = $uploadedFile['newFilename'];
                            } else {
                                return redirect()->back()->with(['error_message' => $uploadedFile['message']]);
                            }
                        } else {
                            $sec5_video_cover_image = $data['row']->sec5_video_cover_image;
                        }
                    /* home page video */
                    
                    if($postData['sec5_video_link'] != ''){
                        $video_link         = explode("watch?v=", $postData['sec5_video_link']);
                        $sec5_video_code    = $video_link[1];
                    } else {
                        $sec5_video_code    = $data['row']->sec5_video_code;
                    }
                    
                    $fields = [
                        'sec2_title'                        => $postData['sec2_title'],
                        'sec2_description'                  => $postData['sec2_description'],
                        'sec3_title'                        => $postData['sec3_title'],
                        'sec3_description'                  => $postData['sec3_description'],
                        'sec4_title'                        => $postData['sec4_title'],
                        'sec4_description'                  => $postData['sec4_description'],
                        'sec5_title'                        => $postData['sec5_title'],
                        'sec5_description'                  => $postData['sec5_description'],
                        'sec5_description2'                 => $postData['sec5_description2'],
                        'sec5_video_cover_image'            => $sec5_video_cover_image,
                        'sec5_video_link'                   => $postData['sec5_video_link'],
                        'sec5_video_code'                   => $sec5_video_code,
                        'sec6_title'                        => $postData['sec6_title'],
                        'sec6_description'                  => $postData['sec6_description'],
                    ];
                    // Helper::pr($fields);
                    HomePage::where($this->data['primary_key'], '=', 1)->update($fields);
                    return redirect("admin/" . $this->data['controller_route'] . "/list")->with('success_message', $this->data['title'].' Updated Successfully !!!');
                } else {
                    return redirect()->back()->with('error_message', 'All Fields Required !!!');
                }
            }
            echo $this->admin_after_login_layout($title,$page_name,$data);
        }
    /* list */
}
