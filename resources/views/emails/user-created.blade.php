<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản đã được tạo</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background-color: #137fec; color: #ffffff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #333333; line-height: 1.6; }
        .credentials { background-color: #f0f4ff; border: 2px solid #137fec; border-radius: 8px; padding: 20px 24px; margin: 24px 0; }
        .credentials p { margin: 8px 0; font-size: 15px; }
        .credentials strong { color: #137fec; font-family: monospace; font-size: 16px; }
        .notice { background-color: #fff8e1; border-left: 4px solid #fbc02d; padding: 12px 16px; border-radius: 4px; font-size: 14px; color: #555; margin-top: 16px; }
        .footer { padding: 20px 32px; background-color: #f9f9f9; font-size: 12px; color: #999; text-align: center; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tài khoản của bạn đã được tạo</h1>
        </div>
        <div class="body">
            <p>Xin chào <strong>{{ $name }}</strong>,</p>
            <p>Tài khoản của bạn trên hệ thống <strong>Quản lý Dân cư</strong> đã được tạo. Dưới đây là thông tin đăng nhập:</p>

            <div class="credentials">
                <p>Email: <strong>{{ $email }}</strong></p>
                <p>Mật khẩu: <strong>{{ $password }}</strong></p>
            </div>

            <div class="notice">
                <strong>Lưu ý:</strong> Vui lòng đổi mật khẩu sau khi đăng nhập lần đầu để bảo mật tài khoản.
            </div>

            <p style="margin-top: 24px;">Trân trọng,<br><strong>Hệ thống Quản lý Dân cư</strong></p>
        </div>
        <div class="footer">
            Đây là email tự động, vui lòng không trả lời email này.
        </div>
    </div>
</body>
</html>
