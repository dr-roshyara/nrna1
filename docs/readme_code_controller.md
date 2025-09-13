
what should  Create method in Code Controller do ? 
#1
Checks if you are allowed to vote.
	If you aren’t eligible, it shows you a message saying you can’t vote, and explains why.
 if allowed to vote go ahead .

#2 check if the code for that particular voter is already there! 
 if already there then check if the code is valid 
   when is the code_model valid 
    $code->is_codemodel_valid=true ; 
    a) Check the time (time from code1_sent_at till now)
   b) Check if it has already voted 
   c) is_code1_usable=true 
   c) check if it has alrady got code2_set 
   d) 
 # 3: Read ip address and check if ip address this time same as the ip address saved in  
   User model 
   - If ip address does not match then redirect to vode deneid page 
   - 

# case: code does not exisits 
it creates a new record for you, which keeps track of your voting session.
 when  sending email it should  do the following : 
	 2) set is_codemodel =true
    1) save code1 as hashed
	 2)set    is_code1_usable: 1 
	 3) has_code1_sent: 1,
   4)save client_ip: 
	 5) code1_sent_at =now() 
	 6) set voting_time_in_minutes: $this->voting_time_in_minutes,
	send notification         $auth_user->notify(new SendFirstVerificationCode($auth_user, $form_opening_code));
	shouldSendCode is true only if has_code1_sent is false. That means we sent only one time. 
   Code is sent only once . 

   if code is not reached to voter then 
   code1 time is longer than voting_time_in_minutes , then he should contact the administrator : 
   forward to Denial page
	

Checks if you have already voted.

	If you have, it tells you that you have already voted and stops you from voting again.



After sending the code to your email, it takes you to a page where you can enter the code to prove it’s really you.
- check if code1 of the user  is empty ? if yes, then generate new code 
set the follwoing variable : 

code fields:

   id: 1,
    user_id: 1,
    code1: "$2y$10$X/nTGG.UP4y32MSKPnwvp.ofNL7EouGB3TtSFtHcZvCvzBC9/HMsy",
    code2: "$2y$10$F39e/ckdF0t1JCoiw8BeMu4U.D8.cba2D2CQvEnWTTQXluosMPkHi",
    code3: null,
    code4: null,
    vote_show_code: null,
    is_code1_usable: 0,
    is_code2_usable: 0,
    code2_sent_at: "2025-08-03 12:28:21",
    is_code3_usable: 0,
    is_code4_usable: 0,
    can_vote_now: 0,
    has_voted: 0,
    voting_time_in_minutes: 30,
    vote_last_seen: null,
    created_at: "2025-08-01 14:31:28",
    updated_at: "2025-08-04 18:25:41",
    code1_used_at: "2025-08-03 12:27:49",
    code2_used_at: "2025-08-03 12:28:49",
    code3_used_at: null,
    code4_used_at: null,
    code_for_vote: "$2y$10$UTeB.atmVFDVNWRmh7lcpOZfmR0xSb4ecfdsFaZZBbeFcSu6lHTQC",
    vote_submitted: 0,
    vote_submitted_at: null,
    has_code1_sent: 1,
    has_code2_sent: 1,
    client_ip: "127.0.0.1",
    is_codemodel_valid: true
    
