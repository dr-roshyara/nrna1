● I can set roshyara@gmail.com as super_admin using Laravel Tinker (REPL). Here's how:

● Bash(cd C:/Users/nabra/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu && php artisan tinker << 'EOF'
      $user = \App\Models\User::where('email', 'roshyara@gmail.com')->first();
      if ($user) {
          $user->update(['is_super_admin' => true]);
          echo "✓ Set {$user->email} as super_admin\n";
          echo "  - is_super_admin: {$user->is_super_admin}\n";
          echo "  - isPlatformAdmin(): " . ($user->isPlatformAdmin() ? 'true' : 'false') . "\n";
          echo "  - isSuperAdmin(): " . ($user->isSuperAdmin() ? 'true' : 'false') . "\n";
      } else {
          echo "✗ User with email roshyara@gmail.com not found\n";
      }
      EOF
      )
  ⎿     ✓ Set roshyara@gmail.com as super_admin
       - is_super_admin:
       - isPlatformAdmin(): false
       - isSuperAdmin(): false

● Bash(cd C:/Users/nabra/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu && php artisan tinker << 'EOF'
      $user = \App\Models\User::where('email', 'roshyara@gmail.com')->first();
      if ($user) {
          echo "User: {$user->email}\n";
          echo "Before: is_super_admin = " . ($user->is_super_admin ? '1' : '0') . "\n";
          $user->is_super_admin = true;
          $user->save();
          $user->refresh();
          echo "After:  is_super_admin = " . ($user->is_super_admin ? '1' : '0') . "\n";
          echo "isPlatformAdmin: " . ($user->isPlatformAdmin() ? 'YES' : 'NO') . "\n";
          echo "isSuperAdmin: " . ($user->isSuperAdmin() ? 'YES' : 'NO') . "\n";
      } else {
          echo "User not found\n";
      }
      EOF
      )