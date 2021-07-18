<?php

namespace App\Http\Controllers;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MakeurlController extends Controller
{
    //
    public  static function getFile($filename){
        // dd("test");
        // $filename ="election_notice_01_20210628";        
        $pdf_path ="pdf_files/";
        $headers =['Content-Type:application/pdf']; 
        $file_name =$pdf_path.$filename.".pdf";
        $file=Storage::disk('public')->path($file_name);
        // dd($file); 
        // return route($file);
         return response()->download($file, $filename.".pdf", $headers);
    
        // return (new Response($file, 200))
        //     //   ->header('Content-Type', 'image/jpeg');
        //     ->header('Content-Type', 'application/pdf');

    }
    public static function make_pdf_prl($file_path, $file_name){ 
        // $file =Storage::disk('public')->get($file_name);
        $headers =['Content-Type:application/pdf']; 
         $storage_path_name =Storage::url($file_path.$file_name.".pdf");
         // dd($storage_path_name); 
        // ("test");
        $content =Storage::get(storage_path_name);
         return response()->url($storage_path_name, $headers);
    }
    public function make_image_url(){

    } 
}
