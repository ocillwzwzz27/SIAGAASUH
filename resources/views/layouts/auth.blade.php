<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiagaAsuh — @yield('title', 'Masuk')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f5f3ff;
        }
        /* Left decorative panel */
        .auth-panel-left {
            display: none;
            width: 45%;
            background: linear-gradient(160deg, #5B1D92 0%, #7B2FBE 45%, #9333EA 80%, #A855F7 100%);
            position: relative;
            overflow: hidden;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 48px;
        }
        @media(min-width: 1024px) { .auth-panel-left { display: flex; } }
        .auth-panel-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .auth-panel-left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 240px; height: 240px;
            border-radius: 50%;
            background: rgba(255,165,0,0.12);
        }
        /* Right form panel */
        .auth-panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            overflow-y: auto;
        }
        .auth-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(123,47,190,0.12);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        .auth-card-header {
            background: linear-gradient(135deg, #5B1D92 0%, #7B2FBE 60%, #9333EA 100%);
            padding: 32px 32px 44px;
            text-align: center;
            position: relative;
        }
        .auth-card-header::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 32px;
            background: white;
            border-radius: 50% 50% 0 0 / 24px 24px 0 0;
        }
        .auth-card-body { padding: 8px 32px 32px; }
        input[type="email"], input[type="password"], input[type="text"], input[type="tel"],
        input[type="number"], input[type="date"], select, textarea {
            width: 100%; border: 1.5px solid #e5e7eb; border-radius: 12px;
            padding: 11px 16px; font-size: 0.875rem; font-family: 'Inter', sans-serif;
            outline: none; transition: all 0.2s; background: #fafafa; color: #1f2937;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #9333EA; box-shadow: 0 0 0 3px rgba(147,51,234,0.12);
            background: #fff;
        }
        .btn-purple {
            width: 100%; background: linear-gradient(135deg, #7B2FBE, #9333EA);
            color: white; padding: 13px; border-radius: 14px; font-weight: 700;
            font-size: 0.95rem; border: none; cursor: pointer; transition: all 0.25s;
            box-shadow: 0 4px 20px rgba(123,47,190,0.35);
            font-family: 'Inter', sans-serif;
        }
        .btn-purple:hover { transform: translateY(-2px); box-shadow: 0 6px 28px rgba(123,47,190,0.5); }
        .label-sm {
            display: block; font-size: 0.72rem; font-weight: 600; color: #6b7280;
            text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 0.85rem; pointer-events: none;
        }
        .input-wrap input { padding-left: 42px; }
        .divider {
            display: flex; align-items: center; gap: 12px; margin: 20px 0;
        }
        .divider-line { flex: 1; height: 1px; background: #f0e6ff; }
        .divider-text { font-size: 0.68rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .social-btn {
            border: 1.5px solid #ede9fe; background: #fafafa; padding: 10px;
            border-radius: 12px; cursor: pointer; transition: all 0.2s; font-size: 1rem;
        }
        .social-btn:hover { background: #f3e8ff; border-color: #c084fc; }
        .demo-box {
            margin-top: 16px; padding: 12px 14px; background: #faf5ff;
            border-radius: 14px; border: 1px solid #ede9fe;
        }
        .demo-btn {
            font-size: 0.7rem; background: white; border: 1px solid #c084fc;
            color: #7B2FBE; padding: 4px 10px; border-radius: 8px;
            cursor: pointer; font-weight: 600; font-family: 'Inter', sans-serif; transition: all 0.15s;
        }
        .demo-btn:hover { background: #7B2FBE; color: white; }
    </style>
    @stack('styles')
</head>
<body>
    {{-- LEFT DECORATIVE PANEL --}}
    <div class="auth-panel-left">
        <div style="text-align:center;position:relative;z-index:1;">
            <img src="/images/logo-full.png" alt="SiagaAsuh Logo" style="width:280px;filter:brightness(0) invert(1);opacity:0.95;margin-bottom:32px;">
            <h2 style="color:white;font-size:1.6rem;font-weight:800;line-height:1.3;">Platform Pengasuhan<br>Anak Terpercaya</h2>
            <p style="color:rgba(233,213,255,0.85);font-size:0.9rem;margin-top:12px;line-height:1.6;">Temukan pengasuh terverifikasi,<br>pantau kondisi anak secara real-time<br>dengan teknologi IoT Wearable.</p>

            <div style="margin-top:36px;display:flex;gap:24px;justify-content:center;">
                <div style="text-align:center;">
                    <div style="color:#F59E0B;font-size:1.8rem;font-weight:900;">100%</div>
                    <div style="color:rgba(255,255,255,0.7);font-size:0.72rem;margin-top:2px;">Terverifikasi</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.2);"></div>
                <div style="text-align:center;">
                    <div style="color:#F59E0B;font-size:1.8rem;font-weight:900;">24/7</div>
                    <div style="color:rgba(255,255,255,0.7);font-size:0.72rem;margin-top:2px;">Live Monitor</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.2);"></div>
                <div style="text-align:center;">
                    <div style="color:#F59E0B;font-size:1.8rem;font-weight:900;">IoT</div>
                    <div style="color:rgba(255,255,255,0.7);font-size:0.72rem;margin-top:2px;">Wearable</div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT FORM PANEL --}}
    <div class="auth-panel-right">
        @yield('content')
    </div>
</body>
</html>
