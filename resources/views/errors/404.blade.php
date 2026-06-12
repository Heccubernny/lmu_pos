<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page Not Found | {{ config('app.name', 'T-Conn POS') }}</title>
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
        /* Animated background blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 { width: 500px; height: 500px; background: #6366f1; top: -150px; left: -150px; animation-delay: 0s; }
        .blob-2 { width: 400px; height: 400px; background: #8b5cf6; bottom: -100px; right: -100px; animation-delay: 3s; }
        .blob-3 { width: 300px; height: 300px; background: #06b6d4; top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: 1.5s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }
        .blob-3 { animation: float3 8s ease-in-out infinite; animation-delay: 1.5s; }
        @keyframes float3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, calc(-50% - 30px)) scale(1.05); }
        }
        /* Content card */
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
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #06b6d4 100%);
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
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon-wrap svg { width: 36px; height: 36px; color: #6366f1; }
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
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
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
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border-radius: 999px;
            margin: 1.25rem auto;
        }
    </style>
</head>
<body>
    <!-- Background decorative blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="card">
        <div class="icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div class="error-code">404</div>
        <div class="divider"></div>

        <h1>Page Not Found</h1>
        <p>
            Oops! The page you're looking for doesn't exist or may have been moved.
            Let's get you back on track.
        </p>

        <div class="btn-group">
            <a href="javascript:history.back()" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </a>
            <a href="{{ url('/') }}" class="btn btn-primary">
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
