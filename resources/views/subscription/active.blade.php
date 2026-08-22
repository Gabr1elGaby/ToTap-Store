<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Langganan Aktif - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="font-family:'Inter',sans-serif; background:#0f172a; min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:32px 16px; margin:0;">

    {{-- Card --}}
    <div style="width:100%; max-width:480px; background:#1e293b; border-radius:24px; border:1px solid #334155; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,0.5);">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg, #065f46, #047857); padding:40px 32px; text-align:center;">
            <div style="width:72px; height:72px; background:rgba(255,255,255,0.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                <svg style="width:36px;height:36px;color:white;" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 style="font-size:22px; font-weight:800; color:white; margin:0 0 4px;">Langganan Aktif!</h1>
            <p style="font-size:13px; color:rgba(255,255,255,0.7); margin:0;">Anda sudah berlangganan produk ini</p>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px;">

            @if(session('info'))
            <div style="background:rgba(59,130,246,0.1); border:1px solid rgba(59,130,246,0.3); color:#93c5fd; font-size:13px; border-radius:12px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                ℹ️ {{ session('info') }}
            </div>
            @endif

            {{-- Rows --}}
            <div style="display:flex; flex-direction:column; gap:10px;">

                <div style="display:flex; align-items:center; justify-content:space-between; background:#0f172a; border-radius:14px; padding:14px 18px;">
                    <span style="color:#94a3b8; font-size:13px;">Produk</span>
                    <span style="color:white; font-weight:600; font-size:14px;">{{ $activeSub->product->name }}</span>
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; background:#0f172a; border-radius:14px; padding:14px 18px;">
                    <span style="color:#94a3b8; font-size:13px;">Paket</span>
                    @php $planName = strtolower($activeSub->plan?->name ?? ''); @endphp
                    <span style="padding:4px 14px; border-radius:6px; font-size:11px; font-weight:800; letter-spacing:0.06em; background:{{ $planName === 'pro' ? '#FEF3C7' : '#DBEAFE' }}; color:{{ $planName === 'pro' ? '#78350F' : '#1E40AF' }};">
                        {{ strtoupper($activeSub->plan?->name ?? 'N/A') }}
                    </span>
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; background:#0f172a; border-radius:14px; padding:14px 18px;">
                    <span style="color:#94a3b8; font-size:13px;">Status</span>
                    <span style="display:flex; align-items:center; gap:6px; color:#34d399; font-weight:600; font-size:13px;">
                        <span style="width:8px; height:8px; background:#34d399; border-radius:50%; display:inline-block;"></span>
                        Aktif
                    </span>
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; background:#0f172a; border-radius:14px; padding:14px 18px;">
                    <span style="color:#94a3b8; font-size:13px;">Berakhir Pada</span>
                    <span style="color:white; font-weight:600; font-size:14px;">{{ $endDate }}</span>
                </div>

                {{-- Days remaining - highlighted --}}
                <div style="display:flex; align-items:center; justify-content:space-between; background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.25); border-radius:14px; padding:16px 18px;">
                    <span style="color:#6ee7b7; font-size:13px; font-weight:500;">⏳ Sisa Durasi</span>
                    <span style="color:#34d399; font-weight:800; font-size:22px;">{{ $daysLeft }} <small style="font-size:13px; font-weight:500;">hari lagi</small></span>
                </div>

            </div>

            {{-- Buttons --}}
            <div style="display:flex; flex-direction:column; gap:10px; margin-top:24px;">
                @if($demoUrl)
                <a href="{{ $demoUrl }}" target="_blank"
                   style="display:block; text-align:center; background:linear-gradient(135deg,#3b82f6,#6366f1); color:white; font-weight:700; font-size:14px; padding:14px; border-radius:14px; text-decoration:none; transition:opacity 0.2s;"
                   onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
                    ⚡ Buka Aplikasi Kasir Saya
                </a>
                @endif
                <a href="{{ url('/') }}"
                   style="display:block; text-align:center; background:transparent; border:1px solid #334155; color:#94a3b8; font-weight:500; font-size:13px; padding:13px; border-radius:14px; text-decoration:none;"
                   onmouseover="this.style.borderColor='#475569'; this.style.color='#cbd5e1';"
                   onmouseout="this.style.borderColor='#334155'; this.style.color='#94a3b8';">
                    ← Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>

    <p style="color:#334155; font-size:12px; margin-top:24px;">© {{ date('Y') }} ToTap Store. All rights reserved.</p>

</body>
</html>
