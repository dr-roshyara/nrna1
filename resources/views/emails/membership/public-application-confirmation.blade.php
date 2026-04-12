<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: sans-serif; color: #1f2937; max-width: 600px; margin: 0 auto; padding: 24px;">
  <h2 style="color: #7c3aed;">Application Received</h2>
  <p>Dear {{ $firstName }},</p>
  <p>Thank you for applying to become a member of <strong>{{ $organisation->name }}</strong>.</p>
  <p>We have received your application and it is currently under review. You will be notified by email once a decision has been made.</p>
  <p style="color: #6b7280; font-size: 0.875rem;">If you did not submit this application, please ignore this email.</p>
  <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">
  <p style="color: #9ca3af; font-size: 0.75rem;">{{ $organisation->name }}</p>
</body>
</html>
