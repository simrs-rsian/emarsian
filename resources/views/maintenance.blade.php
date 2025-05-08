<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Menu Sedang Diperbaiki</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a;
            color: #fff;
            text-align: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden;
        }

        h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: blink 1.5s infinite;
            color: #ffcc00;
        }

        p {
            font-size: 1.2rem;
            color: #ccc;
        }

        .strip {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 20px;
            background: repeating-linear-gradient(
                -45deg,
                #ffcc00,
                #ffcc00 20px,
                #1a1a1a 20px,
                #1a1a1a 40px
            );
            animation: move 2s linear infinite;
        }

        .icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        @keyframes move {
            0% { background-position: 0 0; }
            100% { background-position: 80px 0; }
        }
    </style>
</head>
<body>
    <div class="strip"></div>
    <div class="icon">🚧</div>
    <h1>Maintenance</h1>
    <p>Mohon maaf, menu ini sedang dalam perbaikan.<br>Silakan kembali beberapa saat lagi.</p>
    <a href="javascript:history.back()" style="margin-top: 2rem; color: #ffcc00; text-decoration: underline;">Kembali ke Halaman Sebelumnya</a>
</body>
</html>
