<?php

namespace App\Http\Controllers;

use App\Models\DeligateCandidacy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeligateCandidacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $candidacies =[];
        return Inertia::render('DeligateCandidacy/IndexDeligateCandidacy', [
            'candidacies' =>$candidacies

        ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function show(DeligateCandidacy $deligateCandidacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function edit(DeligateCandidacy $deligateCandidacy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeligateCandidacy $deligateCandidacy)
    {
        //
        
        //
        $startName  ="csv_files/deligatecandidate_upload.csv";
        var_dump($startName);
        //return 0;
        $csvName  =storage_path($startName); 

        // $file = fopen(csvName,"r");
        // $csv = array_map('str_getcsv', file($csvName));
        $csv_array = $this->csv_to_array($csvName,";");
    //here starts 
    
    /******************************* */ 
         //$user = DB::table('users')->find(3);
         $candis = DeligateCandidacy::all();
         $laufer =0;
        // dd($csv_array);
        foreach($csv_array as $element){
            /**
            * each row is a user . So we need to create a user 
            *@user : new USER  
            */
            //first check if user already exists
            $cur_candi  =$candis->where('user_id', $element['user_id']);
            // dd($cur_candi);
            $laufer +=1;
            if(count($cur_candi)>0){
                echo "Candis Exists-> line: ".$laufer."<br>\n";
                 
                // dd(count($cur_user));
            }else{

                // dd($element);    
                $candi = new DeligateCandidacy;
                 //User_id
                 if (array_key_exists('user_id', $element))
                 {
                     if($element['user_id']){
                        $candi->user_id = trim($element['user_id']);  
                     }else{
                         dd("Problem with adding the line user_id");
                     }

                 }    
                //candidacyid 
                if (array_key_exists('nrna_id', $element))
                 {
                     if($element['nrna_id']){
                        $candi->nrna_id = trim($element['nrna_id']);  
                     }else{
                         dd("Problem with adding the line nrna_id");
                     }

                 }  
                //candi name  
                if (array_key_exists('name', $element))
                 {
                     if($element['name']){
                        $candi->name = trim($element['name']);  
                     }else{
                         dd("Problem with adding the line candidacy_name");
                     }

                 }  
          
        
                    //   //candi name  
                if (array_key_exists('post_id', $element))
                {
                    if($element['post_id']){
                        $candi->post_id = trim($element['post_id']);  
                    }else{
                        dd("Problem with adding the line post_id");
                    }

                }
                //description 
                if (array_key_exists('description', $element))
                {
                    // dd($element);
                    if($element['description']){
                       $candi->description = $element['description'];  
                    }else{
                        dd("Problem with adding the line description");
                    }

                }  
                //image path       
                if (array_key_exists('image_path_1', $element))
                {
                 
                    if($element['image_path_1']){
                        $candi->image_path_1 = $element['image_path_1'];  
                    }else{
                        dd("Problem with adding the line image_path_1");
                    }

                }
                
                if (array_key_exists('image_path_2', $element))
                {
                    if($element['image_path_2']){
                        $candi->image_path_2 = $element['image_path_2'];  
                    }else{
                        dd("Problem with adding the line image_path_2");
                    }

                }
                // next 
                if (array_key_exists('image_path_3', $element))
                {
                    if($element['image_path_3']){
                        $candi->image_path_3 = $element['image_path_3'];  
                    }else{
                        dd("Problem with adding the line image_path_3");
                    }

                }
                            
                // dd($candi);
                $candi->save();
         


        }    
    }
     


    }//ends the update method 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeligateCandidacy  $deligateCandidacy
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeligateCandidacy $deligateCandidacy)
    {
        //
    }
        //save array
        public function  csv_to_array($filename='', $delimiter=';')
        {
            if(!file_exists($filename) || !is_readable($filename)){
                echo "file is not readable";
               return FALSE;
    
            }
                
    
            $header = NULL;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== FALSE)
            {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
                {
                    if(!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }
            return $data;
        }
    
} //end of the controllre 
