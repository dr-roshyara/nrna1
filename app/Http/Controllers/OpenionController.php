<?php

namespace App\Http\Controllers;

use App\Models\Openion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\User;
class OpenionController extends Controller
{
    /***
     *
     * Openion
     */
    public function userOpenions($user_id){
        //  return response($user_id);
        //  $userId =Auth::user()->user_id;
         if(!isset($user_id)){
            return null;
         }
         $user = User::where('id', $user_id)->first();
        // return response($user);
         // dd("test");
        // dd(auth()->user());
        $openions = QueryBuilder::for(Openion::class)
                ->allowedIncludes(['user'])
                ->with('user')
                ->where('openions.user_id','=', $user->id)
                // ->join('users', 'users.id','=','openions.user_id')
                ->orderBy('id', 'desc')->get();

        return response($openions->toJson());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $openions = QueryBuilder::for(Openion::class)
         ->with(['user'])
        ->orderBy('id', 'desc')->get();
        //  $openions->load(['user'=>function($query){
        //     // $query->select(['id']);
        // }]);
        //   dd($openions->get());
        return response($openions->toJson());
        //
        // return Inertia::render('Dashboard/MainDashboard');
        // echo " Here we will display different openions";
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
        // dd(Auth::user());

        $request->validate([
            'body'=> ['required'],
        ]);
        $openion = new Openion();
        if(isset($request['title'])){
            $openion->title =$request['title'];
        }
          if(isset($request['hash_tag'])){
            $openion->hash_tag =$request['hash_tag'];
        }
         $_body     =$request['body'];
        // $_body      =nl2br($_body);
        $_breaks      =" ".'&lt;br/&gt;'." ";
        $_body        = preg_replace("/\r\n|\r|\n/",   $_breaks , $_body);
        $_body =str_replace(PHP_EOL,   $_breaks, $_body);
        // $_body        = htmlspecialchars($_body, ENT_QUOTES);
        // dd($_body);
        $openion ->body =$_body;
        // dd($openion);
         $openion->user_id =auth()->user()->id;
        // dd($openion);
        $openion->save();
         return redirect()->route('user.show', ['profile' => auth()->user()->user_id]);
        //  return redirect()->route('dashboard')->with('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function show(Openion $openion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $request->validate([
            'openion'=> ['required'],
        ]);
        //check if $id is given
        if($request==null){
            return redirect()->back();

        }
        $user =Auth::user();
        $openion =$request['openion'];

        if(isset($openion["user"])){
            if($user->id !=$openion["user"]["id"]){
                return redirect()->back()->with('rejected');
            }else{
                $id               = $openion["id"];
                $_deleteOpenion   =Openion::find($id);

                if($_deleteOpenion!=null){
                     return redirect()->back()->with('message', "validation_success");

                    // return Inertia::render('Openion/Edit', [
                    //     'openion' => $openion,
                    //     'authUser'=>$user,
                    //     'isLoggedIn'=> Auth::check()
                    // ]);

                }
                return redirect()->back()->with('rejected');

            }
        }
           return redirect()->back()->with('rejected');


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /***
         * What to do ?
         * 1. First check if the submitted openion id belongs to auth user or not
         * 2. Assign new title , body and hash_tags
         * 3. save
         */

       $request->validate([
            'openion' => ['required'],
            'authUser'=> ['required'],

        ]);

         $openion =$request['openion'];
         $authUser =$request['authUser'];
        if(isset($openion['id'])){
            $openionId =$openion['id'];
            $_openion =Openion::where('id','=',$openionId)->first();
            // dd($_openion);
            //check user ;
            $_user_validity =Auth::check(); //if logged in
            $_user_validity =$_user_validity &&(Auth::user()->id===$authUser["id"]); //if logeduser is same is authuser
            $_user_validity =$_user_validity &&($authUser["id"] ==$_openion->user_id ); // if openion belongs to authuser .
            if($_user_validity && ($openion["id"] ===$_openion->id))
            {
                //go ahead
                if(isset($openion['title'])){
                            $_openion->title =$openion['title'];
                }
                if(isset($openion['body'])){
                    $_body          =$openion['body'];
                    // dd($_body);
                    // $_body        =nl2br($_body);
                    // $_body        = htmlspecialchars($_body, ENT_QUOTES);
                    $_body        = preg_replace("/\r\n|\r|\n/", ' &lt;br/ &gt; ', $_body);
                    //  dd($_body);
                    $_openion->body =$_body ;
                }
                if(isset($openion['hash_tag'])){
                            $_openion->hash_tag =$openion['hash_tag'];
                }
                $_openion->save();
                return redirect()->back()->with(["message"=>"openion saved"]);


            }else{
                return redirect()->back()->with(['message'=>'Not saved']);
            }


        }else{
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Openion  $openion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $request->validate([
            'openion'=> ['required'],
        ]);
        //check if $id is given
        if($request==null){
            return redirect()->back();

        }
         $user =Auth::user();
         $openion =$request['openion'];

        if(isset($openion["user"])){
            if($user->id !=$openion["user"]["id"]){
             return redirect()->back();
          }else{
              $id               = $openion["id"];
              $_deleteOpenion   =Openion::find($id);
              if($_deleteOpenion!=null){
                 $_deleteOpenion->delete();

              }
              return redirect()->route('user.show', ['profile' => auth()->user()->user_id]);
          }

          }
           return redirect()->back();

        return redirect()->route('user.show', ['profile' => auth()->user()->user_id]);


        //check if auth user has the openion $id
        //destroy it.
        // dd($id);


    }

    public function search(Request $request){

        return "test";


    }
}
