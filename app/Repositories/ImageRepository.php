<?php
namespace App\Repositories;

use App\Interfaces\ImageRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class ImageRepository implements ImageRepositoryInterface
{
  // define a method to upload our image
    public function upload_image($base64_image,$image_type){

        $image_64 = $base64_image["compressed"];
        $extension = explode('/',$image_64['type']);
        $extension = $extension[1];
       $file_name= $this->save_and_get_filename($image_64,$extension, $image_type);
       return $file_name;
    }

     public function save_and_get_filename($file,$extension, $type)
    {

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
            $target_filename = $date->getTimestamp()."_". basename($file["file"]->getClientOriginalName());
            $target_filename = preg_replace('/\s+/', '_', $target_filename);
            $target_filename =strtolower($target_filename);
             $_filename =$_baseDir."/".$target_filename;
            //take the base24 string first
            $base64Image = explode(";base64,", $file["base64"]);
            if(isset($base64Image[1])){
                Storage::disk('public')->put($_filename , base64_decode($base64Image[1]));

            }
            Storage::disk('public')->put($_filename , base64_decode($base64Image[1]));
            // Storage::disk('public')->put($_filename, base64_encode($file->getClientMimeType()));
            // dd("test");
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
        //$file_path = $file['file']->storeAs($_baseDir, $target_filename, 'public');
        //dd("test");

        return $_filename;
    }
}
