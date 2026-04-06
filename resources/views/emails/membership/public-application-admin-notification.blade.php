<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: sans-serif; color: #1f2937; max-width: 600px; margin: 0 auto; padding: 24px;">
  <h2 style="color: #7c3aed;">New Membership Application</h2>
  <p>A new membership application has been submitted for <strong>{{ $organisation->name }}</strong>.</p>

  <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
    <tr><td style="padding: 6px 8px; font-weight: 600; color: #6b7280; width: 160px;">Name</td><td style="padding: 6px 8px;">{{ ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '') }}</td></tr>
    <tr style="background:#f9fafb;"><td style="padding: 6px 8px; font-weight: 600; color: #6b7280;">Email</td><td style="padding: 6px 8px;">{{ $data['email'] ?? '—' }}</td></tr>
    <tr><td style="padding: 6px 8px; font-weight: 600; color: #6b7280;">Telephone</td><td style="padding: 6px 8px;">{{ $data['telephone_number'] ?? '—' }}</td></tr>
    <tr style="background:#f9fafb;"><td style="padding: 6px 8px; font-weight: 600; color: #6b7280;">City</td><td style="padding: 6px 8px;">{{ $data['city'] ?? '—' }}</td></tr>
    <tr><td style="padding: 6px 8px; font-weight: 600; color: #6b7280;">Country</td><td style="padding: 6px 8px;">{{ $data['country'] ?? '—' }}</td></tr>
    <tr style="background:#f9fafb;"><td style="padding: 6px 8px; font-weight: 600; color: #6b7280;">Profession</td><td style="padding: 6px 8px;">{{ $data['profession'] ?? '—' }}</td></tr>
    <tr><td style="padding: 6px 8px; font-weight: 600; color: #6b7280;">Education</td><td style="padding: 6px 8px;">{{ $data['education_level'] ?? '—' }}</td></tr>
    @if(!empty($data['message']))
    <tr style="background:#f9fafb;"><td style="padding: 6px 8px; font-weight: 600; color: #6b7280; vertical-align: top;">Message</td><td style="padding: 6px 8px;">{{ $data['message'] }}</td></tr>
    @endif
  </table>

  <a href="{{ $reviewUrl }}" style="display: inline-block; background: #7c3aed; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 8px;">
    Review Application
  </a>

  <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">
  <p style="color: #9ca3af; font-size: 0.75rem;">{{ $organisation->name }} — Membership Management</p>
</body>
</html>
