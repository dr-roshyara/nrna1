<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .header h1 {
            color: #b5862b;
            font-size: 24px;
            margin: 0 0 8px;
        }
        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #0369a1;
            padding: 16px;
            margin: 24px 0;
            border-radius: 0 8px 8px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #b5862b 0%, #9a6e1f 100%);
            color: white !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 24px 0;
            text-align: center;
        }
        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            font-size: 13px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>🗳️ Election Invitation</h1>
            </div>

            <p>{{ $content['greeting'] }}</p>

            <p>{{ $content['body'] }}</p>

            <div class="info-box">
                <p style="margin: 0 0 4px; font-weight: 600;">{{ $content['election_label'] }}</p>
                <p style="margin: 0 0 12px;">{{ $content['election_name'] ?? 'Election' }}</p>

                <p style="margin: 0 0 4px; font-weight: 600;">{{ $content['organisation_label'] }}</p>
                <p style="margin: 0;">{{ $content['organisation_name'] ?? 'Organisation' }}</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">
                    {{ $content['button_text'] }}
                </a>
            </div>

            <div class="footer">
                <p>{{ $content['expiry_note'] }}</p>
                <p>{{ $content['ignore_note'] }}</p>
            </div>
        </div>
    </div>
</body>
</html>
