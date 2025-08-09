#post
####################################
use App\Models\Post;
        $post =new Post
        $post->post_id ="2025_02"
        $post->name ="President"
        $post->nepali_name ="अद्यक्ष" 
        $post->required_number =1
        $post->save()
#   
   use App\Models\Post;
        $post =new Post
        $post->post_id ="2025_06"
        $post->required_number =1
        $post->is_national_wide =1
        $post->name ="General Secretary"
        $post->nepali_name ="महासचिव"
        $post->save()

###################################
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
$candi =                new Candidacy;
$candi->user_id         = "nrna_1110";
$candi->candidacy_id    = "DE_2025_1";
$candi->post_id         = "2025_02"; // president 
$candi->proposer_id     = "DE10216"; // Link to the proposer's nrna_id
$candi->supporter_id    = "DE10000216"; // Link to the supporter's nrna_id
$candi->image_path_1    = "manang.jpg";
$candi->image_path_2    = "-";
$candi->image_path_3    = "-";
$candi->save();

#

#######################################################################################################
// Create the user who is the candidate
$candidateUser = new User;
$candidateUser->name = "Ani Choying Drolma";
$candidateUser->user_id ="nrna_2050"
$candidateUser->first_name = "Ani Choying";
$candidateUser->last_name = " Drolma";
$candidateUser->email = "cn@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_46";
$candi->candidacy_id = "DE_TEST_2025_205";
$candi->post_id = "2025_06";
$candi->proposer_id = "DE100121"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE100122"; // Link to the supporter's nrna_id
$candi->image_path_1 = "anidhoing.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#######################
// Create the user who is the candidate
$candidateUser = new User;
$candidateUser->name = "Himani Shaha";
$candidateUser->user_id ="nrna_46"
$candidateUser->first_name = "Himani";
$candidateUser->last_name = "Shaha";
$candidateUser->email = "cn1@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_46";
$candi->candidacy_id = "DE_2025_01";
$candi->post_id = "2025_06";
$candi->proposer_id = "DE10122"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE100124"; // Link to the supporter's nrna_id
$candi->image_path_1 = "himani.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#
#####################################

// Create the user who is the candidate
$candidateUser = new User;
$candidateUser->name = "Manisha Koirala";
$candidateUser->user_id ="nrna_77"
$candidateUser->first_name = "Manisha";
$candidateUser->last_name = "Koirala";
$candidateUser->email = "cn2@example.com";
$candidateUser->password = Hash::make('password');
$candidateUser->save();

##
use App\Models\Candidacy;
$candi = new Candidacy;
$candi->user_id = "nrna_77";
$candi->candidacy_id = "DE_2025_03";
$candi->post_id = "2025_06";
$candi->proposer_id = "DE1230122"; // Link to the proposer's nrna_id
$candi->supporter_id = "DE1124"; // Link to the supporter's nrna_id
$candi->image_path_1 = "manisha.jpg";
$candi->image_path_2 = "-";
$candi->image_path_3 = "-";
$candi->save();
#

nrna_77