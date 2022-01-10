
@component('mail::message')

# First Voting Code 
<p> 
Namaskar  {{$user->name}}! <br> 
Your voting code is given below. 
With this code you can open the voting form. 
Please go to the url where you are supposed to give the code.</p> 
<p> नमस्कार! <br> 
तपाइकाे भोटिङ कोड तल लेखिएको छ। <br>
कृपया यो कोड लाइ   अघि देखाएको फर्ममा लेख्नुहोस र लेखि सकेपछि त्यसै 
ठाउमा वनाएकाे वटन थिच्नुहाेस ।  
</p> 
@component('mail::button', ['url' => ''])
 {{ $code }} 
@endcomponent

धन्यवाद / Thanks,<br>
{{ config('app.name') }}
@endcomponent
