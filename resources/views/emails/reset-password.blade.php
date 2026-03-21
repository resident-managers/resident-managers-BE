<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #1a73e8;
            color: #ffffff;
            padding: 24px 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .body {
            padding: 32px;
            color: #333333;
            line-height: 1.6;
        }
        .token-box {
            background-color: #f0f4ff;
            border: 2px dashed #1a73e8;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 24px 0;
        }
        .token-box p {
            margin: 0 0 12px 0;
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .token-input {
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            font-weight: bold;
            color: #1a73e8;
            background-color: #ffffff;
            border: 2px solid #1a73e8;
            border-radius: 6px;
            padding: 10px 14px;
            text-align: center;
            letter-spacing: 2px;
            cursor: pointer;
            outline: none;
            font-family: monospace;
        }
        .token-input:focus {
            background-color: #e8f0fe;
        }
        .copy-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 24px;
            background-color: #1a73e8;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        .notice {
            background-color: #fff8e1;
            border-left: 4px solid #fbc02d;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 14px;
            color: #555;
            margin-top: 16px;
        }
        .footer {
            padding: 20px 32px;
            background-color: #f9f9f9;
            font-size: 12px;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Đặt lại mật khẩu</h1>
        </div>
        <div class="body">
            <p>Xin chào,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản gắn với địa chỉ email <strong>{{ $email }}</strong>.</p>
            <p>Vui lòng sử dụng mã token dưới đây trong ứng dụng để đặt lại mật khẩu của bạn:</p>

            <div class="token-box">
                <p>Mã xác nhận (Token)</p>
                <input
                    id="token-val"
                    type="text"
                    class="token-input"
                    value="{{ $token }}"
                    readonly
                    onclick="this.select();"
                >
                <button id="copy-btn" class="copy-btn" onclick="
                    var val = document.getElementById('token-val').value;
                    var btn = document.getElementById('copy-btn');
                    function done() {
                        btn.textContent = '✓ Đã sao chép!';
                        btn.style.backgroundColor = '#2e7d32';
                        setTimeout(function(){ btn.textContent = 'Sao chép Token'; btn.style.backgroundColor = '#1a73e8'; }, 2000);
                    }
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(val).then(done).catch(function(){
                            var i = document.getElementById('token-val');
                            i.select(); i.setSelectionRange(0, 99999);
                            document.execCommand('copy'); done();
                        });
                    } else {
                        var i = document.getElementById('token-val');
                        i.select(); i.setSelectionRange(0, 99999);
                        document.execCommand('copy'); done();
                    }
                ">Sao chép Token</button>
            </div>

            <div class="notice">
                <strong>Lưu ý:</strong> Mã token này sẽ hết hạn sau <strong>60 phút</strong>.
                Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.
            </div>

            <p style="margin-top: 24px;">Trân trọng,<br><strong>Hệ thống Quản lý Dân cư</strong></p>
        </div>
        <div class="footer">
            Đây là email tự động, vui lòng không trả lời email này.
        </div>
    </div>
</body>
</html>
