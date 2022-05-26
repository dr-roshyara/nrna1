#add the candidates
- You can through the site : /candidacy/update
   using update method of CandidacyController.php
#make notification code
   php artisan make:notification SendFirstVerificationCode --markdown mail.send_first_verification_code
   php artisan make:notification SecondVerificationCode --markdown mail.send_second_verification_code
   php artisan make:notification SendVoteSavingCode --markdown mail.send_vote_saving_code
# laravel socialte is used to set up login with google
    composer require laravel/socialite
    followed this guide
    https://therealprogrammer.com/laravel-9-socialite-login-with-google-gmail-account/
    https://web-tuts.com/laravel-9-socialite-login-with-google-example/
    
