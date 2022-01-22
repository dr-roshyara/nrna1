<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Vote;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
    //
    
    public function index(){
        
        $users = DB::table('users')->get();
        dd($users);
        //
        $codes = DB::table('codes')->get();
        dd($codes); 
        //
        $votes = DB::table('votes')->get();
        dd($votes);
        //
        $result = DB::table('results')->get();
        dd($result);
        
    }


}
