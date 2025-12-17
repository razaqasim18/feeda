<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="OTP Verification">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 12px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
            text-align: center;
        }

        .email-body h3 {
            margin-top: 0;
            color: #4CAF50;
        }

        .otp-code {
            display: inline-block;
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            letter-spacing: 4px;
            margin: 20px 0;
            padding: 10px 20px;
            border: 2px dashed #4CAF50;
            border-radius: 8px;
        }

        .email-footer {
            background-color: #f4f4f9;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #666;
        }

        .email-footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Happy Birthday</h2>
        </div>
        <div class="email-body" style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
            <h2 style="color: #2c3e50;">🎉 Happy Birthday, Valued Customer! 🎂</h2>

            <p>We’re truly grateful to have you with us, and on this special day, we want to celebrate **you**!</p>

            <p>
                As a token of our appreciation, here’s a special <strong>birthday coupon</strong> just for you:
            </p>

            <div class="coupon-code"
                style="background-color: #f8f9fa; border: 1px dashed #aaa; padding: 15px; margin: 20px 0; font-size: 24px; font-weight: bold; text-align: center;">
                {{ $coupon }}
            </div>

            <p style="margin-bottom: 10px;">You can use this coupon to enjoy a discount on your next purchase.</p>

            <p style="color: #e74c3c;"><strong>Hurry! This offer is valid until
                    {{ $validday }}.</strong></p>

            <p>Once again, happy birthday and thank you for being a part of our journey. 🎈</p>

            <p style="margin-top: 30px;">Warm wishes, <br><strong>The {{ config('app.name') }} Team</strong></p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') . '/privacy-policy' }}">Privacy Policy</a> |
                <a href="{{ config('app.url') . '/contact-us' }}">Contact Support</a>
            </p>
        </div>
    </div>
</body>

</html>
