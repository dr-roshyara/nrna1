<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
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
        //
        $csvName =storage_path("csv_files/germany_final_20210718_02.csv");        
        // $file = fopen(csvName,"r");
        // $csv = array_map('str_getcsv', file($csvName));
        $csv_array = $this->csv_to_array($csvName,";");
        // dd($csv_array);
        foreach($csv_array as $element){
            /**
            * each row is a user . So we need to create a user 
            *@user : new USER  
            */
            $user = new User;
           //first name 
           if (array_key_exists('first_name', $element))
            {
                $user->first_name = $element['first_name'];
            }
            //middle name 
           if (array_key_exists('middle_name', $element))
            {
                $user->middle_name = $element['middle_name'];
            }        
            //middle name 
           if (array_key_exists('last_name', $element))
            {
                $user->last_name = $element['last_name'];
            }
            dd($user);
                


        }
        //dd(fgetcsv($file));



    }//end of store method

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
       public function  csv_to_array($filename='', $delimiter=';')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

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

}
