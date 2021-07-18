<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
//
use Inertia\Inertia; 
use App\Http\Controllers\MakeurlController;
use Illuminate\Support\Facades\Storage;
class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $notices = Notice::all();
        // dd($notices);
          // dd(Post::all());
            //$query =Post::query();
            //  $query =Notice::query(); 
            //  $notices =$query->paginate(120); 
             $notice =$notices->find(1);
            //  dd(Storage::url('images/nrna_logo'));
            // $file_path, $file_name
            foreach ($notices as $notice){
                // $file_path          ="pdffiles/";
                // $file_name          =$notice->pdf_path; 
                // $headers           =['Content-Type:application/pdf']; 
                // $file_path_name     =$file_path.$file_name.".pdf";
                // $url_path           = Storage::url($file_path_name, $headers);
                // dd($url_path);
                $url_path  ="/get/".$notice->pdf_path;
                // $response_path      =response()->download($url_path, $headers);
                $notice->url_path = $url_path;
            };
            // dd($notices); 
            // MakeurlController::make_pdf_prl($file_name,$file_name); 
             //$notice->put('pdfurl');
        return Inertia::render('Notice/IndexNotice',[
            'notices' => $notices 
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
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notice $notice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice)
    {
        //
    }
}
