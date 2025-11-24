- Check if the mysql credentials are correct . 
    - php artisan migrate 
    - php artisan serve 
    - How to check if all migration get success  
     php artisan migrate:status 
    - How to migrate only a particular : 
    # 1. Clear caches
    php artisan config:clear
    php artisan cache:clear

    # 2. Run voter_slugs migrations specifically
    php artisan migrate --path=database/migrations/2025_09_13_210629_create_voter_slugs_table.php

    # 3. If successful, run the step columns migration
    php artisan migrate --path=database/migrations/2025_09_13_212143_add_step_columns_to_voter_slugs_table.php

    # 4. Test if the page works now

- register the name with email 
- verify the email address 
- You give the committee member right with following process 
- start php artisan tinker 
nabra@LAPTOP-5874DSDS MINGW64 ~/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu (election)
$ php artisan tinker
 Psy Shell v0.12.9 (PHP 8.3.24 — cli) by Justin Hileman
> User::find(1)
[!] Aliasing 'User' to 'App\Models\User' for this Tinker session.
= App\Models\User {#5792
    id: 1,
    user_id: "nab-raj-roshyara",
    facebook_id: null,
    name: "Nab Raj Roshyara",
    email: "roshyara@gmail.com",
    email_verified_at: "2025-11-24 16:39:39",
    #password: "$2y$10$G6ctODcwRNaI5emP2vvDW.Omrlbk.u65wqjkqoGxGX597x2zknG96",
    #two_factor_secret: null,
    #two_factor_recovery_codes: null,
    #remember_token: null,
    voting_ip: null,
    current_team_id: null,
    profile_photo_path: null,
    profile_bg_photo_path: null,
    profile_icon_photo_path: null,
    created_at: "2025-11-24 16:38:57",
    updated_at: "2025-11-24 16:39:41",
    first_name: "Nab Raj",
    middle_name: null,
    last_name: "Roshyara",
    gender: null,
    region: "Europe",
    country: null,
    state: null,
    street: null,
    housenumber: null,
    postalcode: null,
    city: null,
    additional_address: null,
    nrna_id: null,
    telephone: null,
    is_voter: 0,
    name_prefex: null,
    can_vote: 0,
    approvedBy: null,
    suspendedBy: null,
    suspended_at: null,
    has_voted: 0,
    has_candidacy: 0,
    lcc: null,
    designation: null,
    google_id: null,
    social_id: null,
    social_type: null,
    is_committee_member: 0,
    committee_name: null,
    user_ip: "127.0.0.1",
    +profile_photo_url: "https://ui-avatars.com/api/?name=N+R+R&color=7F9CF5&background=EBF4FF",
  }

> $user->is_voter=1;
= 1

> $user->save()
= true 

> $user->is_committee_member=1;
= 1

> $user->save()
= true
# now  Check if roles and permissions are already created: 
    nabra@LAPTOP-5874DSDS MINGW64 ~/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu (election)
    $ php artisan tinker
    Psy Shell v0.12.9 (PHP 8.3.24 — cli) by Justin Hileman
    > // 1. Import the Permission model
    > use Spatie\Permission\Models\Permission;
    > // 2. Get all records from the permissions table
    > $permissions = Permission::all();
    = Illuminate\Database\Eloquent\Collection {#6428
        all: [],
    }
    > // 3. Display the results
    > $permissions->pluck('name'); // Shows a cleaner list of just the permission names
    > $permissions = Permission::all();
    = Illuminate\Database\Eloquent\Collection {#6413
        all: [],
    }

#################################
if seeder not created  then create it . 
nabra@LAPTOP-5874DSDS MINGW64 ~/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu (election)
$ php artisan make:seeder RolesAndPermissionsSeeder
 Seeder created successfully.

nabra@LAPTOP-5874DSDS MINGW64 ~/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu (election)
$
#############################################################
# How to give permission to election committee : 

## Step 1: Seed Election Permissions

Before publishing results for the first time, you need to set up the permission system:

```bash
php artisan db:seed --class=ElectionPermissionSeeder
php artisan db:seed --class=ElectionPermissionsSeeder
```

This command creates:
- **Permissions**: `publish-election-results`, `view-election-results`, `manage-election-settings`, etc.
- **Role**: `election-committee` with all election permissions
- **Database tables**: Required permission structures

## Step 2: Assign Committee Role

Assign the `election-committee` role to appropriate users:

```bash
# Via Laravel Tinker
php artisan tinker

# In Tinker console:
$user = App\Models\User::find(USER_ID);
$user->assignRole('election-committee');
```
Or via the application's admin interface if available.
after this permission 
## Overview | अवलोकन
The Election Management system provides authorized administrators with tools to monitor, control, and manage election processes. 
This comprehensive dashboard is accessible at 

`http://localhost:8000/election/management`.

######################################################################################
Test if permissions are working:
$user = App\Models\User::find(1);

// Check individual permissions
$user->can('manage-election-settings'); // Should return true/false
$user->can('view-election-results');    // Should return true/false
$user->can('publish-election-results'); // Should return true/false

// Check multiple permissions
$user->hasAnyPermission(['manage-election-settings', 'view-election-results']);

// Check all permissions
$user->hasAllPermissions(['manage-election-settings', 'view-election-results']);

## Assign to your user through tinker:
bash
php artisan tinker
>>> $user = App\Models\User::first();
>>> $user->givePermissionTo('manage-election-settings');
>>> $user->givePermissionTo('view-election-results'); 
>>> $user->givePermissionTo('publish-election-results');
# How to start election : 
# Everything for the election commission : 
     http://localhost:8000/election/viewboard

##    who can see the view board: 
    // Election Viewboard Routes (View Rights Only)
    Route::middleware(['auth:sanctum', 'verified', 'can:view-election-results'])->group(function () {
    Route::get('/election/viewboard', [ElectionManagementController::class, 'viewboard'])->name('election.viewboard');
    });

# who can do  Election Result Management (Committee Only)
Route::middleware(['auth:sanctum', 'verified', 'can:publish-election-results'])->group(function () {
    Route::post('/election/publish-results', [ElectionManagementController::class, 'publishResults'])->name('election.publish');
    Route::post('/election/unpublish-results', [ElectionManagementController::class, 'unpublishResults'])->name('election.unpublish');
});

// Election Voting Period Control (Committee Only)
Route::middleware(['auth:sanctum', 'verified', 'can:manage-election-settings'])->group(function () {
    Route::post('/election/start-voting', [ElectionManagementController::class, 'startVoting'])->name('election.start-voting');
    Route::post('/election/end-voting', [ElectionManagementController::class, 'endVoting'])->name('election.end-voting');
});

