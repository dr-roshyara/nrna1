<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.verify_email_subject', [], app()->getLocale()) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header with gradient */
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .tagline {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Main content */
        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .message {
            font-size: 14px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .message p {
            margin-bottom: 15px;
        }

        /* Button */
        .button-wrapper {
            text-align: center;
            margin: 40px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
            background-color: #2563eb !important;
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            border: 2px solid #1e40af !important;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        /* Security note */
        .security-note {
            background-color: #f0f4ff;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 30px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #1e40af;
        }

        .security-note strong {
            color: #1e40af;
        }

        /* Link backup */
        .link-section {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
            font-size: 12px;
            color: #666;
        }

        .link-section p {
            margin-bottom: 8px;
        }

        .link-section code {
            display: block;
            background-color: #fff;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            margin-top: 8px;
            word-break: break-all;
            font-family: 'Monaco', 'Courier', monospace;
            font-size: 11px;
            color: #374151;
        }

        /* Footer */
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #666;
        }

        .footer-links {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }

        .trust-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            font-size: 11px;
            color: #666;
        }

        .badge-icon {
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }

            .header {
                padding: 30px 20px;
            }

            .content {
                padding: 25px 20px;
            }

            .greeting {
                font-size: 16px;
            }

            .message {
                font-size: 13px;
            }

            .button {
                display: block;
                width: 100%;
                text-align: center;
            }

            .footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🔐 PUBLIC DIGIT</div>
            <div class="tagline">{{ __('emails.secure_voting_platform', [], app()->getLocale()) }}</div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                {{ __('emails.hello', ['name' => $user->first_name ?? $user->name], app()->getLocale()) }}
            </div>

            <div class="message">
                <p>{{ __('emails.verify_email_intro', [], app()->getLocale()) }}</p>
                <p>{{ __('emails.verify_email_description', [], app()->getLocale()) }}</p>
            </div>

            <!-- Verification Button -->
            <div class="button-wrapper">
                <a href="{{ $verificationUrl }}" class="button" style="display: inline-block !important; padding: 14px 40px !important; background: #2563eb !important; background-color: #2563eb !important; color: #ffffff !important; text-decoration: none !important; border-radius: 6px !important; font-weight: 600 !important; font-size: 15px !important; border: 2px solid #1e40af !important; line-height: 1.4 !important;">
                    {{ __('emails.verify_email_button', [], app()->getLocale()) }}
                </a>
            </div>

            <!-- Security Note -->
            <div class="security-note">
                <strong>🔒 {{ __('emails.security_note', [], app()->getLocale()) }}</strong><br>
                {{ __('emails.security_note_text', [], app()->getLocale()) }}
            </div>

            <!-- Backup Link -->
            <div class="link-section">
                <p><strong>{{ __('emails.trouble_clicking', [], app()->getLocale()) }}</strong></p>
                <p>{{ __('emails.copy_link_below', [], app()->getLocale()) }}</p>
                <code>{{ $verificationUrl }}</code>
            </div>

            <!-- Not you -->
            <div class="message" style="font-size: 13px; color: #999; margin-top: 30px;">
                {{ __('emails.not_you', [], app()->getLocale()) }}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-links">
                <a href="{{ url('/') }}">{{ __('emails.visit_website', [], app()->getLocale()) }}</a>
                <a href="{{ url('/about') }}">{{ __('emails.about_us', [], app()->getLocale()) }}</a>
            </div>

            <div class="divider"></div>

            <p>{{ __('emails.footer_text', [], app()->getLocale()) }}</p>

            <div class="trust-badge">
                <span class="badge-icon">🇩🇪</span>
                <span>{{ __('emails.german_hosting', [], app()->getLocale()) }}</span>
            </div>

            <div class="trust-badge">
                <span class="badge-icon">🔐</span>
                <span>{{ __('emails.gdpr_compliant', [], app()->getLocale()) }}</span>
            </div>

            <p style="margin-top: 15px; font-size: 11px;">
                © {{ date('Y') }} PUBLIC DIGIT. {{ __('emails.all_rights_reserved', [], app()->getLocale()) }}
            </p>
        </div>
    </div>
</body>
</html>
