use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = new User;
$user->name = "Manag Mustang";
$user->email = "manag@example.com";
$user->password = Hash::make('password'); // Always hash passwords
$user->first_name = "Manag";
$user->last_name = "Mustang";
$user->nrna_id = "DE_TEST_2025_01";
$user->is_voter = 1;
// Add other required user fields here...
$user->save();
#
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = 2; // Link to the candidate's user ID
$candi->candidacy_id = "DE_TEST_2025_01";
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

#
Use App\Models\Candidacy; 
$candi =new Candidacy
$candi->user_id         =2
$candi->candidacy_id    ="DE_TEST_02";
$candi->candidacy_name  ="Kathmandu City";
$candi->proposer_name   ="Bhima Nani Shrestha"
$candi->proposer_id     ="DE11000011"
$candi->supporter_name  ="Krishna Prasad Gaire"
$candi->supporter_id    ="DE08000016"
$candi->post_name        ="President"
$candi->post_nepali_name  ="अद्यक्ष"
$candi->Post_id          ="2025_02"
$candi->image_path_1     ="Kathmandu.jpg"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()
#
#************************************

Use App\Models\Candidacy; 
$candi =new Candidacy
$candi->user_id         =2901 
$candi->candidacy_id    ="DE_TEST_03";
$candi->candidacy_name  ="Pokhara";
$candi->proposer_name   ="Bhima Nani Shrestha"
$candi->proposer_id     ="DE11000011"
$candi->supporter_name  ="Krishna Prasad Gaire"
$candi->supporter_id    ="DE08000016"
$candi->post_name        ="President"
$candi->post_nepali_name  ="अद्यक्ष"
$candi->Post_id          ="2025_02"
$candi->image_path_1     ="pokhara.jpg"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()


Use App\Models\Candidacy; 
$candi                  =new Candidacy
$candi->user_id         =2902
$candi->candidacy_id    ="DE_TEST_04";
$candi->candidacy_name  ="Humla Simikot ";
$candi->proposer_name   ="Radha Sigdel"
$candi->proposer_id     ="	DE10000001"
$candi->supporter_name  ="Dhurba Sharma"
$candi->supporter_id    ="DE10000245"
$candi->post_name        ="President"
$candi->post_nepali_name  ="अद्यक्ष"

$candi->Post_id          ="2025_02"
$candi->image_path_1     ="simikot"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()

#
Use App\Models\Candidacy; 
$candi                  =new Candidacy
$candi->user_id         =2905
$candi->candidacy_id    ="DE_TEST_05";
$candi->candidacy_name  ="Janakpur City ";
$candi->proposer_name   ="Radha Sigdel"
$candi->proposer_id     ="	DE10000001"
$candi->supporter_name  ="Dhurba Sharma"
$candi->supporter_id    ="DE10000245"
$candi->post_name        ="President"
$candi->post_nepali_name  ="अद्यक्ष"

$candi->Post_id          ="2021_02"
$candi->image_path_1     ="janakpur"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()

Use App\Models\Candidacy; 
$candi                  =new Candidacy
$candi->user_id         =2905
$candi->candidacy_id    ="DE_TEST_06";
$candi->candidacy_name  ="Dadeldhura City ";
$candi->proposer_name   ="Radha Sigdel"
$candi->proposer_id     ="	DE10000001"
$candi->supporter_name  ="Dhurba Sharma"
$candi->supporter_id    ="DE10000245"
$candi->post_name        ="President"
$candi->post_nepali_name  ="अद्यक्ष"

$candi->Post_id          ="2025_02"
$candi->image_path_1     ="janakpur"
$candi->image_path_2     ="-"
$candi->image_path_3     ="-"
$candi->save()
