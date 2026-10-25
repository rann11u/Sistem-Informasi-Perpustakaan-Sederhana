
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sistem Informasi Perpustakaan')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f9;
            color: #263238;
        }

        .header {
            background-color: #174a38;
            color: white;
            padding: 20px 5%;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .header p {
            margin: 6px 0 0;
            font-size: 14px;
            color: #dcebe3;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 28px auto;
        }

        .navigation {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .navigation a {
            display: inline-block;
            padding: 10px 14px;
            background-color: white;
            color: #174a38;
            text-decoration: none;
            border: 1px solid #dce3df;
            border-radius: 6px;
        }

        .navigation a:hover {
            background-color: #e5f1eb;
        }

        .content {
            min-height: 300px;
            padding: 24px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .alert {
            padding: 12px 16px;
            margin-bottom: 16px;
            border-radius: 6px;
        }

        .alert-success {
            background-color: #e4f6e9;
            color: #176534;
        }

        .alert-error {
            background-color: #fde8e7;
            color: #a12222;
        }

        .footer {
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .container {
                width: 94%;
            }

            .content {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <h1>Sistem Informasi Perpustakaan</h1>
        <p>Pengelolaan buku, anggota, dan transaksi perpustakaan</p>
    </header>

    <main class="container">

        @include('partials.navbar')

        @include('partials.flash-message')

        <section class="content">
            @yield('content')
        </section>

    </main>

    <footer class="footer">
        &copy; {{ date('Y') }} Sistem Informasi Perpustakaan Sederhana
    </footer>

</body>
</html>