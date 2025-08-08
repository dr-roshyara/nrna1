use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = new User;
$user->name = "Manag Mustang";
$user->user_id="nrna_1110"
$user->email = "manang@example.com";
$user->password = Hash::make('password'); // Always hash passwords
$user->first_name = "Manag";
$user->last_name = "Mustang";
$user->nrna_id = "DE_TEST_2025_01";
$user->is_voter = 0;
// Add other required user fields here...
$user->save();
#
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_1110";
$candi->candidacy_id = "DE_TEST_2025_123";
$candi->post_id = "2025_02";
$candi->proposer_id = "DE10000216"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000216"; // Link to the supporter's nrna_id
$candi->image_path_1 = "manag_mustang.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#

#send candidates 
#************************************

#************************************
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create the user who is the candidate
$candidateUser = new User;
$candidateUser->name = "Kathmandu";
$candidateUser->user_id =2
$candidateUser->first_name = "Kathmandu";
$candidateUser->last_name = "City";
$candidateUser->email = "kathmandu@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->id = 2900; // Assuming you can set the ID, otherwise it's auto-incremented
// Add other required fields...
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = 2; // Link to the candidate's user ID
$candi->candidacy_id = "DE_TEST_2025_02";
$candi->post_id = "2025_02";
$candi->proposer_id = "DE10000217"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000218"; // Link to the supporter's nrna_id
$candi->image_path_1 = "kathmandu.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#
#************************************
#************************************
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create the user who is the candidate
$candidateUser = new User;
$candidateUser->name = "Pokhara";
$candidateUser->user_id =3
$candidateUser->first_name = "Pokhara";
$candidateUser->last_name = "City";
$candidateUser->email = "pokhara@example.com";
$candidateUser->password = Hash::make('password');
// Add other required fields...
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = 3; // Link to the candidate's user ID
$candi->candidacy_id = "DE_TEST_2025_03";
$candi->post_id = "2025_02";
$candi->proposer_id = "DE10000218"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000219"; // Link to the supporter's nrna_id
$candi->image_path_1 = "pokhara.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();


#************************************
#************************************
#************************************
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create the user who is the candidate
$candidateUser = new User;
$candidateUser->name = "Dadelhura";
$candidateUser->user_id =4
$candidateUser->first_name = "Dadeldhura";
$candidateUser->last_name = "City";
$candidateUser->email = "dadeldhura@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = 4; // Link to the candidate's user ID
$candi->candidacy_id = "DE_TEST_2025_04";
$candi->post_id = "2025_02";
$candi->proposer_id = "DE10000219"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000229"; // Link to the supporter's nrna_id
$candi->image_path_1 = "dadelhura.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#################################################
#######################################################################################################
// Create the user who is the candidate
$candidateUser = new User;
$candidateUser->name = "Ani Choying Drolma";
$candidateUser->user_id ="nrna_205"
$candidateUser->first_name = "Ani Choying";
$candidateUser->last_name = " Drolma";
$candidateUser->email = "cn@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_205";
$candi->candidacy_id = "DE_TEST_2025_205";
$candi->post_id = "2025_06";
$candi->proposer_id = "DE1000021"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000222"; // Link to the supporter's nrna_id
$candi->image_path_1 = "anidhoing.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#

#************************************************
#************************************************
$candidateUser = new User;
$candidateUser->name = "Khaptad National Park";
$candidateUser->user_id =5
$candidateUser->first_name = "Khaptad";
$candidateUser->last_name = "National Park";
$candidateUser->email = "khaptad@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = 5; // Link to the candidate's user ID
$candi->candidacy_id = "DE_TEST_2025_06";
$candi->post_id = "2025_06";
$candi->proposer_id = "DE1000022"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE100023"; // Link to the supporter's nrna_id
$candi->image_path_1 = "chitawan.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#

#************************************************
#************************************************
#************************************************
$candidateUser = new User;
$candidateUser->name = "Bardia National Park";
$candidateUser->user_id =7
$candidateUser->first_name = "Bardia";
$candidateUser->last_name = "National Park";
$candidateUser->email = "bardia@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = 7; // Link to the candidate's user ID
$candi->candidacy_id = "DE_TEST_2025_07";
$candi->post_id = "2025_06";
$candi->proposer_id = "DE128"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE129"; // Link to the supporter's nrna_id
$candi->image_path_1 = "bardia.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#
#************************************************


Use App\Models\Candidacy; 
$candi                  =new Candidacy
$candi->user_id         = 2918
$candi->candidacy_id    ="Nepal_20";
$candi->candidacy_name  ="Bardiya National Park";
$candi->proposer_name   ="Radha Sigdel"
$candi->proposer_id     ="DE10000001"
$candi->supporter_name  ="Dhurba Sharma"
$candi->supporter_id    ="DE10000245"
$candi->post_name        ="General Secretary"
$candi->post_nepali_name  ="माहासचिव"

$candi->Post_id          ="2021_05"
$candi->image_path_1     ="bardiaya"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()
#
#************************************************
Use App\Models\Candidacy; 
$candi                  =new Candidacy
$candi->user_id         = 2919
$candi->candidacy_id    ="Nepal_21";
$candi->candidacy_name  ="Shivapuri Nagarjun National Park";
$candi->proposer_name   ="Radha Sigdel"
$candi->proposer_id     ="DE10000001"
$candi->supporter_name  ="Dhurba Sharma"
$candi->supporter_id    ="DE10000245"
$candi->post_name        ="General Secretary"
$candi->post_nepali_name  ="माहासचिव"

$candi->Post_id          ="2021_05"
$candi->image_path_1     ="shivapuri"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()
#

#secretary 
#************************************************
Use App\Models\Candidacy; 
$candi                  =new Candidacy
$candi->user_id         = 2920
$candi->candidacy_id    ="Nepal_22";
$candi->candidacy_name  ="Chandragiri Hill";
$candi->proposer_name   ="Radha Sigdel"
$candi->proposer_id     ="DE10000001"
$candi->supporter_name  ="Dhurba Sharma"
$candi->supporter_id    ="DE10000245"
$candi->post_name        ="General Secretary"
$candi->post_nepali_name  ="माहासचिव"

$candi->Post_id          ="2021_06"
$candi->image_path_1     ="chandragiri"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()
#


