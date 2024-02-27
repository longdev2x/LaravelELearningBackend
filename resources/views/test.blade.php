<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flutter and Laravel</title>
</head>
<body>

    {!! 
        // Lấy mật khẩu từ người dùng (ví dụ từ form đăng nhập)
        $passwordFromUser = 'admin';
        
        $hashedPassword = Illuminate\Support\Facades\Hash::make($passwordFromUser);
        echo $hashedPassword;
        echo "........";
        // Lấy mật khẩu đã được mã hóa từ cơ sở dữ liệu (ví dụ từ cơ sở dữ liệu)
        $hashedPasswordFromDatabase = '$2y$10$zmkFMb28cS1SqU7QhZDFM.9SUXXCNUDW16r1wJ8toQXLCL3RYGfhW';
        
        // So sánh mật khẩu từ người dùng với mật khẩu đã được mã hóa từ cơ sở dữ liệu
        if (Illuminate\Support\Facades\Hash::check($passwordFromUser, $hashedPasswordFromDatabase)) {
            // Mật khẩu hợp lệ, thực hiện hành động tiếp theo, ví dụ đăng nhập thành công
            echo "Đăng nhập thành công!";
        } else {
            // Mật khẩu không hợp lệ, xử lý lỗi, ví dụ thông báo "Sai mật khẩu"
            echo "Sai mật khẩu!";
        }
     !!}
</body>
</html>