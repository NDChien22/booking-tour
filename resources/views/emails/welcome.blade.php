<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến TCTravel</title>
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
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 20px;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }

        .greeting strong {
            color: #667eea;
        }

        .message {
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #555;
        }

        .features {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .features h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .features ul {
            list-style: none;
            padding-left: 0;
        }

        .features li {
            padding: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .features li:before {
            content: "✓ ";
            color: #667eea;
            font-weight: bold;
            margin-right: 8px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
            font-size: 14px;
        }

        .cta-button:hover {
            opacity: 0.9;
            text-decoration: none;
            color: #ffffff;
        }

        .info-box {
            background-color: #e8f4fd;
            border-left: 4px solid #0066cc;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            font-size: 12px;
            color: #888;
            margin: 5px 0;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .contact-info {
            font-size: 13px;
            color: #666;
            margin-top: 15px;
        }

        .contact-info p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌍 TCTravel</h1>
            <p>Khám phá thế giới cùng chúng tôi</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Chào mừng, <strong>{{ $user->username }}</strong>! 👋
            </div>

            <div class="message">
                <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>TCTravel</strong>. Chúng tôi rất vui được chào đón bạn
                    gia nhập cộng đồng du lịch của chúng tôi!</p>
                <p style="margin-top: 10px;">Để hoàn tất quá trình đăng ký, vui lòng xác thực địa chỉ email của bạn bằng cách nhấp vào nút bên dưới. <strong style="color: #dc3545;">Link xác thực chỉ có hiệu lực trong vòng 15 phút.</strong></p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" class="cta-button">Xác thực Email</a>
            </div>

            <div class="info-box">
                <strong>⏰ Lưu ý quan trọng:</strong>
                <p style="margin-top: 8px;">
                    • Link xác thực sẽ hết hạn sau <strong>15 phút</strong> kể từ khi email này được gửi.<br>
                    • Nếu link hết hạn, bạn có thể yêu cầu gửi lại email xác thực mới.<br>
                    • Sau khi xác thực thành công, bạn có thể đăng nhập và sử dụng đầy đủ các tính năng của TCTravel.
                </p>
            </div>

            <div class="features">
                <h3>Sau khi xác thực, bạn có thể:</h3>
                <ul>
                    <li>Khám phá hàng trăm tour du lịch hấp dẫn</li>
                    <li>Đặt tour yêu thích của bạn chỉ với vài cách nhấp</li>
                    <li>Quản lý các đơn đặt tour của bạn dễ dàng</li>
                    <li>Nhận ưu đãi và khuyến mãi độc quyền</li>
                    <li>Kết nối với cộng đồng du lịch thế giới</li>
                </ul>
            </div>

            <div class="info-box" style="background-color: #fff3cd; border-left-color: #ffc107;">
                <strong>🔐 Bảo mật tài khoản:</strong>
                <p style="margin-top: 8px;">
                    Nếu bạn không thực hiện đăng ký tài khoản này, vui lòng bỏ qua email này. Tài khoản sẽ không được kích hoạt nếu không xác thực email.
                </p>
            </div>

            <div style="display: none;">
                <a href="{{ route('dashboard') }}" class="cta-button">Bấm vào đây để xác thực</a>
            </div>

            <div class="info-box">
                <strong>📧 Thông tin tài khoản của bạn:</strong>
                <p style="margin-top: 8px;">
                    <strong>Email:</strong> {{ $user->email }}<br>
                    <strong>Tên đăng nhập:</strong> {{ $user->username }}<br>
                    <strong>Số điện thoại:</strong> {{ $user->phone_number ?? 'Chưa cập nhật' }}
                </p>
            </div>

            <div class="message">
                <p>Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi. Chúng tôi luôn sẵn sàng hỗ trợ
                    bạn!</p>
            </div>

            <div class="divider"></div>

            <div class="message" style="text-align: center; font-style: italic; color: #888;">
                Hãy bắt đầu hành trình du lịch của bạn ngay hôm nay! 🚀
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>TCTravel - Nền tảng du lịch trực tuyến hàng đầu</strong></p>
            <p>© {{ date('Y') }} TCTravel. Tất cả quyền lợi được bảo vệ.</p>

            <div class="contact-info">
                <p>📧 <a href="mailto:support@tctravel.com">support@tctravel.com</a></p>
                <p>📞 1900 xxxx | 🕐 Hỗ trợ 24/7</p>
            </div>

            <p style="margin-top: 15px; font-size: 11px; color: #aaa;">
                Đây là email tự động. Vui lòng không trả lời trực tiếp vào email này.
            </p>
        </div>
    </div>
</body>

</html>
