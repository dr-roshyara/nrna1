<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function index(Request $request)
    {
        request()->validate([
            'direction'=> ['in:asc,desc'],
            'field' => ['in:id,from,to,created_at']
        ]);
        $query =Message::query();
        // if(request('direction')){
        //     $query->orderBy('id',request('direction'));

        // }else{
        //     $query->orderBy('id','desc');

        // }
        
        if(request('search')){
            $query->where('to', 'LIKE', '%'.request('search').'%');
        } 
        if(request()->has(['field', 'direction'])){
            $query->orderBy(request('field'), request('direction')); 
        }else{
            $query->orderBy('id','desc');
        }
        //the following lines are for the first type of search 

        // $messages =Message::when( $request->term, 
        //     function($query, $term){
        //     $query->where('to', 'LIKE', '%'.$term.'%' );
        // })->paginate(20); 
        
         $messages =$query->paginate(20);
        // $messages =$messages->sortBy('created_at')->reverse();
        return Inertia::render('Message/Index', [
          'messages' => $messages,
          'filters' =>request()->all(['search', 'field','direction'])

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
 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request()->validate([
                'from' => ['required', 'max:50'], 
                'to' => ['required', 'max:50'],
                'message' => ['required', 'max:255'],
           
        ]);
        Message::create(
            $request()->validate([
                'from' => ['required', 'max:50'], 
                'to' => ['required', 'max:50'],
                'message' => ['required', 'max:255'],
            ])
        );
        return  redirect()->route('messages.index');
        //  return redirect('/messages/index')->with('from', 'to', 'message');
        // return Redirect::route('Message.Index');
        /**
         * 
         * 
         */
        //    User::create(
        //     Request::validate([
        //         'first_name' => ['required', 'max:50'],
        //         'last_name' => ['required', 'max:50'],
        //         'email' => ['required', 'max:50', 'email'],
        //     ])
        // );

        // return Redirect::route('users.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
