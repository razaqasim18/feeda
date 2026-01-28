<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Point Redeem Coupon">
    <title>Point Redeem Coupon</title>

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

        .email-header h2 {
            margin: 0;
            font-size: 22px;
        }

        .email-body {
            padding: 20px;
            text-align: center;
        }

        .coupon-code {
            background-color: #f8f9fa;
            border: 1px dashed #aaa;
            padding: 15px;
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
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
        <h2>Your Point Redeem Coupon</h2>
    </div>

    <div class="email-body">
        <p>Thank you for redeeming your points.</p>

        <p>
            As a reward, here is your <strong>Point Redeem Coupon</strong>:
        </p>

        <div class="coupon-code">
            {{ $coupon }}
        </div>

        <p>You can use this coupon on your next purchase.</p>

        <p style="color: #e74c3c;">
            <strong>Valid until {{ $validday }}.</strong>
        </p>

        <p style="margin-top: 30px;">
            <strong>The {{ config('app.name') }} Team</strong>
        </p>
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
