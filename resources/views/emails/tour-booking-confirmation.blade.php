<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đặt Tour</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .content {
            padding: 30px 25px;
        }

        .greeting {
            font-size: 18px;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #475569;
        }

        .info-value {
            color: #0f172a;
            text-align: right;
        }

        .total-price {
            background: #ecfdf5;
            border: 2px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .total-price .label {
            font-size: 14px;
            color: #059669;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .total-price .amount {
            font-size: 28px;
            color: #047857;
            font-weight: 700;
        }

        .note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .note p {
            margin: 5px 0;
            color: #92400e;
            font-size: 14px;
        }

        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .footer p {
            margin: 5px 0;
        }

        .button {
            display: inline-block;
            background: #3b82f6;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Xác Nhận Đặt Tour Thành Công</h1>
        </div>

        <div class="content">
            <p class="greeting">Xin chào <strong>{{ $user->name }}</strong>,</p>

            <p>Cảm ơn bạn đã đặt tour với chúng tôi! Đơn đặt tour của bạn đã được xác nhận thành công.</p>

            <div class="info-box">
                <h3 style="margin-top: 0; color: #0f172a;">📋 Thông Tin Tour</h3>

                <div class="info-row">
                    <span class="info-label">Tên Tour:</span>
                    <span class="info-value">{{ $tour->title }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Mã Đặt Tour:</span>
                    <span class="info-value">#{{ $booking->booking_id }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Ngày Đặt:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Ngày Khởi Hành:</span>
                    <span
                        class="info-value">{{ \Carbon\Carbon::parse($booking->departure_date)->format('d/m/Y') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Số Lượng Người:</span>
                    <span class="info-value">{{ $booking->number_of_people }} người</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Trạng Thái:</span>
                    <span class="info-value" style="color: #f59e0b; font-weight: 600;">Đang xử lý</span>
                </div>
            </div>

            <div class="total-price">
                <div class="label">Tổng Tiền</div>
                <div class="amount">{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</div>
            </div>

            <div class="note">
                <p><strong>📌 Lưu ý:</strong></p>
                <p>• Chúng tôi sẽ liên hệ với bạn trong vòng 24 giờ để xác nhận chi tiết và hướng dẫn thanh toán.</p>
                <p>• Vui lòng giữ liên lạc và kiểm tra email thường xuyên.</p>
                <p>• Nếu có thắc mắc, vui lòng liên hệ với chúng tôi qua email hoặc hotline.</p>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('profile') }}" class="button">Xem Hồ Sơ Của Tôi</a>
            </p>

            <p>Chúc bạn có một chuyến đi vui vẻ!</p>

            <p style="margin-top: 30px;">
                Trân trọng,<br>
                <strong>Đội ngũ Booking Tour</strong>
            </p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
            <p>© {{ date('Y') }} Booking Tour. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
