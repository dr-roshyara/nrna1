<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Confirmation Code</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); overflow: hidden;">

                    <!-- Header with Success Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                ✅ Vote Confirmation Code
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #d1fae5; font-size: 14px; font-weight: 400;">
                                One More Step to Complete Your Vote
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">

                            <!-- English Section -->
                            <div style="margin-bottom: 35px;">
                                <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 20px; font-weight: 600;">
                                    Dear {{ $user->name }},
                                </h2>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    Thank you for selecting your candidates! You're almost done with the voting process.
                                </p>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    To complete and save your vote, please use the confirmation code provided below. Enter this code on the verification page to finalize your vote.
                                </p>

                                <!-- Success Message Box -->
                                <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px 20px; margin: 20px 0; border-radius: 6px;">
                                    <p style="margin: 0; color: #065f46; font-size: 14px; line-height: 1.5;">
                                        <strong>✓ Vote Selections Received</strong><br>
                                        Your candidate selections have been temporarily saved. Please confirm to complete the voting process.
                                    </p>
                                </div>
                            </div>

                            <!-- Code Display Box -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 10px; padding: 30px; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);">
                                            <p style="margin: 0 0 10px 0; color: #d1fae5; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                                Your Confirmation Code
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
                                        <a href="{{ route('vote.verify') }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); transition: all 0.3s ease;">
                                            Confirm & Save Vote
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Progress Indicator -->
                            <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; margin: 25px 0;">
                                <p style="margin: 0 0 15px 0; color: #1f2937; font-size: 14px; font-weight: 600; text-align: center;">
                                    Voting Progress
                                </p>
                                <div style="background-color: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                                    <div style="background: linear-gradient(90deg, #10b981 0%, #059669 100%); width: 75%; height: 100%;"></div>
                                </div>
                                <p style="margin: 10px 0 0 0; color: #6b7280; font-size: 12px; text-align: center;">
                                    Step 3 of 4: Verify and confirm your vote
                                </p>
                            </div>

                            <!-- Divider -->
                            <hr style="border: none; border-top: 2px solid #e5e7eb; margin: 40px 0;">

                            <!-- Nepali Section -->
                            <div style="margin-top: 35px;">
                                <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 20px; font-weight: 600;">
                                    आदरणीय {{ $user->name }} ज्यू, नमस्कार
                                </h2>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    उम्मेदवार छनोट गर्नुभएकोमा धन्यवाद! तपाईं मतदान प्रक्रिया लगभग पूरा गर्न लाग्नुभएको छ।
                                </p>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    आफ्नो मत पूर्ण रूपमा सेभ गर्नको लागि, कृपया तल दिइएको पुष्टिकरण कोड प्रयोग गर्नुहोस्। यो कोड प्रमाणीकरण पृष्ठमा लेख्नुहोस् र आफ्नो मत अन्तिम रूप दिनुहोस्।
                                </p>

                                <!-- Info Box Nepali -->
                                <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px 20px; margin: 20px 0; border-radius: 6px;">
                                    <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.5;">
                                        <strong>महत्वपूर्ण:</strong> तपाईंको मत अहिले अस्थायी रूपमा सेभ भएको छ। कृपया माथिको बटनमा क्लिक गरी र कोड प्रविष्ट गरी आफ्नो मत पुष्टि गर्नुहोस्।
                                    </p>
                                </div>

                                <!-- Quick Steps -->
                                <div style="background-color: #eff6ff; padding: 20px; border-radius: 8px; margin-top: 20px;">
                                    <p style="margin: 0 0 10px 0; color: #1f2937; font-size: 14px; font-weight: 600;">
                                        🔐 अर्को चरण:
                                    </p>
                                    <ol style="margin: 0; padding-left: 20px; color: #4b5563; font-size: 14px; line-height: 1.6;">
                                        <li style="margin-bottom: 8px;">माथिको "Confirm & Save Vote" बटनमा क्लिक गर्नुहोस्</li>
                                        <li style="margin-bottom: 8px;">पुष्टिकरण कोड प्रविष्ट गर्नुहोस्</li>
                                        <li style="margin-bottom: 0;">आफ्नो मत पुष्टि र सेभ गर्नुहोस्</li>
                                    </ol>
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
