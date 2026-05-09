<!DOCTYPE html>
<html>

<head>
    <title>Reset Password - BookVerse</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.5;
            background-color: #f0f2f5;
            color: #1e293b;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Email Card */
        .email-card {
            background: #ffffff;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 40px 32px;
            text-align: center;
        }

        .logo-icon {
            font-size: 52px;
            margin-bottom: 12px;
        }

        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 6px;
            letter-spacing: -0.3px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
            margin: 0;
        }

        /* Content */
        .content {
            padding: 40px 36px;
        }

        .greeting {
            font-size: 22px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .description {
            color: #475569;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        /* Button Container */
        .button-container {
            text-align: center;
            margin: 32px 0 36px;
        }

        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 40px;
            box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.3);
            transition: all 0.25s ease;
            letter-spacing: 0.3px;
            border: none;
        }

        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px rgba(37, 99, 235, 0.4);
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        /* Alternative Section */
        .alternative-section {
            background: #f8fafc;
            border-radius: 20px;
            padding: 24px;
            margin: 28px 0;
            text-align: center;
        }

        .alternative-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .alternative-title span {
            font-size: 16px;
        }

        .token-code {
            font-family: 'SF Mono', 'Courier New', monospace;
            font-size: 14px;
            font-weight: 600;
            color: #1e40af;
            background: #ffffff;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            word-break: break-all;
            margin-top: 12px;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider-text {
            padding: 0 16px;
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
        }

        /* Warning Box */
        .warning-box {
            background: #fffbeb;
            border-radius: 16px;
            padding: 16px 20px;
            margin: 24px 0;
            border-left: 4px solid #f59e0b;
        }

        .warning-text {
            font-size: 13px;
            color: #92400e;
            line-height: 1.5;
        }

        .warning-text strong {
            font-weight: 700;
        }

        /* Help Section */
        .help-section {
            text-align: center;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }

        .help-text {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 12px;
        }

        .help-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: color 0.2s;
        }

        .help-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            background: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .copyright {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .footer-text {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .address {
            font-size: 11px;
            color: #cbd5e1;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 520px) {
            .container {
                padding: 12px;
            }

            .content {
                padding: 28px 20px;
            }

            .header {
                padding: 32px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .greeting {
                font-size: 18px;
            }

            .reset-button {
                padding: 12px 28px;
                font-size: 14px;
            }

            .token-code {
                font-size: 12px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="email-card">
            <!-- Header - Branding -->
            <div class="header">
                <div class="logo-icon">📚</div>
                <h1>BookVerse</h1>
                <p>Reset your password securely</p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">
                    Hello, {{ $name ?? 'BookLover' }}! 👋
                </div>

                <div class="description">
                    We received a request to reset the password for your BookVerse account.
                    Click the button below to create a new password.
                </div>

                <!-- Main CTA Button -->
                <div class="button-container">
                    <a href="{{ $resetUrl ?? url('/reset-password?token=' . $token . '&email=' . $email) }}"
                        style="display: inline-block; background: linear-gradient(135deg, #3b82f6, #2563eb); color: #ffffff !important; font-size: 15px; font-weight: 600; padding: 14px 32px; text-decoration: none; border-radius: 48px; box-shadow: 0 6px 18px -4px rgba(37,99,235,0.35); border: none;">
                        Reset Password
                    </a>
                </div>

                <!-- Alternative Method -->
                <div class="alternative-section">
                    <div class="alternative-title">
                        <span></span> HAVING TROUBLE?
                    </div>
                    <div style="font-size: 13px; color: #64748b;">
                        Copy this manual reset code:
                    </div>
                    <div class="token-code">
                        {{ $token }}
                    </div>
                    <div style="font-size: 12px; color: #94a3b8; margin-top: 12px;">
                        Use this code on the reset password page
                    </div>
                </div>

                <!-- Security Warning -->
                <div class="warning-box">
                    <div class="warning-text">
                        <strong>Security Notice:</strong> This link will expire in <strong>60 minutes</strong> for
                        your safety.
                        If you didn't request this, please ignore this email.
                    </div>
                </div>

                <!-- Help Section -->
                <div class="help-section">
                    <div class="help-text">
                        Need assistance? Our support team is here for you.
                    </div>
                    <a href="mailto:customerservice@bookverse.com" class="help-link">
                        Contact Customer Support
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="copyright">
                    © {{ date('Y') }} PT. BookVerse Global Media
                </div>
                <div class="footer-text">
                    Your reading companion for life
                </div>
                <div class="address">
                    Jl. Raya Karanglo No.KM. 2, Tasikmadu<br>
                    Lowokwaru, Kota Malang, Jawa Timur 65153<br>
                    Indonesia
                </div>
            </div>
        </div>
    </div>
</body>

</html>
