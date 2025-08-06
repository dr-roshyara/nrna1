<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Result;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
class ResultController extends Controller
{
    //
       
    public function index(){
          
      $posts        = Post::get(['id','post_id','name','state_name','required_number']) ;
      $candidates   =[]; 
      // Check if results should be published
        $electionCompleted = false; /* your logic to check if election is completed */;
        
        if (!$electionCompleted) {
            return redirect()->back()->with('error', 
                'Election results will be available after the election is completed.'
            );
        }

    //   $results   =Result::query();
        $results    = DB::table('results')
                    ->selectRaw(' count(*) as total ');

          
        $results =$results->selectRaw('COUNT(DISTINCT vote_id) as total_votes');
        // dd($results->get());
        //       SELECT 
        //       SUM(IF(name = ?, 1, 0)) AS name_count,
        //       SUM(IF(address = ? AND port = ?, 1, 0)) AS addr_count
        //   FROM 
        //       table_nam
    // Here I used his suggestion: 
    // https://reinink.ca/articles/calculating-totals-in-laravel-using-conditional-aggregates
    foreach($posts as $post){
        //  dd($post);
        // $_expr ="SUM( IF(post_id='"; 
        // $_expr .= $post->post_id."', 1, 0) )  as ".$post->post_id;
        // $_expr  =  'COUNT(DISTINCT vote_id)* '. $post->required_number;
        // $_expr  .="  as ".$post->post_id;
        // dd($_expr);
        // $results =$results->selectRaw($_expr ); 
        //expressioin for candidates 
        $post_candidates =$post->candidates;
        
       
        foreach ($post_candidates as $candidate){
            $_candi_condition ="post_id = '";
            $_candi_condition .=$post->post_id."'";
            $_candi_condition .= " AND candidacy_id = '";
            $_candi_condition .= $candidate->candidacy_id;
            $_candi_condition .= "' ";
            // dd($_candi_condition);             
             $_candi_expr = "SUM( IF( ". $_candi_condition;
             
             $_candi_expr .= ", 1, 0)) as ";
             $_candi_expr .= $post->post_id."_and_".$candidate->candidacy_id;
            //  dd($_candi_expr);
              
            $results =$results->selectRaw($_candi_expr); 
         }

         //load users in candidates 
           $post_candidates->load(['user'=>function($query){
             return  $query->select('name', 'user_id');
           }]);
           $candidates =array_merge($candidates,(array)$post_candidates);
        
        
    }
    //  dd($results->get());
    
      $final_result =$results->get();
        // dd($final_result);
     return Inertia::render('Result/Index', [
            'final_result' =>$final_result,
            'posts'=> $posts,
            'candidates'=>$candidates  
        ]);
    }
}
