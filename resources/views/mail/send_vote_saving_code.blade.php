@component('mail::message')
# Code to see or check your Vote 
<p> 
Namaskar  {{$user->name}}! <br> 
Thank you for your vote. We are sending you a very secrect and unique code below. 
Please keep that code very secretly. You can use this code to check your final vote. 
If you want to see your vote again , please go to the follwoing link and 
submit the code. </p> 
<p style="margin-top: 2px; margin-bottom:2px; text-align:center;"> 
 <a href="{{ route('vote.verify_to_show') }}" 
style="color:#1E90FF; font-weight:bold; "> 
Please click here to see your vote </a> 

</p> 
<p> आदरणिय {{$user->name}} ज्यु,  नमस्कार! <br> 
यहाँले भोट गर्ने काम  सकाइसक्नु भएको छ। त्यस्को लागि यहाँ लाइ वधाइ छ।  <br>
 यदि तपाइलाइ आफुले  िदएको मतदान फेरि हेर्ने मन छ भने तल लेखिएको कोड लाइ  
 निम्न लिन्कमा लेख्नुहुनेछ  र लेखि सकेपछि आफ्नो भोट  हेर्न त्यहाँ भएको वटन थिच्नुहुनेछ। यदि यो कोड
 हराएमा वा डिलिट भएमा अरु कुनै पनि उपाय वाट तपाइले गर्नु भएको मतदान हेर्न  मिल्नेछैन । तपाइले गर्नु भएको 
 मतदानको पहिचान गराउने एक मात्र माध्यम तल उल्लेखित कोड हो र त्यस विना कसैले पनि यहाँले गर्नु भएको मतदानकाे 
 पहिचान गर्न सक्ने छैन। यसैले तपाइले गर्नु भएको मतदान पुर्ण रूपमा गोप्य रहने छ।  </p> 
 <p style="margin-top: 2px; margin-bottom:2px; text-align:center;"> 
 <a href="{{ route('vote.verify_to_show') }}" 
 style="color:#1E90FF; font-weight:bold; "> 
 आफ्नो मत हेर्न यहाँ क्लिक गर्नुहोस। </a> 

</p> 
@component('mail::button', ['url' => ''])
{{ $vote_saving_code }}
@endcomponent

धन्यवाद / Thanks,<br>
{{ config('app.name') }}
@endcomponent
