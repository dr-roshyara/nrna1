<?php

namespace App\Http\Controllers;

use App\Models\Candidacy;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Upload;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use Storage; 
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CandidacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     //starts here 
    public function index(Request $request)
    {
        //  dd(Candidacy::all());
        //  $candi = Candidacy::where('candidacy_id','2021_02')->first();
        //  echo $candi;
        //  dd($candi->user);
          

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('candidacy_id', 'LIKE', "%{$value}%");
                // $query->where('itemId', "{$value}");
                // ->orWhere('warehouseId', 'LIKE', "%{$value}%");
            });
        });

        $candidacies = QueryBuilder::for(Candidacy::class)
        ->defaultSort('post_id')
        ->allowedSorts(['candidacy_id', 'post.name', 'post.post_id', 'user.name', "user.nrna_id"])
        ->allowedFilters(['user.name','candidacy_id',  $globalSearch])
        ->paginate(100) 
        ->withQueryString();
       
        /**
         * 
         * Load User 
         */
        $candidacies->load(['user' => function ($query) {
            $query->select(['id','name','user_id', 'nrna_id']);
            // $query->withTraced()->select('name');
            //$query->orderBy('published_date', 'asc');
            // return($query->get('name'));
        //  $qs =$query->select('name');
        //  dd($query);
        // return $query->pluck('name');
        // return();
        }]);
        /**
         * 
         * Load Post 
         */
        $candidacies->load(['post' => function ($query) {
            $query->select(['id','post_id','name','is_national_wide']);
            // $query->withTraced()->select('name');
            //$query->orderBy('published_date', 'asc');
            // return($query->get('name'));
            //  $qs =$query->select('name');
            //  dd($query);
            // return $query->pluck('name');
            // return();
        }]);
        // dd($candidacies);
        return Inertia::render('Candidacy/Index', [
            'candidacies'=>$candidacies
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([                
                'user.name' => 'Candidate Name',
                // 'warehouse.name'  => 'Warehouse ',
                // 'variationId'   => 'Variation Id',
                // 'itemId'   =>  'Item Id',
            ])->addFilter('warehouseId', 'Warehouse', [
                // '3' => 'Sulzbach',
                // '4' => 'Flörsheim',
                // 'manufacturer.name' =>'Manufacturer',
                // 'nl' => 'Nederlands',
            ])->addColumns([
                'candidacy_id'        => 'Candidacy Id',
                'post_id'             => 'Post Id',
                'post_name'            =>'Post Name',
                'nrna_id'              =>"Candidate's NRNAID Name",
                'user_name'            =>"Candidate's Name",
                
            ]);
        });

        //ends  
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
          $query =Post::all();

        //   dd($query->pluck('post_id'));
        // return redirect()->route('candidacy.index');
         return Inertia::render('Candidacy/CreateCandidacy', [
            'posts' =>$query,  
            'name'  => ""
         ]);
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
        //  dd($request->all());
       $request->validate([
            'name'=>['required'],
            //  'proposer_name'=>['required'],
             'proposer_id'=>['required'],
            //  'supporter_name'=>['required'],
             'supporter_id'=>['required'], 
            //  'post_name' =>['required'],            
            // 'image' =>['image'],
            // 'name' =>['required'],
            
            // 'name' =>['unique:candidacies,candidacy_name'],
            // 'user_id'=>'unique:candidacies, user_id'
        ]);
        //save 
        // dd($request->all());
        $post               =Post::all()->where('post_id', $request['post_id']);
        $post_name          =$post->first()->name;
        // $post_nepali_name   =$post->first()->nepali_name;        
        if($request->file('image')){
             $uploadedFile      = $request->file('image');
             $filename          =time().$uploadedFile->getClientOriginalName();
        
        } 
        // $candidacy           =['user_id' => auth()->user()->id,
        // 'candidacy_id'       => auth()->user()->id];
         $candidacy          =new  Candidacy;
        //  $this.validate_input($candidacy); 
        //   dd($candidacy); 
        $candidacy['user_id'] = 1; 
         $candidacy['candidacy_id']         = $request['nrna_id']; 
        $candidacy['candidacy_name']        =$request['name'];                  
        // $candidacy['post_name']             =$post_name; 
        //  $candidacy['post_nepali_name']      =$post_nepali_name; 
       /** change psot id according to the post  otherwise it wont work
         * look at the post controller id*/ 
        $candidacy['post_id']         =$request['post_id']; 
        // $candidacy['post_id']         =$request['nrna_id']; 
        $candidacy['proposer_id']      =$request['proposer_id'];  
        $candidacy['proposer_name']   =$request['proposer_name']; 
        $candidacy['supporter_id']    =$request['supporter_id'];   
        $candidacy['supporter_name']   =$request['supporter_name'];  
        $candidacy['image_path_1']     =$filename ;
        // $candidacy['image_path_2']     ="test";
        // $candidacy['image_path_3']     ="test";
        // $candidacy['image_path_1']      =$request->input('image') ? $request->file('image')->store('images', 'public'): null;
        //save 
        // dd($candidacy);
        //    auth()->user()->candidacy()->create($candidacy); 
         $this->upload($uploadedFile);    
        $candidacy->save();
            // auth()->user()->files()->create([ 
            //     'title' => "file1",
            //     'overview' =>"description1",
            //  ]);
            // dd($candidacy);
            // $this->upload($uploadedFile);  
            // Candidacy::create($candidacy);  
         return redirect('/candidacies/index');       
        // return redirect()->Inertia::render(('Candidacy/CreateCandidacy'); 
        //   return Inertia::render('Candidacy/IndexCandidacy', [
        //   'candidacies' => $candidacies,
        //   'filters' =>request()->all(['search', 'field','direction'])   
        // ]);
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function show(Candidacy $candidacy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidacy $candidacy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidacy $candidacy)
    {
        //
        $startName  ="csv_files/global_candidacy.csv";
        var_dump($startName);
        //return 0;
        $csvName  =storage_path($startName); 

        // $file = fopen(csvName,"r");
        // $csv = array_map('str_getcsv', file($csvName));
        $csv_array = $this->csv_to_array($csvName,",");
    //here starts 
    
  
     

    //  here ends     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidacy $candidacy)
    {
        //
    }
    //
    public function upload($uploadedFile)
    {
        //
        // $uploadedFile = $request->file($fileKey);
        if($uploadedFile){
            $filename = time().$uploadedFile->getClientOriginalName();
            Storage::disk('local')->putFileAs(
            'upload_files/'.$filename, 
                $uploadedFile, 
                $filename
            );
            $upload = new Upload;
            $upload->filename = $filename;
            $upload->user_id=1;
            // $upload->user()->associate(auth()->user());
            $upload->save();
        }
    }
    public function validate_input($myArray){ 
    //      $validator = Validator::make($myArray, 
    //      [ 
    //        'user_id' => 'unique:candidacies,user_id',
    //    ]);
        
    //    if ($validator->fails()) {
    //         Session::flash('error', $validator->messages()->first());
    //         return redirect()->back()->withInput();
    //    }
    }
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidacy  $candidacy
     * @return \Illuminate\Http\Response
     */
    public function assign()
    {
        //
        $startName  ="csv_files/global_candidacy.csv";
        var_dump($startName);
        //return 0;
        $csvName  =storage_path($startName); 

        // $file = fopen(csvName,"r");
        // $csv = array_map('str_getcsv', file($csvName));
        $csv_array = csv_to_array($csvName,",");
    //here starts 
    
    /******************************* */ 
         //$user = DB::table('users')->find(3);
         $candis = Candidacy::all();
         $laufer =0;
        // dd($csv_array);
        foreach($csv_array as $element){
            /**
            * each row is a user . So we need to create a user 
            *@user : new USER  
            */
            //first check if user already exists
            $candi  =$candis->where('user_id', $element['user_id'])->first();
            // dd($cur_candi);
            $laufer +=1;
            if($candi){
                echo "<p>\n Candis Exists-> line: ". $laufer. ", user_id:". $element['user_id'] ."</p>\n";
   
            }else{

                // dd($element);    
                $candi = new Candidacy;
            } 
            {    //User_id
                 if (array_key_exists('user_id', $element))
                 {
                     if($element['user_id']){
                        $candi->user_id = $element['user_id'];  
                     }else{
                         dd("Problem with adding the line user_id");
                     }

                 }    
                //candidacyid 
                if (array_key_exists('candidacy_id', $element))
                 {
                     if($element['candidacy_id']){
                        $candi->candidacy_id = $element['candidacy_id'];  
                     }else{
                         dd("Problem with adding the line candidacy_id");
                     }

                 }  
                //candi name  
                if (array_key_exists('candidacy_name', $element))
                 {
                     if($element['candidacy_name']){
                        $candi->candidacy_name = $element['candidacy_name'];  
                     }else{
                         dd("Problem with adding the line candidacy_name");
                     }

                 }  
               //candi name  
               if (array_key_exists('proposer_name', $element))
               {
                   if($element['proposer_name']){
                      $candi->proposer_name = $element['proposer_name'];  
                   }else{
                       dd("Problem with adding the line proposer_name");
                   }

               }  
                //candi name  
                if (array_key_exists('proposer_id', $element))
                {
                    if($element['proposer_id']){
                    $candi->proposer_id = $element['proposer_id'];  
                    }else{
                        dd("Problem with adding the line proposer_id");
                    }

                }  
                    //candi name  
                if (array_key_exists('supporter_id', $element))
                    {
                        if($element['supporter_id']){
                            $candi->supporter_id = $element['supporter_id'];  
                        }else{
                            dd("Problem with adding the line supporter_id");
                        }

                    }  
                //
                    //candi name  
                    if (array_key_exists('supporter_name', $element))
                    {
                        if($element['supporter_name']){
                            $candi->supporter_name = $element['supporter_name'];  
                        }else{
                            dd("Problem with adding the line supporter_name");
                        }

                    }  
                //   //candi name  
                if (array_key_exists('post_name', $element))
                {
                    if($element['post_name']){
                        $candi->post_name = $element['post_name'];  
                    }else{
                        dd("Problem with adding the line post_name");
                    }

                }
        
                // $candi->post_nepali_name="-";
                    //   //candi name  
                if (array_key_exists('post_id', $element))
                {
                    if($element['post_id']){
                        $candi->post_id = $element['post_id'];  
                    }else{
                        dd("Problem with adding the line post_id");
                    }

                }
                
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
     

    //  here ends     
    }

    //ends here
}
