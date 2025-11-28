<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Verification Code</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); overflow: hidden;">

                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                🗳️ Voting Verification Code
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #e0e7ff; font-size: 14px; font-weight: 400;">
                                {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">

                            <!-- English Section -->
                            <div style="margin-bottom: 35px;">
                                <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 20px; font-weight: 600;">
                                    Hello {{ $user->name }},
                                </h2>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    Thank you for participating in the NRNA election. Your voting verification code is provided below. This code is required to access your voting ballot.
                                </p>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    Please enter this code on the verification form to proceed with casting your vote.
                                </p>

                                <!-- Info Box -->
                                <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px 20px; margin: 20px 0; border-radius: 6px;">
                                    <p style="margin: 0; color: #1e40af; font-size: 14px; line-height: 1.5;">
                                        <strong>Important:</strong> This code is valid for 20 minutes. For security reasons, please do not share this code with anyone.
                                    </p>
                                </div>
                            </div>

                            <!-- Code Display Box -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; padding: 30px; box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);">
                                            <p style="margin: 0 0 10px 0; color: #e0e7ff; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                                Your Verification Code
                                            </p>
                                            <p style="margin: 0; color: #ffffff; font-size: 36px; font-weight: 700; letter-spacing: 6px; font-family: 'Courier New', monospace;">
                                                {{ $code }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('election.use_slug_path', false) ? route('voter.start') : route('dashboard') }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
                                            Verify Code & Start Voting
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <hr style="border: none; border-top: 2px solid #e5e7eb; margin: 40px 0;">

                            <!-- Nepali Section -->
                            <div style="margin-top: 35px;">
                                <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 20px; font-weight: 600;">
                                    आदरणीय {{ $user->name }} ज्यू, नमस्कार
                                </h2>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    NRNA निर्वाचनमा सहभागी हुनुभएकोमा धन्यवाद। तपाईंको मतदान प्रमाणीकरण कोड माथि उल्लेख गरिएको छ। यो कोड मतदान फारम खोल्न आवश्यक छ।
                                </p>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    कृपया यो कोड प्रमाणीकरण फारममा लेख्नुहोस् र मतदान गर्न अघि बढ्नुहोस्।
                                </p>

                                <!-- Info Box Nepali -->
                                <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px 20px; margin: 20px 0; border-radius: 6px;">
                                    <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.5;">
                                        <strong>महत्वपूर्ण:</strong> यो कोड २० मिनेटको लागि मात्र मान्य छ। सुरक्षा कारणले, कृपया यो कोड कसैसँग साझेदारी नगर्नुहोस्। कोड लेख्दा ठूलो र सानो अक्षरको ध्यान दिनुहोस्।
                                    </p>
                                </div>

                                <!-- Quick Tips -->
                                <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; margin-top: 20px;">
                                    <p style="margin: 0 0 10px 0; color: #1f2937; font-size: 14px; font-weight: 600;">
                                        📝 सुझावहरू:
                                    </p>
                                    <ul style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.6;">
                                        <li style="margin-bottom: 8px;">कोड कागजमा लेखेर राख्नुहोस्</li>
                                        <li style="margin-bottom: 8px;">माथिको बटनमा क्लिक गरी सिधै मतदान पृष्ठमा जानुहोस्</li>
                                        <li style="margin-bottom: 0;">कोड लेख्दा ठूलो र सानो अक्षरको फरक छ (Case-sensitive)</li>
                                    </ul>
                                </div>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px;">
                                <strong>धन्यवाद / Thank you</strong>
                            </p>
                            <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 13px;">
                                {{ config('app.name') }}
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px; line-height: 1.5;">
                                This is an automated message. Please do not reply to this email.<br>
                                If you need assistance, please contact the election committee.
                            </p>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                                <p style="margin: 0; color: #9ca3af; font-size: 11px;">
                                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                                </p>
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
