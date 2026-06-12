<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Server Error | {{ config('app.name', 'T-Conn POS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 { width: 500px; height: 500px; background: #ef4444; top: -150px; left: -150px; animation-delay: 0s; }
        .blob-2 { width: 400px; height: 400px; background: #f97316; bottom: -100px; right: -100px; animation-delay: 3s; }
        .blob-3 { width: 300px; height: 300px; background: #eab308; top: 50%; left: 50%; animation-delay: 1.5s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }
        .blob-3 { animation: float3 8s ease-in-out infinite; animation-delay: 1.5s; }
        @keyframes float3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, calc(-50% - 30px)) scale(1.05); }
        }
        .card {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 3rem 4rem;
            max-width: 540px;
            width: 90%;
        }
        .error-code {
            font-size: 9rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #ef4444 0%, #f97316 50%, #eab308 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.05em;
            margin-bottom: 1rem;
            animation: pulse-text 3s ease-in-out infinite;
        }
        @keyframes pulse-text {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.85; }
        }
        .icon-wrap {
            width: 80px; height: 80px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon-wrap svg { width: 36px; height: 36px; color: #ef4444; }
        h1 {
            color: #f1f5f9;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }
        p {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-group { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: #fff;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
        }
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
        }
        .btn-secondary {
            background: rgba(255,255,255,0.07);
            color: #cbd5e1;
            border: 1px solid rgba(255,255,255,0.12);
        }
        .btn-secondary:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
            transform: translateY(-2px);
        }
        .divider {
            width: 60px; height: 3px;
            background: linear-gradient(90deg, #ef4444, #f97316);
            border-radius: 999px;
            margin: 1.25rem auto;
        }
        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.25);
            color: #fca5a5;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 1.5rem;
        }
        .dot { width: 6px; height: 6px; border-radius: 50%; background: #ef4444; animation: blink 1.5s infinite; }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.2; } }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="card">
        <div class="icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <div class="error-badge">
            <span class="dot"></span>
            Server Error
        </div>

        <div class="error-code">{{ $exception->getStatusCode() ?? 500 }}</div>
        <div class="divider"></div>

        <h1>Something Went Wrong</h1>
        <p>
            Our server encountered an unexpected error and could not complete your request.
            Our team has been notified. Please try again in a moment.
        </p>

        <div class="btn-group">
            <a href="javascript:location.reload()" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Try Again
            </a>
            <a href="{{ url('/') }}" class="btn btn-danger">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Return Home
            </a>
        </div>
    </div>
</body>
</html>
