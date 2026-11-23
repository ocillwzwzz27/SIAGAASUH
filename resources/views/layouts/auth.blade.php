<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiagaAsuh — @yield('title', 'Masuk')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #5B1D92 0%, #7B2FBE 50%, #9333EA 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        /* Background decorative circles */
        body::before {
            content: '';
            position: fixed;
            top: -120px; right: -120px;
            width: 350px; height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -100px; left: -100px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }
        .input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.2s;
        }
        .input:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168,85,247,0.15);
        }
        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #7B2FBE, #9333EA);
            color: white;
            padding: 13px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 20px rgba(123,47,190,0.4);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 25px rgba(123,47,190,0.5);
        }
        .card-auth {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            overflow: hidden;
            width: 100%;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
