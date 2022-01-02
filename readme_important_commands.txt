#add the candidates 
- You can through the site : /candidacy/update 
   using update method of CandidacyController.php 
#make notification code 
   php artisan make:notification SendFirstVerificationCode --markdown mail.send_first_verification_code
   php artisan make:notification SecondVerificationCode --markdown mail.send_second_verification_code
