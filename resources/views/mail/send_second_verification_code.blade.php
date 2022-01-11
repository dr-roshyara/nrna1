@component('mail::message')
# Vote Conformation Code 
<p> 
Namaskar  {{$user->name}}! <br> 
Thank you for your vote. Your code to conform your vote is given below. 
Please go to the url where you are supposed to give the code. 
<br> 
<span style="font-weight:bold;"> Note:</span>  
 You can also click the following link
 and give the voting code
 </p> 
<p style="margin-top: 2px; margin-bottom:2px; text-align:center;"> 
 <a href="{{ route('vote.verfiy') }}" 
 style="color:#1E90FF; font-weight:bold; "> 
  मतदान पुर्ण रूपमा सेभ गर्नलाइ  यहाँ क्लिक गर्नुहोस। </a> 
</p> 
<p> नमस्कार! <br> 
यहाँले भोट गर्ने काम लगभग सकाइसक्नु भएको छ।  <br>
कृपया अव तल लेखिएको कोड लाइ  अघि  भर्खरै देखाएको फर्ममा लेख्नुहोस र लेखि सकेपछि आफ्नो भोट सेभ गर्नलाइ त्यसै 
ठाउमा वनाएकाे वटन थिच्नुहाेस ।  
<br>
<span style="font-weight:bold;"> पुनश्चः </span> तल उल्लेखित  लिन्कमा क्लीक गरेर पनि 
अघि देखाएको पर्ममा पुग्न सकिन्छ। 
</p> 
<p style="margin-top: 2px; margin-bottom:2px; text-align:center;"> 
 <a href="{{ route('vote.verfiy') }}" 
 style="color:#1E90FF; font-weight:bold; "> 
  मतदान पुर्ण रूपमा सेभ गर्नलाइ  यहाँ क्लिक गर्नुहोस। </a> 
</p> 

@component('mail::button', ['url' => ''])
 {{ $code }}
@endcomponent

धन्यवाद / Thanks,<br>
{{ config('app.name') }}
@endcomponent
