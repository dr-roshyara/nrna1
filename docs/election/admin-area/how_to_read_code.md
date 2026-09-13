  $id = App\Models\Election::withoutGlobalScopes()->where('slug','onf-europe-1-750761df')->first()->id;
  $codes = App\Models\Code::withoutGlobalScopes()->where('election_id', $id)->get(['user_id','has_voted','code_to_open_voting_form'])
App\Models\Code::withoutGlobalScopes()->where('election_id', $id)->forceDelete();