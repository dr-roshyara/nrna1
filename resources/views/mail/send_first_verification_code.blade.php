
@component('mail::message')

# First Voting Code 
<p> 
Namaskar  {{$user->name}}! <br> 
Your first voting code is given below. 
With this code you can open the voting form. 
Please go to the previous form  to submit the code.<br> 
 <span style="font-weight:bold;"> Note:</span>  You can also click the following link
 and give the voting code</p> 
<p style="margin-top: 2px; margin-bottom:2px; text-align:center;"> 
 <a href="{{ route('code.create') }}" 
 style="color:#1E90FF; font-weight:bold; "> 
  Click here to verify your code </a> 
</p> 


<p> आदरणिय {{$user->name}} ज्यु, नमस्कार  <br> 
तपाइकाे भोटिङ कोड तल उल्लेखित  लिन्क मुनी  चाैकुने घेराभित्र  लेखिएको छ। <br>
कृपया त्यो कोड लाइ   अघि देखाएको फर्ममा लेख्नुहोस र लेखि सकेपछि त्यसै 
ठाउमा वनाएकाे वटन थिच्नुहाेस ।  <br>
<span style="font-weight:bold;"> पुनश्चः </span> तल उल्लेखित  लिन्कमा क्लीक गरेर पनि 
अघि देखाएको पर्ममा पुग्न सकिन्छ। सवै भन्दा उत्तम उपाय तल लेखिएको कोडलाइ कापीको पानामा 
लेख्नुहोस र तल उल्लेखित लिन्कमा क्लीक गर्नुहोस। कोड लेख्दा क्यापिटल र स्माल लेटरको पनि ख्याल 
गर्नुहोला । 

</p> 

<p style="margin-top: 2px; margin-bottom:2px; text-align:center;"> 
 <a href="{{ route('code.create') }}" 
 style="color:#1E90FF; font-weight:bold; "> 
  मतदान गर्नको लागि  यहाँ क्लिक गर्नुहोस। </a> 
</p> 
  
@component('mail::button', ['url' => ''])
   {{ $code }} </p> 
@endcomponent

धन्यवाद / Thanks,<br>
{{ config('app.name') }}
@endcomponent
