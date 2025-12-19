<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - TCTravel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 36px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 28px 20px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 14px;
        }

        .message {
            font-size: 14px;
            color: #555;
            margin-top: 8px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            margin: 18px 0;
            font-size: 14px;
        }

        .cta-button:hover {
            opacity: 0.92;
            color: #fff;
            text-decoration: none;
        }

        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 14px 16px;
            margin: 16px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #664d03;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 24px 0;
        }

        .muted-link {
            font-size: 12px;
            color: #6c757d;
            word-break: break-all;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 18px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            font-size: 12px;
            color: #888;
            margin: 4px 0;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>🔐 Đặt lại mật khẩu</h1>
            <p>Yêu cầu đặt lại mật khẩu cho tài khoản TCTravel</p>
        </div>

        <div class="content">
            <div class="greeting">
                Xin chào{{ isset($user) && $user?->username ? ' ' . e($user->username) : '' }},
            </div>

            <div class="message">
                Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Nhấp vào nút bên dưới để tạo mật
                khẩu mới.
            </div>

            <div style="text-align:center; margin: 18px 0;">
                <a href="{{ $resetUrl }}" class="cta-button">Đặt lại mật khẩu</a>
            </div>

            <div class="info-box">
                <strong>Lưu ý:</strong>
                <p style="margin-top:6px;">
                    Liên kết đặt lại mật khẩu sẽ hết hạn sau
                    <strong>{{ $expiresMinutes ?? 60 }}</strong> phút kể từ khi email này được gửi. Nếu bạn không yêu
                    cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
                </p>
            </div>

            <div class="message">
                Nếu nút không hoạt động, sao chép và dán liên kết sau vào trình duyệt của bạn:
            </div>
            <a href="{{ $resetUrl }}"><p class="muted-link">{{ $resetUrl }}</p></a>

            <div class="divider"></div>

            <div class="message" style="text-align:center; font-style: italic; color: #888;">
                Vì sự an toàn của bạn, không chia sẻ liên kết này cho bất kỳ ai.
            </div>
        </div>

        <div class="footer">
            <p><strong>TCTravel - Hỗ trợ đặt lại mật khẩu</strong></p>
            <p>© {{ date('Y') }} TCTravel. Tất cả quyền lợi được bảo vệ.</p>
            <p style="font-size:11px; color:#aaa;">Đây là email tự động. Vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>

</html>
