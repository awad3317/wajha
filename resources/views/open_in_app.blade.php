<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فتح تطبيق وجّه</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #4a90e2, #50e3c2);
            color: #fff;
            text-align: center;
        }
        .container {
            background: rgba(0,0,0,0.3);
            padding: 40px 30px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            max-width: 400px;
            width: 90%;
        }
        img.logo {
            width: 100px;
            margin-bottom: 20px;
                border-radius: 50px;
        }
        h1 {
            margin-bottom: 10px;
            font-size: 1.8em;
        }
        p {
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 1em;
            font-weight: bold;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .btn-open {
            background: #ffd700;
            color: #000;
        }
        .btn-open:hover {
            background: #ffbf00;
        }
        .btn-download {
            background: #fff;
            color: #4a90e2;
        }
        .btn-download:hover {
            background: #e6e6e6;
        }
        .loader {
            margin: 20px auto 0;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('img/wjahh.jpg') }}" alt="Wajha Logo" class="logo ">
        <h1>مرحباً بك في تطبيق وجّهه</h1>
        <p>جارٍ فتح التطبيق... إذا لم يفتح تلقائياً، يمكنك الضغط على أحد الأزرار أدناه.</p>
        
        <a id="openApp" class="btn btn-open">فتح التطبيق</a>
        <a href="{{ $fallback }}" class="btn btn-download">تحميل التطبيق</a>
        
        <div class="loader"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const scheme = "{{ $scheme }}";
            const openBtn = document.getElementById("openApp");

            // حاول فتح التطبيق تلقائياً بعد ثانية واحدة
            setTimeout(() => {
                window.location = scheme;
            }, 1000);

            // زر فتح التطبيق يفتح التطبيق مباشرة
            openBtn.addEventListener("click", () => {
                window.location = scheme;
            });
        });
    </script>
</body>
</html>
