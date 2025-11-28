<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Secure Vote Receipt</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); overflow: hidden;">

                    <!-- Header with Celebration Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                🎉 Vote Successfully Recorded!
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #fef3c7; font-size: 14px; font-weight: 400;">
                                Your Secure Vote Receipt Code
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">

                            <!-- English Section -->
                            <div style="margin-bottom: 35px;">
                                <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 20px; font-weight: 600;">
                                    Congratulations {{ $user->name }}!
                                </h2>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    Your vote has been successfully recorded and securely saved in our database. Thank you for participating in this democratic process.
                                </p>

                                <!-- Success Confirmation Box -->
                                <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 6px;">
                                    <p style="margin: 0 0 8px 0; color: #065f46; font-size: 16px; font-weight: 700;">
                                        ✓ Vote Status: CONFIRMED
                                    </p>
                                    <p style="margin: 0; color: #047857; font-size: 13px; line-height: 1.5;">
                                        Your vote has been encrypted and stored securely. The voting process is now complete.
                                    </p>
                                </div>

                                <p style="margin: 20px 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    Below is your <strong>unique and confidential vote receipt code</strong>. This code is the ONLY way to view your vote later.
                                </p>
                            </div>

                            <!-- Code Display Box with Security Badge -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 10px; padding: 30px; box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3); position: relative;">
                                            <p style="margin: 0 0 10px 0; color: #fef3c7; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                                🔐 Your Confidential Receipt Code
                                            </p>
                                            <p style="margin: 0 0 15px 0; color: #ffffff; font-size: 36px; font-weight: 700; letter-spacing: 6px; font-family: 'Courier New', monospace;">
                                                {{ $vote_saving_code }}
                                            </p>
                                            <p style="margin: 0; color: #fef3c7; font-size: 11px; line-height: 1.4;">
                                                Keep this code secure and private
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Warning Box -->
                            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; margin: 30px 0; border-radius: 6px;">
                                <p style="margin: 0 0 10px 0; color: #991b1b; font-size: 15px; font-weight: 700;">
                                    ⚠️ IMPORTANT SECURITY NOTICE
                                </p>
                                <ul style="margin: 0; padding-left: 20px; color: #7f1d1d; font-size: 14px; line-height: 1.7;">
                                    <li style="margin-bottom: 8px;">This code is <strong>extremely confidential</strong> and personal</li>
                                    <li style="margin-bottom: 8px;">Without this code, <strong>no one</strong> (including you) can view your vote</li>
                                    <li style="margin-bottom: 8px;">This ensures <strong>complete voting privacy</strong> and security</li>
                                    <li style="margin-bottom: 0;"><strong>Delete this email immediately</strong> if there's any risk of others accessing it</li>
                                </ul>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('vote.verify_to_show') }}"
                                           style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                            View Your Vote
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Privacy Options -->
                            <div style="background-color: #fffbeb; border: 2px solid #fbbf24; padding: 20px; margin: 25px 0; border-radius: 8px; text-align: center;">
                                <p style="margin: 0 0 10px 0; color: #78350f; font-size: 14px; font-weight: 700;">
                                    💡 Privacy Recommendation
                                </p>
                                <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.6;">
                                    If you feel any pressure to show your vote to others, or want to ensure maximum privacy,
                                    <strong>delete this email now</strong>. Once deleted, your vote remains secure and completely anonymous.
                                </p>
                            </div>

                            <!-- Divider -->
                            <hr style="border: none; border-top: 2px solid #e5e7eb; margin: 40px 0;">

                            <!-- Nepali Section -->
                            <div style="margin-top: 35px;">
                                <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 20px; font-weight: 600;">
                                    बधाई छ {{ $user->name }} ज्यू!
                                </h2>
                                <p style="margin: 0 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    तपाईंको मत सफलतापूर्वक रेकर्ड गरिएको छ र सुरक्षित रूपमा हाम्रो डाटाबेसमा सेभ गरिएको छ। यो लोकतान्त्रिक प्रक्रियामा सहभागी हुनुभएकोमा धन्यवाद।
                                </p>

                                <!-- Success Box Nepali -->
                                <div style="background-color: #dcfce7; border-left: 4px solid #16a34a; padding: 20px; margin: 20px 0; border-radius: 6px;">
                                    <p style="margin: 0 0 8px 0; color: #14532d; font-size: 16px; font-weight: 700;">
                                        ✓ मत स्थिति: पुष्टि भयो
                                    </p>
                                    <p style="margin: 0; color: #166534; font-size: 13px; line-height: 1.5;">
                                        तपाईंको मत इन्क्रिप्ट गरी सुरक्षित रूपमा भण्डारण गरिएको छ। मतदान प्रक्रिया अब पूर्ण भयो।
                                    </p>
                                </div>

                                <p style="margin: 20px 0 15px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                    तल तपाईंको <strong>अद्वितीय र गोप्य मत रसिद कोड</strong> छ। यो कोड पछि आफ्नो मत हेर्नको लागि एकमात्र माध्यम हो।
                                </p>

                                <!-- Security Warning Nepali -->
                                <div style="background-color: #fee2e2; border-left: 4px solid #dc2626; padding: 20px; margin: 25px 0; border-radius: 6px;">
                                    <p style="margin: 0 0 12px 0; color: #7f1d1d; font-size: 15px; font-weight: 700;">
                                        ⚠️ अत्यन्त महत्वपूर्ण सुरक्षा सूचना
                                    </p>
                                    <ul style="margin: 0; padding-left: 20px; color: #7f1d1d; font-size: 14px; line-height: 1.7;">
                                        <li style="margin-bottom: 10px;">यो कोड <strong>अत्यन्त गोप्य र व्यक्तिगत</strong> छ</li>
                                        <li style="margin-bottom: 10px;">यो कोड बिना <strong>कसैले पनि</strong> (तपाईं पनि) आफ्नो मत हेर्न सक्नुहुन्न</li>
                                        <li style="margin-bottom: 10px;">यसले <strong>पूर्ण मतदान गोपनीयता</strong> र सुरक्षा सुनिश्चित गर्दछ</li>
                                        <li style="margin-bottom: 10px;">यदि अरूले यो कोड हेर्ने कुनै सम्भावना छ भने <strong>यो इमेल तुरुन्त डिलिट गर्नुहोस्</strong></li>
                                        <li style="margin-bottom: 0;">कोड डिलिट भएपछि, तपाईंको मत पूर्ण रूपमा गोप्य र सुरक्षित रहन्छ</li>
                                    </ul>
                                </div>

                                <!-- Privacy Box Nepali -->
                                <div style="background-color: #fef9c3; border: 2px dashed #ca8a04; padding: 20px; margin: 25px 0; border-radius: 8px;">
                                    <p style="margin: 0 0 10px 0; color: #713f12; font-size: 14px; font-weight: 700; text-align: center;">
                                        🛡️ गोपनीयता सिफारिस
                                    </p>
                                    <p style="margin: 0; color: #854d0e; font-size: 13px; line-height: 1.7; text-align: center;">
                                        यदि तपाईंलाई आफ्नो मत अरूलाई देखाउन दबाब आउने सम्भावना छ भने, वा अधिकतम गोपनीयता चाहनुहुन्छ भने,<br>
                                        <strong style="color: #dc2626;">कृपया यो इमेल अहिले नै डिलिट गर्नुहोस्।</strong><br>
                                        एक पटक डिलिट भएपछि, तपाईंको मत सुरक्षित र पूर्ण रूपमा गुमनाम रहन्छ।
                                    </p>
                                </div>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <div style="background-color: #dbeafe; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                                <p style="margin: 0; color: #1e40af; font-size: 13px; line-height: 1.6;">
                                    <strong>Voting Complete!</strong><br>
                                    You can now safely close this window. Thank you for your participation.
                                </p>
                            </div>
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
