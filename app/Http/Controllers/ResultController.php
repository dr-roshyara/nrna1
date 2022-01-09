<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
class ResultController extends Controller
{
    //
       
    public function index(){
         $votes =Vote::all();
        //  $votes =DB::table('users')->get(); 
         dd($votes);

        $globalSearch = AllowedFilter::callback('global', 
        function ($query, $value) {
            $query->where(function ($query) use ($value) {
                // $query->where('candidacy_id', 'LIKE', "%{$value}%");
                // $query->where('itemId', "{$value}");
                // ->orWhere('warehouseId', 'LIKE', "%{$value}%");
            });
        });
        
        // $candidacies = QueryBuilder::for(Candidacy::class)
        // $posts = QueryBuilder::for(Post::with('candidates'))
        $votes = QueryBuilder::for(Vote::class)
        // ->defaultSort('candidac')
        // ->allowedSorts(['post_id'])            
        ->paginate(1) 
        ->withQueryString();

        return Inertia::render('Vote/Result', [
            'votes' =>$votes
        ]);
    }
}
