<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
//
use Inertia\Inertia;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //starts here
    public function index(Request $request)
    {
        request()->validate([
            'direction'=> ['in:asc,desc'],
            'field' => ['in:id,name,last_name,nrna_id,state,telephone,created_at']
        ]);
        $query =User::query();

        // if(request('direction')){
        //     $query->orderBy('id',request('direction'));

        // }else{
        //     $query->orderBy('id','desc');

        // }

        if(request('search')){
            $query->where('last_name', 'LIKE', '%'.request('search').'%');
        }
        if(request('name')){
            $query->where('name', 'LIKE', '%'.request('name').'%');
        }
        //
         if(request('nrna_id')){
            $query->where('nrna_id', 'LIKE', '%'.request('nrna_id').'%');
        }
        //
        if(request()->has(['field', 'direction'])){
            $query->orderBy(request('field'), request('direction'));
        }else{
            $query->orderBy('id','desc');
        }
        //the following lines are for the first type of search

        // $users =Message::when( $request->term,
        //     function($query, $term){
        //     $query->where('to', 'LIKE', '%'.$term.'%' );
        // })->paginate(20);

         $users =$query->paginate(20);
        // $users =$users->sortBy('created_at')->reverse();
        return Inertia::render('User/Index', [
          'users' => $users,
          'filters' =>request()->all(['name','nrna_id','field','direction'])

        ]);


    }
    //ends here
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

        // $privatekey =get_random_string(10);
        // $privatekey ="testing";
        // $encryptedValue =Crypt::encryptString($privatekey);
        // $decrypted = Crypt::decryptString($encryptedValue);
        // echo $encryptedValue;
        // dd($decrypted);

        //
        // $startName  ="csv_files/selected_nrna_members_20210802_01.csv";
        // $startName  ="csv_files/germany_july28_final_02.csv";


        $startName  ="csv_files/global_candidates.csv";
       // var_dump($startName);
        //return 0;
        $csvName  =storage_path($startName);
        // var_dump($csvName);

        // $file = fopen(csvName,"r");
        // $csv = array_map('str_getcsv', file($csvName));
        $csv_array = csv_to_array($csvName,";");
        //read users
        // var_dump($csv_array);
        // dd($csv_array);
         $users = User::all();
         $su =$users->where('email',"roshyara@gmail.com")->first();
        //  dd($su);
         if(!$su){
             $role       =Role::where('name', 'Superadmin')->first();
             $permssion  =Permission::where('name', 'send code')->first();
             $this->create_permissions_to_role($role->id, $permssion->id);
             $su->assignRole($role);
             //echo "found";
             $role        = Role::where('name', 'Superadmin')->first();
             $permission  = Permission::where('name', 'send code')->first();
             $role->givePermissionTo($permission);
             $su->assignRole($role);
             // return "test";:
            // $btemp      =auth()->user()->hasAnyPermission('send code');
             //var_dump($btemp);
            //  dd($su->hasAnyPermission('send code'));
            //  dd($su->getPermissionsViaRoles());

             //  $su->assignRole('Superadmin');
            // create_permissions_to_role($roleId, $permssionid )
        //}
         //$user = DB::table('users')->find(3);



            //  $btemp      =auth()->user()->hasAnyPermission('send code');
            // dd($btemp);
            //  dd($su->hasAnyPermission('send code'));
            //  dd($su->getPermissionsViaRoles());

             //  $su->assignRole('Superadmin');
            // create_permissions_to_role($roleId, $permssionid )
        }
         //$user = DB::table('users')->find(3);
         $laufer =0;
        //  dd($csv_array);
        foreach($csv_array as $element){
            /**
            * each row is a user . So we need to create a user
            *@user : new USER
            */
            //  dd($element);
            $laufer +=1;
            $user  =User::where('user_id', trim($element['user_id']))->first();
            // dd($user);
             if($user){

                echo "User Exists-> line: ".$laufer.", user name ". $user->name. ", user_id:". $user->user_id ."<br>\n";

            }else{
                /***
                 *
                 * create new user here
                 *
                 */
                $user             = new User;
                $user->email      =$element ['email'];
                $user->password   =Hash::make($element ['password']);
                echo  $element ['user_id'].'<br/>';

                }
                  $user->name       =$element ['name'];
                  $user->region     =$element ['region'];
                //   $user->password   =$element ['password'];

                  $user->user_id    =$element ['user_id'];
                  $user->nrna_id    =$element ['nrna_id'];
                  $user->is_voter   =$element ['is_voter'];
                //dd($user);
                  $user->save();






        }



    }//end of store method


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userid)
    {
        //
        $user = DB::table('users')->where('user_id', $userid)->first();
        // dd(config('app.name'));
        // config('app.name') ="test";
        config(['app.name' => "Home Page of ". $user->name ]);
        if(!isset($user)){
                return Response(['error'=>'Resources not found'],404);

        }
            // dd($user);
            // $results = Cart::with('users')->where('status',1)->getOrFail();
        return Inertia::render('User/Profile', [
          'user' => $user,
          'isLoggedIn'=> Auth::check()

        ]);

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
        if(!auth()->check()){
            return Response(['error'=>'Resources not found'],404);
        }
        $user = User::where('id',$id)->first();
         if(!isset($user)){
            return Response(['error'=>'Resources not found'],404);
        }

          $user= $user->only(['id',
            'name_prefex',
            'first_name',
            'middle_name',
            'last_name',
            'name',
            'gender',
            'region',
            'country',
            'state',
            'street',
            'hosuenumber',
            'postalcode',
            'city',
            'nrna_id',
            'telephone',
            'email',
            'password',
            'profile_photo_path',
            'profile_bg_photo_path',
            'designation',
            ]);
        return Inertia::render('User/EditProfile', [
                'user' => $user,
                'isLoggedIn'=> Auth::check()
              ]);
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
        $user =User::where('id',$id)->first();
        //if the following variables are null, change them empty string.
        if(!isset($request->name_prefex)){
            $request['name_prefex'] ='';
        }
        if(!isset($request->gender)){
            $request['gender'] ='';
        }
        if(!isset($request->state)){
            $request['state'] ='';
        }
        if(!isset($request->street)){
            $request['street'] ='';
        }
        if(!isset($request->postalcode )){
            $request['postalcode'] ='';
        }
        if(!isset($request->designation )){
            $request['designation'] ='';
        }

        Validator::make($request->all(), [
            'name_prefex'       => ['string', 'max:10'],
            'firstName'         => ['required', 'string', 'max:255'],
            'lastName'          => ['required', 'string', 'max:255'],
            // 'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email'             => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telephone'         => ['required',  'max:18', Rule::unique('users')->ignore($user->id)],
            'region'            =>['required', 'string', 'max:255'],
            // 'name'         =>['required', 'string', 'max:255', 'unique:users'],
            // 'nrna_id'       =>['required', 'string', 'max:255', 'unique:users'],
            // 'first_name'    =>['required', 'string', 'max:255'],
            // 'middle_name'    =>['required', 'string', 'max:255'],
            'gender'            =>[ 'string', 'max:255'],
            'telephone'         => ['string',  'max:255', 'unique:users'],
            'country'           => ['required', 'string',  'max:255'],
            'state'             => ['string',  'max:255'],
            'street'            => ['string',  'max:255'],
            'housenumber'       => ['string',  'max:20'],
            'postalcode'        => ['string',  'max:20'],
            'city'              => ['required', 'string',  'max:255'],
            'designation'        => ['string',  'max:255'],
            // 'terms'         => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();
        //set the name
        $_name ="";
        if($request['name_prefex']!=""){
            $_name =$request['name_prefex'];
        }
        if($request['firstName']!=""){
            $_name .=" ".$request['firstName'];
        }
        if($request['middleName']!=""){
            $_name .=" ".$request['middleName'];
        }
        if($request['lastName']!=""){
            $_name .=" ".$request['lastName'];
        }
        $_name =trim($_name);

        //
        if ($request['email'] !== $user->email &&
        $user instanceof MustVerifyEmail) {
        $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name_prefex'    => $request['name_prefex'],
                'first_name'    => $request['firstName'],
                'middle_name'   => $request['middleName'],
                'last_name'     => $request['lastName'],
                'name'          => $_name,
                'email'         => $request['email'],
                'telephone'     => $request['telephone'],
                'gender'        =>$request['gender'],
                'country'       =>$request['country'],
                'state'         =>$request['state'],
                'city'          =>$request['city'],
                'street'        =>$request['street'],
                'housenumber'   =>$request['housenumber'],
                'postalcode'    =>$request['postalcode'],
                'designation'    =>$request['designation'],

            ])->save();
        }
        return redirect()->route('user.show', ['profile' => $user->user_id]);

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



    //make permissions
    /**
     *
     */
    // public function create_role_and_permission ($roleName, $permssionVec){

    //      //$role = Role::create(['name' =>$roleName]);
    //      for ($i=0; $i<sizeof($permssionVec); $i++){
    //         $permission = Permission::create(['name' => $permssionVec[$i]]);
    //     }

    // }
    public function create_permissions_to_role($roleId, $permssionid ){
        $role = Role::findById($roleId);
        $permission = Permission::findById($permssionid);
        // $role ->givePermissionTo()
        $role->givePermissionTo($permission);
    }

}


