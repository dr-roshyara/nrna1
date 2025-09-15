use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = new User;
$user->name = "Sudhila Karki";
$user->user_id="nrna_10"
$user->email = "sushila@example.com";
$user->password = Hash::make('password'); // Always hash passwords
$user->first_name = "Sushila";
$user->last_name = "Karki";
$user->nrna_id = "TEST_2025_10";
$user->is_voter = 0;
// Add other required user fields here...
$user->save();
###########################
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_10";
$candi->candidacy_id = "TEST_2025_10";
$candi->post_id = "2025_02";
$candi->proposer_id = "DE10216"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000216"; // Link to the supporter's nrna_id
$candi->image_path_1 = "sushila.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#
$user = new User;
$user->name = "Pasang Lamu Sherpa";
$user->user_id="nrna_11"
$user->email = "pasang@example.com";
$user->password = Hash::make('password'); // Always hash passwords
$user->first_name = "Pasang Lamu";
$user->last_name = "Sherpa";
$user->nrna_id = "TEST_2025_11";
$user->is_voter = 0;

// Add other required user fields here...
$user->save();
###########################
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_11";
$candi->candidacy_id = "TEST_2025_11";
$candi->post_id = "2025_02";
$candi->proposer_id = "DE10217"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000217"; // Link to the supporter's nrna_id
$candi->image_path_1 = "pasang.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#
########################################
$user = new User;
$user->name = "Himani Shah";
$user->user_id="nrna_12"
$user->email = "himani@example.com";
$user->password = Hash::make('password'); // Always hash passwords
$user->first_name = "Himani";
$user->last_name = "Shah";
$user->nrna_id = "TEST_2025_12";
$user->is_voter = 0;

// Add other required user fields here...
$user->save();
###########################
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_12";
$candi->candidacy_id = "TEST_2025_12";
$candi->post_id = "2025_02";
$candi->proposer_id = "DE10218"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE1000018"; // Link to the supporter's nrna_id
$candi->image_path_1 = "himani.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
################################################


#############
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
$candi->proposer_id = "DE10216"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE10000216"; // Link to the supporter's nrna_id
$candi->image_path_1 = "manag.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#

#send candidates
#************************************
########################################
$user = new User;
$user->name = "Kathmandu City";
$user->user_id="nrna_13"
$user->email = "kathmandu@example.com";
$user->password = Hash::make('password'); // Always hash passwords
$user->first_name = "Kathmandu";
$user->last_name = "City";
$user->nrna_id = "TEST_2025_13";
$user->is_voter = 0;
$user->save()


############# 


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
