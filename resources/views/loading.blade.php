<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f3f3f3;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #6e45e2, #88d3ce);
            background-size: 200% 200%;
            animation: gradientMove 5s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            font-size: 28px;
            color: white;
            font-weight: 600;
            text-align: center;
            position: relative;
            letter-spacing: 2px;
        }

        /* Progress bar container */
        .progress-container {
            position: absolute;
            bottom: 50px;
            width: 80%;
            max-width: 500px;
            height: 15px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }

        /* Progress bar */
        .progress-bar {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #ff6b6b, #f7c11f, #24c6dc);
            border-radius: 10px;
            animation: loadProgress 1.3s linear forwards; /* Thay đổi thời gian để phù hợp */
        }

        @keyframes loadProgress {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        /* Spinner */
        .spinner {
            width: 50px;
            height: 50px;
            border: 6px solid rgba(255, 255, 255, 0.2);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 70%;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Rùa chạy theo thanh tiến trình */
        .turtle {
            position: absolute;
            width: 120px;
            bottom: 75px; /* Đặt con rùa ở trên thanh tiến trình */
            animation: turtleRun 6s linear forwards; /* Thay đổi thời gian để chạy chậm hơn */
        }

        @keyframes turtleRun {
            0% { left: 0; } /* Bắt đầu từ đầu thanh tiến trình */
            100% { left: 100%; } /* Di chuyển đến cuối thanh tiến trình */
        }

        /* Responsive */
        @media (max-width: 768px) {
            h1 {
                font-size: 22px;
            }

            .spinner {
                width: 40px;
                height: 40px;
            }

            .progress-container {
                height: 12px;
                width: 90%;
            }

            .turtle {
                width: 50px;
                bottom: 60px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 18px;
            }

            .spinner {
                width: 30px;
                height: 30px;
            }

            .progress-container {
                height: 10px;
                width: 95%;
            }

            .turtle {
                width: 40px;
                bottom: 50px;
            }
        }
    </style>
    <script>
        // Tự động chuyển hướng sau khi thanh tiến trình hoàn tất
        setTimeout(function(){
            window.location.href = "{{ route('product_client.index') }}";
        }, 1200); // Chờ 2 giây trước khi chuyển hướng
    </script>
</head>
<body>
<div>
    <h1>Chúng tôi đang tải tất cả sản phẩm...</h1>
    <div class="progress-container">
        <div class="progress-bar"></div>
    </div>
</div>

<!-- Con rùa đuổi theo thanh tiến trình -->
<img src="https://storage.googleapis.com/garasales/run.gif" alt="Rùa đang chạy" class="turtle">

<div class="spinner"></div>
</body>
</html>
