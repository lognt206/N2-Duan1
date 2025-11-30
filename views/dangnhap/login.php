<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
  
    <style>
       body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-image: url('uploads/login.jpg');
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

form {
    background-color: #fff;
    border-radius: 20px;
    padding: 40px 50px;
    width: 380px;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
    animation: fadeIn 0.6s ease-in-out;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

table {
    width: 100%;
}

table td {
    padding: 10px 0;
    font-weight: bold;
    color: #555;
}

input, select {
    width: 100%;
    height: 38px;
    padding: 5px 12px;
    border: 1px solid #ccc;
    border-radius: 20px;
    outline: none;
    transition: border-color 0.3s ease;
}

input:focus, select:focus {
    border-color: #4facfe;
}

button {
    width: 100%;
    background: #4facfe;
    border: none;
    color: white;
    padding: 10px 0;
    border-radius: 20px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background 0.3s ease, transform 0.2s ease;
    margin-top: 10px;
}

button:hover {
    background: #00c6ff;
    transform: scale(1.03);
}

a {
    display: block;
    margin-top: 15px;
    text-align: center;
    text-decoration: none;
    color: #4facfe;
    font-weight: 500;
    transition: color 0.3s ease;
}

a:hover {
    color: #00c6ff;
}

span {
    color: red;
    display: block;
    text-align: center;
    margin-top: 10px;
}

/* Hiệu ứng mượt khi load */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

    </style>
</head>
<body>
    <form method="POST">
    <h2>Đăng nhập</h2>
    <table>
        <tr>
            <td>Tên đăng nhập:</td>
            <td><input type="email" name="email" required placeholder="Nhập email"></td>
        </tr>

        <tr>
            <td>Mật khẩu:</td>
            <td><input type="password" name="password" required placeholder="Nhập mật khẩu"></td>
        </tr>
    </table><br>

    <button type="submit" name="dangnhap">Đăng nhập</button>
    <span><?= $loi ?? '' ?></span>
    <!-- <a href="?act=dangky">Đăng ký tài khoản</a> -->
</form>
</body>
</html>
