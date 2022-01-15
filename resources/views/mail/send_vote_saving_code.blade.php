@component('mail::message')
# Code to see or check your Vote 
<p> 
Namaskar  {{$user->name}}! <br> 
Thank you for your vote. We are sending you a very secrect and unique code below. 
Please keep that code very secretly. You can use this code to check your final vote. 
If you want to see your vote again , please go to the follwoing link and 
submit the code. </p> 
<p style="margin-top: 2px;  margin-bottom:2px; text-align:center;"> 
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
 पहिचान गर्न सक्ने छैन। यसैले तपाइले गर्नु भएको मतदान पुर्ण रूपमा गोप्य रहने छ। </p> 
 
 <p style="margin-top: 2px; margin-bottom:2px; text-align:center;"> 
 <a href="{{ route('vote.verify_to_show') }}" 
 style="color:#1E90FF; font-weight:bold; "> 
 आफ्नो मत हेर्न यहाँ क्लिक गर्नुहोस। </a> 

</p> 
@component('mail::button', ['url' => ''])
{{ $vote_saving_code }}
@endcomponent

<p style="color:red; font-weight:bold; margin-top:3px;"> 
  पुनश्चः  यदि अरु कसैले तपाइलाइ  मत देखाउन दवाव दिने संम्भावना छ भने वा तपाइलाइ आफुुले हालेको मत अरूले हेर्न सक्लाकी ? 
  भन्ने डर छ भने  वा त्यस किसमको अलिकति पनि दवाव आउने संम्भावना छ भने 
कृपया यो इमेल लाइ अहिले नै डिलिट गरि दिनुहोस। 
यो इमेलमा भएको कोड डिलिट भए पछि माथि उल्लेखित कोड विना अरू कुनै माध्यम
वाट पनि तपाइले गर्नु भएको मतदान वा तपाइकाे मत कुन हो भनेर कसैले पनि पत्ता लगाउन सक्ने छैन।  </p>

धन्यवाद / Thanks,<br>
{{ config('app.name') }}
@endcomponent
