 Bash(php artisan tinker --execute="
  \$e = App\Models\Election::withoutGlobalScopes()->where('slug','testing-1-0c715071')->first();
  \$codes = App\Models\Code::withoutGlobalScopes()->where('election_id', \$e->id)->get();
  foreach (\$codes as \$c) {
      \$u = App\Models\User::find(\$c->user_id);
      echo '--- code id ' . \$c->id . ' ---' . PHP_EOL;
      echo 'user: ' . (\$u?->email ?? 'unknown') . PHP_EOL;
      echo 'code_to_open_voting_form: ' . \$c->code_to_open_voting_form . PHP_EOL;
      echo 'usable: ' . var_export(\$c->is_code_to_open_voting_form_usable, true) . PHP_EOL;
      echo 'has_voted: ' . var_export(\$c->has_voted, true) . PHP_EOL;
      echo 'vote_submitted: ' . var_export(\$c->vote_submitted, true) . PHP_EOL;
      echo PHP_EOL;
  }
  ")
   --- code id a2bad6ba-096e-41c7-83a8-3bbcc6af9dc7 ---
user: restaurant.namastenepal@gmail.com
code_to_open_voting_form: PAYZN72W
usable: true
has_voted: false
vote_submitted: false

--- code id a2bad0ee-361e-4239-86fc-bfef73881029 ---
user: roshyara@gmail.com
code_to_open_voting_form: V2NYZR69
usable: true
has_voted: false
vote_submitted: false
