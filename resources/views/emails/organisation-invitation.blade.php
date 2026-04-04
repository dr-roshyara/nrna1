<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organisation Invitation</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:36px 40px;text-align:center;">
              <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;">You're Invited!</h1>
              <p style="margin:8px 0 0;color:#c7d2fe;font-size:15px;">Join {{ $invitation->organisation->name }}</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:36px 40px;">
              <p style="margin:0 0 20px;color:#374151;font-size:15px;line-height:1.6;">
                <strong>{{ $invitation->invitedBy->name }}</strong> has invited you to join
                <strong>{{ $invitation->organisation->name }}</strong> as a
                <strong>{{ ucfirst($invitation->role) }}</strong>.
              </p>

              @if($invitation->message)
              <div style="background:#f8fafc;border-left:4px solid #6366f1;border-radius:4px;padding:16px 20px;margin:0 0 24px;">
                <p style="margin:0;color:#4b5563;font-size:14px;font-style:italic;">
                  "{{ $invitation->message }}"
                </p>
              </div>
              @endif

              <!-- CTA Button -->
              <div style="text-align:center;margin:32px 0;">
                <a href="{{ $acceptUrl }}"
                   style="display:inline-block;background:#4f46e5;color:#ffffff;text-decoration:none;font-size:16px;font-weight:600;padding:14px 36px;border-radius:10px;">
                  Accept Invitation
                </a>
              </div>

              <p style="margin:0;color:#6b7280;font-size:13px;text-align:center;">
                This invitation expires on {{ $invitation->expires_at->format('d M Y') }}.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:20px 40px;text-align:center;">
              <p style="margin:0;color:#9ca3af;font-size:12px;">
                If you didn't expect this invitation, you can safely ignore this email.<br>
                This link was sent from PublicDigit on behalf of {{ $invitation->organisation->name }}.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
