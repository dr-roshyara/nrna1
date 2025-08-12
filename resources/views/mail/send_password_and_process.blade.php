<!-- Plain HTML email (no mail::message dependency) -->
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="font-family: Arial, sans-serif; color:#222; line-height:1.5;">
  <tr>
    <td style="max-width:640px; margin:0 auto; padding:24px;">
      <h1 style="margin:0 0 12px;">Your Voting Account Details</h1>

      <p style="margin:0 0 10px;">
        Namaskar {{ $name }}! <br>
        Please find your full account details and the voting procedure below.
      </p>

      <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse; margin:10px 0;">
        <tr><td style="padding:6px 0; width:180px;"><strong>User ID:</strong></td><td style="padding:6px 0;">{{ $userId }}</td></tr>
        <tr><td style="padding:6px 0;"><strong>Name:</strong></td><td style="padding:6px 0;">{{ $name }}</td></tr>
        <tr><td style="padding:6px 0;"><strong>Username (Email):</strong></td><td style="padding:6px 0;">{{ $email }}</td></tr>
        <tr><td style="padding:6px 0;"><strong>Password:</strong></td><td style="padding:6px 0;">{{ $password }}</td></tr>
        <tr><td style="padding:6px 0;"><strong>Time to call:</strong></td><td style="padding:6px 0;">{{ $time }}</td></tr>
      </table>

      <hr style="border:none; border-top:1px solid #ccc; margin:16px 0;">

      <p style="margin:0 0 6px;"><strong>Short Voting Process:</strong></p>
      <ol style="padding-left:18px; margin:0 0 12px;">
        <li><strong>Login:</strong> Use the <strong>email</strong> and <strong>password</strong> provided in this email to log in.</li>
        <li><strong>Video verification:</strong> Make a video call with the <strong>Election Officers</strong> to verify your identity and confirm no fraud is happening.</li>
        <li><strong>Officer approval:</strong> After verification, the officers will press the button to allow you to cast your vote.</li>
        <li><strong>Cast vote:</strong> Click <em>“Click here to vote”</em> and proceed to submit your vote.</li>
      </ol>

      @if (!empty($contacts))
      <p style="margin:12px 0 4px;"><strong>To arrange the video call, contact:</strong></p>
      <ul style="padding-left:18px; margin:0 0 12px;">
        @foreach ($contacts as $c)
          <li>{{ $c }}</li>
        @endforeach
      </ul>
      @endif

      <p style="margin:12px 0;">Login page: <a href="{{ $loginUrl }}" style="color:#1E90FF;">{{ $loginUrl }}</a></p>

      @php $voteLink = $voteUrl ?? $loginUrl; @endphp
      <p style="text-align:center; margin:16px 0 24px;">
        <a href="{{ $voteLink }}" style="background:#1E90FF; color:#fff; padding:10px 18px; text-decoration:none; border-radius:4px; font-weight:bold; display:inline-block;">
          Click here to vote
        </a>
      </p>

      <hr style="border:none; border-top:1px solid #ccc; margin:20px 0;">

      <h1 style="margin:0 0 12px;">तपाईंको भोटिङ अकाउन्ट विवरण</h1>

      <p style="margin:0 0 10px;">
        नमस्कार {{ $name }}! <br>
        तल तपाईंको सम्पूर्ण विवरण र छोटो मतदान प्रक्रिया दिइएको छ।
      </p>

      <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse; margin:10px 0;">
        <tr><td style="padding:6px 0; width:180px;"><strong>युजर आईडी:</strong></td><td style="padding:6px 0;">{{ $userId }}</td></tr>
        <tr><td style="padding:6px 0;"><strong>नाम:</strong></td><td style="padding:6px 0;">{{ $name }}</td></tr>
        <tr><td style="padding:6px 0;"><strong>युजरनेम (इमेल):</strong></td><td style="padding:6px 0;">{{ $email }}</td></tr>
        <tr><td style="padding:6px 0;"><strong>पासवर्ड:</strong></td><td style="padding:6px 0;">{{ $password }}</td></tr>
        <tr><td style="padding:6px 0;"><strong> मतदानको लागि समय:</strong></td><td style="padding:6px 0;">{{ $time }}</td></tr>
      </table>

      <hr style="border:none; border-top:1px solid #ccc; margin:16px 0;">

      <p style="margin:0 0 6px;"><strong>मतदान प्रक्रिया:</strong></p>
      <ol style="padding-left:18px; margin:0 0 12px;">
        <li><strong>लगइन:</strong> यस इमेलमा दिएको <strong>इमेल</strong> र <strong>पासवर्ड</strong> प्रयोग गरी लगइन गर्नुहोस्।</li>
        <li><strong>भिडियो प्रमाणीकरण:</strong> <strong>निर्वाचन अधिकृतहरू</strong>सँग भिडियो कल गरी आफ्नो परिचय पुष्टि गर्नुहोस् र  तपाइ स्वय ले भोट हाली रहेको प्रमाणित गर्नुहोस। </li>
        <li><strong>स्वीकृति:</strong> प्रमाणीकरणको प्रक्रिया  पछि अधिकृतहरूले तपाईंलाई भोट हाल्न अनुमति दिने बटन थिच्नेछन्।</li>
        <li><strong>भोट हाल्नुहोस्:</strong> <em>“यहाँ क्लिक गरेर भोट हाल्नुहोस्”</em> बटनमा क्लिक गरी अगाडि र उल्लेखित भए अनुसार अनुशरण गर्दै  बढ्नुहोस् र मतदान सम्पन्न गर्नुहोस।  
        </li>
      </ol>

      @if (!empty($contacts))
      <p style="margin:12px 0 4px;"><strong>भिडियो कल मिलाउन सम्पर्क गर्नुहोस्:</strong></p>
      <ul style="padding-left:18px; margin:0 0 12px;">
        @foreach ($contacts as $c)
          <li>{{ $c }}</li>
        @endforeach
      </ul>
      @endif

      <p style="margin:12px 0;">लगइन पेज: <a href="{{ $loginUrl }}" style="color:#1E90FF;">{{ $loginUrl }}</a></p>

      <p style="text-align:center; margin:16px 0 0;">
        <a href="{{ $voteLink }}" style="background:#1E90FF; color:#fff; padding:10px 18px; text-decoration:none; border-radius:4px; font-weight:bold; display:inline-block;">
          यहाँ क्लिक गरेर भोट हाल्नुहोस्
        </a>
      </p>

      <p style="margin-top:18px;">धन्यवाद,<br>{{ config('app.name') }}</p>
    </td>
  </tr>
</table>
