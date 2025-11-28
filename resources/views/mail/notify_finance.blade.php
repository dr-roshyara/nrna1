<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance {{ $type }} Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); overflow: hidden;">

                    <!-- Header with Professional Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                📊 Finance {{ $type }}
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #dbeafe; font-size: 14px; font-weight: 400;">
                                Financial Information Sheet
                            </p>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">

                            <!-- Sender Information -->
                            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px 20px; margin-bottom: 30px; border-radius: 6px;">
                                <p style="margin: 0; color: #1e40af; font-size: 14px; line-height: 1.5;">
                                    <strong>Submitted By:</strong> {{ $user['name'] }}
                                </p>
                            </div>

                            <!-- Document Information -->
                            <h2 style="margin: 0 0 20px 0; color: #1f2937; font-size: 20px; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 12px;">
                                {{ $type }} Details
                            </h2>

                            <!-- Finance Data Table -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 20px 0;">
                                @php
                                    $keys = array_keys($finance);
                                    $index = 0;
                                @endphp

                                @foreach($keys as $key)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 16px 12px; background-color: {{ $index % 2 == 0 ? '#f9fafb' : '#ffffff' }};">
                                        <p style="margin: 0; color: #374151; font-size: 14px; font-weight: 600;">
                                            {{ $loop->iteration }}. {{ $key }}
                                        </p>
                                    </td>
                                    <td style="padding: 16px 12px; background-color: {{ $index % 2 == 0 ? '#f9fafb' : '#ffffff' }}; text-align: right;">
                                        <p style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 500;">
                                            {{ $finance[$key] }}
                                        </p>
                                    </td>
                                </tr>
                                @php $index++; @endphp
                                @endforeach
                            </table>

                            <!-- Summary Box -->
                            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #86efac; padding: 20px; margin: 30px 0; border-radius: 8px;">
                                <p style="margin: 0 0 8px 0; color: #14532d; font-size: 15px; font-weight: 700;">
                                    ✓ Document Summary
                                </p>
                                <p style="margin: 0; color: #166534; font-size: 13px; line-height: 1.5;">
                                    Total entries: <strong>{{ count($keys) }}</strong><br>
                                    Document type: <strong>{{ $type }}</strong><br>
                                    Timestamp: <strong>{{ now()->format('F d, Y h:i A') }}</strong>
                                </p>
                            </div>

                            <!-- Information Notice -->
                            <div style="background-color: #fffbeb; border-left: 4px solid #fbbf24; padding: 16px 20px; margin: 25px 0; border-radius: 6px;">
                                <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                                    <strong>Note:</strong> This is an automated notification. Please review the information above and take appropriate action as required.
                                </p>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px;">
                                <strong>Thank you</strong>
                            </p>
                            <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 13px;">
                                {{ config('app.name') }}
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px; line-height: 1.5;">
                                This is an automated message. Please do not reply to this email.<br>
                                For inquiries, please contact the finance department.
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
