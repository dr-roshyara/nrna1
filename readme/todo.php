todo 
public function startAuthorization(Request $request)
{
    if (!$request->user()->hasRole(['election-committee', 'super-admin'])) {
        abort(403);
    }
    
    $election = Election::current();
    $election->startAuthorization();
    
    return response()->json([
        'message' => 'Authorization process started',
        'required_authorizers' => $election->required_authorizers,
        'deadline' => $election->authorization_deadline,
    ]);
}
