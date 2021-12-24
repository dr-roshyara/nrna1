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
        // $startName  ="csv_files/selected_nrna_members_20210802_01.csv";
        $startName  ="csv_files/germany_july28_final_02.csv";
        $startName  ="csv_files//global_candidates.csv";
       // var_dump($startName);
        //return 0;
        $csvName  =storage_path($startName); 
        // var_dump($csvName);
      
        // $file = fopen(csvName,"r");
        // $csv = array_map('str_getcsv', file($csvName));
        $csv_array = $this->csv_to_array($csvName,";");
        //read users 
        //var_dump($csv_array);
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
        // dd($csv_array);
        foreach($csv_array as $element){
            /**
            * each row is a user . So we need to create a user 
            *@user : new USER  
            */
            // dd($element);
            $laufer +=1;
            $cur_user1  =User::where('nrna_id', trim($element['nrna_id']))->first();
            // $cur_user1  =User::where('nrna_id', trim($element['nrna_id']))->first();
            // dd($cur_user1);
            // $cur_user1  =$users->where('telephone', '49'.$element['telephone']);
            //  $n2 =count($cur_user1);
             if($cur_user1){
                  
                echo "User Exists-> line: ".$laufer.", user name ". $cur_user1->name. "<br>\n";
                // $cur_user1->is_voter =1;
                // $cur_user1->save();

            }else{
                /***
                 * 
                 * create new user here 
                 * 
                 */
                  $user             = new User; 
                  $user->name       =$element ['name'];
                  $user->email      =$element ['email'];
                  $user->region     =$element ['region'];
                //   $user->password   =$element ['password'];
                  $user->password   =Hash::make($element ['password']);
 
                  $user->nrna_id    =$element ['nrna_id'];
                  $user->is_voter   =$element ['is_voter'];
                //dd($user);
                  $user->save();
                  
                  
                  
                    
                echo  $element ['nrna_id'].'<br/>'; 
            }
        }



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


