<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Garasale by thanyk14') }}</title>
    <meta name="msapplication-TileColor" content="#ff6600"/>
    <meta name="theme-color" content="#ff6600"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

    <!-- Meta description for search engines -->
    <meta name="description" content="Garasale - A platform to buy and sell items easily and securely. Developed by thanyk14, Garasale provides an intuitive and reliable marketplace experience."/>

    <!-- Canonical URL for SEO -->
    <meta name="canonical" content="https://garasale.com">

    <!-- Twitter Cards Metadata -->
    <meta name="twitter:image:src" content="https://garasales-1027992830683.asia-east2.run.app/iu.jpg">
    <meta name="twitter:site" content="@garasale_official">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Garasale - Buy & Sell with Ease | Developed by thanyk14">
    <meta name="twitter:description" content="Garasale is a secure marketplace platform where you can buy and sell items quickly and safely. Join us today!">

    <!-- Open Graph Metadata for Facebook and other social media -->
    <meta property="og:image" content="https://garasales-1027992830683.asia-east2.run.app/iu.jpg">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="640">
    <meta property="og:site_name" content="Garasale">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Garasale - Buy & Sell with Ease | Developed by thanyk14">
    <meta property="og:url" content="https://garasales-1027992830683.asia-east2.run.app/">
    <meta property="og:description" content="Garasale, developed by thanyk14, offers a smooth and secure platform for users to buy and sell products. Enjoy a reliable marketplace experience today!">
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
    <h1>Chúng tôi đang tải tài nguyên...</h1>
    <div class="progress-container">
        <div class="progress-bar"></div>
    </div>
</div>

<!-- Con rùa đuổi theo thanh tiến trình -->
<img src="https://storage.googleapis.com/garasales/run.gif" alt="Rùa đang chạy" class="turtle">

<div class="spinner"></div>
</body>
</html>
