<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Requests\StoreImage;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //
         $_image_path ='';
         $user = Auth::user();
         //save the image and get the image path
           $_imageType = $request['image_tpye'];
        //    dd($request->all());
        //    $this->save_and_get_filename($request->file('image'), $_imageType);

         if($request->hasFile('image')){
             $_file =$request->file('image');
             $_image_path =$this->save_and_get_filename($_file, $_imageType);
             $user->profile_bg_photo_path ="/storage/".$_image_path;
               // Create Image Model
            $_image =new Image();
            $_image->path =$_image_path;
            $_image->type =$_imageType ;
            $_image->user_id= $user->id;
             $_image->save();
             $user->save();
         }

        //  dd("test");
        //finally return back to the page
        //  return redirect('user.show', ['user_id'=> $user->user_id]);
         return redirect()->route('user.show', ['profile' => $user->user_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        //
    }
   /***
     *
     * Save files bzw rechnungen
     * @params type: Illuminate\Http\UploadedFile $file
     * @returen : String
     *
     */

    public function save_and_get_filename($file, $type)
    {

         /**
          **
          * chek if file is empty
          * inputtype: $file has a Type :  Illuminate\Http\UploadedFile
          *mkdir("/documents/gfg/articles/", 0770, true)
          *is_dir( $file )
          *storage/app/public/users

          */
          $_baseDir = "users/".Auth::user()->user_id;
        if($type=="profile"){
                $target_dir = "profile";
                $_baseDir.="/". $target_dir;
           }
        if($type=="avatar"){
             $target_dir = "avatar";
             $_baseDir.="/". $target_dir;
        }

            // dd($_baseDir);
            $_tfValue = is_dir($_baseDir);
            // dd( $_tfValue);
            if(!$_tfValue){
                var_dump($_baseDir);
                mkdir($_baseDir, 0770, true);
                // dd($_baseDir);
            }

            $date = new \DateTime();

        /**
         *
         * basename (/file_name.ext)  gives file_name.ext .
         *
         */
            $target_filename = $date->getTimestamp()."_". basename($file->getClientOriginalName());
            $target_filename = preg_replace('/\s+/', '_', $target_filename);
            $target_filename =strtolower($target_filename);
            dd( toLower($target_filename));

        /**
        * storeAs function has three parameters
        * Store the uploaded file on a filesystem disk with public visibility.
        *
        * @param  string  basic_dir
        * @param  string  file_path
        * @param  array|string  $options
        * @return string|false
        */

        // $file_path = $file->storeAs($_baseDir, $target_filename, 'public');
        $file_path = $file->storeAs($_baseDir, $target_filename, 'public');
        //   dd("test");

        return $file_path;
    }

    public function avatarUpload(Request $request){
        //    return ("tessting ");
    //    dd($request->all());
    //    $_file =$request['avatar1'];
       $_file =$request->file('avatar1');
    //    dd($_file);
        $_image_path ='';
        $user = Auth::user();
       $_imageType ="avatar";
       $_image_path =$this->save_and_get_filename($_file, $_imageType);
       $user->profile_icon_photo_path ="/storage/".$_image_path;
            // Create Image Model
        $_image =new Image();
        $_image->path =$_image_path;
        $_image->type =$_imageType ;
        $_image->user_id= $user->id;
        $_image->save();
        $user->save();
        return "success ";
    }


}
