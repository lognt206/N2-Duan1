<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            color: #ee1414ff;
            margin-top: 20px;
        }

        .container {
            width: 100%;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
            box-sizing: border-box;
        }

        .container img {
            width: 500px;
            height: 400px;
            object-fit: cover; /* Giữ đẹp không méo hình */
            border-radius: 10px;
            transition: 0.3s;
        }

        .container img:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <h1>TRANG ĐIỀU HƯỚNG</h1>

    <div class="container">
        <!-- Ảnh bên trái -->
        <a href="?act=header"> trang chủ 
            <img src="uploads/logo.png" alt="">
        </a>

        <!-- Ảnh bên phải -->
        <a href="?act=login">đăng nhập admin và hdv 
            <img src="uploads/logo.png" alt="">
        </a>
    </div>

</body>
</html>
