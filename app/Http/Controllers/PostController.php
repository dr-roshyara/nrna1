<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use App\Models\Post;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
class PostController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%");
                // ->orWhere('email', 'LIKE', "%{$value}%");
            });
        });
        $posts = QueryBuilder::for(Post::class)
            ->defaultSort('post_id')
            ->allowedSorts(['name','post_id', 'is_national_wide', 'required_number'])
            ->allowedFilters(['post_id', 'name', 'is_national_wide', 'required_number', $globalSearch])
            ->paginate(100)
            ->withQueryString();
            //
            return Inertia::render('Post/IndexPost', [
                'posts' => $posts,
            ])->table(function (InertiaTable $table) {
                $table->addSearchRows([
                    'name'      => 'Name',
                    'post_id'  => 'Post ID',
                ])->addFilter('name', 'Name', [
                    // 'en' => 'Engels',
                    // 'nl' => 'Nederlands',
                ])->addColumns([
                    'sn'                => 'S.N.',
                    'post_id'            => 'Post ID',
                    'name'              => 'Name',
                    'required_number'   => "Required Number",
                    'state_name'        => "Scope"
                ]);
            });
           
     


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
     /***
      * 
      *Cerate mass assignment 
      */
      public function  assign(){
        // here stats the assgin function 
         $startName  ="csv_files//global_posts.csv";
        
         //return 0;
        $csvName  =storage_path($startName); 
        // var_dump($csvName);
      
        $csv_array = csv_to_array($csvName,",");
        //read users 
        // dd($csv_array);
        $posts = Post::all();  
        $laufer =0;
        // dd($csv_array);
        foreach($csv_array as $element){
            /**
            *each row is a post . So we need to create a post 
            *@post : new  post 
            */
            // dd($element);
            $laufer +=1;
            $post  =Post::where('post_id', trim($element['post_id']))->first();
            if($post){
                  
                echo "Post Exists-> line: ".$laufer.", post name ". $post->name. ", post id: ". $post->post_id ."<br>\n";

            }else{
                /***
                 * 
                 * create new user here 
                 * 
                 */
                $post                     = new Post; 
                   
                echo  $element ['post_id'].'<br/>'; 
            }
                $post->name               =$element ['name'];
                $post->post_id            =$element['post_id']; 
                $post->nepali_name        =$element['nepali_name'];
                $post->required_number    =$element['required_number'];
                $post->is_national_wide   =$element['is_national_wide']; 
        
            if (array_key_exists('state_name', $element))
                 {
                     if($element['state_name']){
                        $post->state_name = $element['state_name'];  
                     }

                 }    
           
            // dd($post);
            $post->save();
        }    
       

 

        //here ends the assign function . 
      }
}
