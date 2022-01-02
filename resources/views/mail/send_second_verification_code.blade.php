@component('mail::message')
# Vote Conformation Code 
<p> 
Namaskar  {{$user->name}}! <br> 
Thank you for your vote. Your code to conform your vote is given below. 
Please go to the url where you are supposed to give the code.</p> 
<p> नमस्कार! <br> 
यहाँले भोट गर्ने काम लगभग सकाइसक्नु भएको छ।  <br>
कृपया अव तल लेखिएको कोड लाइ  अघि  भर्खरै देखाएको फर्ममा लेख्नुहोस र लेखि सकेपछि आफ्नो भोट सेभ गर्नलाइ त्यसै 
ठाउमा वनाएकाे वटन थिच्नुहाेस ।  
</p> 

@component('mail::button', ['url' => ''])
 {{ $code }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
