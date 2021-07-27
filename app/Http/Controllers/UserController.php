<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

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
            'field' => ['in:id,first_name,last_name,nrna_id,state,telephone,created_at']
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
        if(request('first_name')){
            $query->where('first_name', 'LIKE', '%'.request('first_name').'%');
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
          'filters' =>request()->all(['first_name','nrna_id','field','direction'])  
 
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
        //
        $startName  ="csv_files/final_nrna_member_20210726_1714.csv";
        //var_dump($startName);
        //return 0;
        $csvName  =storage_path($startName); 

        // $file = fopen(csvName,"r");
        // $csv = array_map('str_getcsv', file($csvName));
        $csv_array = $this->csv_to_array($csvName,";");
        //read users 
        var_dump($csv_array);
         $users = User::all();  
         $su =$users->where('email',"roshyara@gmail.com")->first();
         if($su){
             //$role       =Role::where('name', 'Superadmin')->first();
             //$permssion  =Permission::where('name', 'send code')->first();
             //$this->create_permissions_to_role($role->id, $permssion->id);
             //$su->assignRole($role);
             //echo "found";
             $role        = Role::where('name', 'Superadmin')->first();
             $permission  = Permission::where('name', 'send code')->first();
            //  $role->givePermissionTo($permission);
            //  $su->assignRole($role);
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
        // dd($csv_array);
        foreach($csv_array as $element){
            /**
            * each row is a user . So we need to create a user 
            *@user : new USER  
            */
            //first check if user already exists
            $cur_user  =$users->where('email', $element['email']);
            // dd($cur_user);
            $laufer +=1;
            if(count($cur_user)>0){
                echo "User Exists-> line: ".$laufer."<br>\n";
                 
                // dd(count($cur_user));
            }else{

                // dd($element);    
                $user = new User;
                 //first name 
                 if (array_key_exists('name_prefex', $element))
                 {
                     if($element['name_prefex']){
                        $user->name_prefex = $element['name_prefex'];  
                     }else{
                         $user->name_prefex ="";
                     }

                 }    
                //first name 
                if (array_key_exists('first_name', $element))
                    {
                        if($element['first_name']){
                           $user->first_name = $element['first_name'];  
                        }else{
                            $user->first_name ="-";
                        }

                    }
                    //middle name 
                if (array_key_exists('middle_name', $element))
                    {
                        // $user->middle_name = $element['middle_name'];
                        if($element['middle_name']!=""){
                            $user->middle_name = $element['middle_name'];  
                         }else{
                             $user->middle_name ="-";
                         } 
 
                    }  
                       
                    //last_name
                if (array_key_exists('last_name', $element))
                    {
                        // $user->last_name = $element['last_name'];
                         // $user->middle_name = $element['middle_name'];
                         if($element['last_name']!=""){
                            $user->last_name = $element['last_name'];  
                         }else{
                             $user->last_name ="-";
                         }
                    }
                    //middle name 
                if (array_key_exists('email', $element))
                {
                    // $user->email = $element['email'];
                    if($element['email']!=""){
                        $user->email = $element['email'];  
                     }else{
                         $user->email ="test".$laufer."@test.de";
                     }
                
                }
             
                //gender
                if (array_key_exists('gender', $element))
                {
                    // $user->gender = $element['gender'];
                    if($element['gender']!=""){
                        $user->gender = $element['gender'];  
                     }else{
                         $user->gender ="";
                     }

                }
                
                //country;
                if (array_key_exists('country', $element))
                {
                    // $user->country = $element['country'];
                    if($element['country']!=""){
                        $user->country = $element['country'];  
                     }else{
                         $user->country ="-";
                     }
                     
                }
                //password;
                if (array_key_exists('password', $element))
                {
                    $user->password =Hash::make($element['password']);
                }
                //city;
                if (array_key_exists('city', $element))
                {
                    // $user->city = $element['city'];
                    
                    if($element['city']!=""){
                        $user->city = $element['city'];  
                     }else{
                         $user->city ="-";
                     }
                }
              
                //state;
                if (array_key_exists('state', $element))
                {
                    // $user->state = $element['state'];
                    if($element['state']!=""){
                        $user->state = $element['state'];  
                     }else{
                         $user->state ="-";
                     }
                }
                
                //telephone;
                if (array_key_exists('telephone', $element))
                {
                    // $user->telephone = "49".$element['telephone'];
                    if($element['telephone']!=""){
                        $user->telephone = "49".$element['telephone'];  
                     }else{
                         $user->telephone ="No_".$laufer;    
                     }
                     
                } 
                //nrna_id;
                if (array_key_exists('nrna_id', $element))
                {
                    // $user->nrna_id = $element['nrna_id'];
                    if($element['nrna_id']!=""){
                        $user->nrna_id = $element['nrna_id'];  
                     }else{
                         $user->nrna_id ="nrna_id_".$laufer;
                     }
                }
                //additional_address
                if (array_key_exists('additional_address', $element))
                {
                    // $user->additional_address = $element['additional_address'];
                    if($element['additional_address']!=""){
                        $user->additional_address = $element['additional_address'];  
                     }else{
                         $user->additional_address ="";
                     }
                } 
                //
            
                $user->postalcode =0;
                if($user->name_prefex!=""){
                    $user->name = "$user->name_prefex"." ".$user->first_name. "  ". $user->middle_name. " ". $user->last_name;
                }else{
                    $user->name = $user->first_name. "  ". $user->middle_name. " ". $user->last_name;
                }
                // lcc 
                  //lcc
                if (array_key_exists('lcc', $element))
                {
                    // $user->additional_address = $element['additional_address'];
                    if($element['lcc']!=""){
                        $user->lcc = $element['lcc'];  
                     }else{
                         $user->lcc ="";
                     }
                }
                //

                // dd($user);        
                    // dd($user); 
                        
                    $user->save();
            }


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
        $user = DB::table('users')->where('id', $id);
        return Inertia::render('User/Profile', [
          'user' => $user,
 
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
    //
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


