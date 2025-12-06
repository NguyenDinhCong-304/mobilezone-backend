<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận tài khoản</title>
</head>
<body>
    <h2>Chào {{ $user->name }},</h2>
    <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Website bán hàng</strong>.</p>
    <p>Vui lòng nhấn vào liên kết bên dưới để xác nhận email của bạn:</p>

    <p><a href="{{ $verifyUrl }}" style="background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">
        Xác nhận Email
    </a></p>

    <p>Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.</p>
</body>
</html>
