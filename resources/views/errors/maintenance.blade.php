<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Dalam Pemeliharaan - ToTap Store</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                        brand: ['Righteous', 'cursive'],
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.08); opacity: 1; }
        }
        @keyframes floatAnim {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(2deg); }
        }
        .animate-float {
            animation: floatAnim 4s ease-in-out infinite;
        }
        .animate-pulse-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(51, 65, 85, 0.6);
        }
    </style>
</head>
<body class="bg-[#0b0f19] text-slate-100 font-sans min-h-screen flex flex-col items-center justify-between p-4 sm:p-8 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">
    
    <!-- Background Ambient Glows -->
    <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[550px] bg-indigo-600/15 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="fixed bottom-10 right-10 w-[350px] h-[350px] bg-amber-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <!-- Header Logo -->
    <header class="w-full max-w-5xl flex items-center justify-between py-4 relative z-10">
        <a href="/" class="flex items-center gap-3 group">
            <img src="/images/logo-totap-v2.png" alt="ToTap Store Logo" class="h-10 w-auto object-contain drop-shadow-[0_0_15px_rgba(99,102,241,0.5)] transition group-hover:scale-105" onerror="this.src='https://totapstore.com/images/logo-totap-v2.png'">
            <span class="text-2xl font-bold tracking-widest text-white font-brand">TOTAP STORE</span>
        </a>
        
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold shadow-sm">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
            <span>Maintenance Mode</span>
        </div>
    </header>

    <!-- Main Maintenance Content -->
    <main class="w-full max-w-2xl my-auto py-10 relative z-10 text-center flex flex-col items-center">
        
        <!-- Floating Visual Icon -->
        <div class="relative mb-8">
            <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-3xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-amber-500 p-0.5 shadow-[0_0_50px_rgba(99,102,241,0.4)] animate-float">
                <div class="w-full h-full bg-slate-900 rounded-[22px] flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-indigo-500/10 animate-pulse"></div>
                    <i class="fas fa-tools text-4xl sm:text-5xl text-amber-400 drop-shadow-[0_0_20px_rgba(251,191,36,0.6)]"></i>
                </div>
            </div>
            
            <!-- Sparkles & Gear Accents -->
            <div class="absolute -top-2 -right-2 w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center text-sm shadow-md animate-bounce">
                <i class="fas fa-cog fa-spin" style="animation-duration: 6s;"></i>
            </div>
            <div class="absolute -bottom-2 -left-2 w-8 h-8 rounded-xl bg-indigo-500/20 border border-indigo-500/40 text-indigo-300 flex items-center justify-center text-xs shadow-md">
                <i class="fas fa-shield-alt"></i>
            </div>
        </div>

        <!-- Headline -->
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-4 leading-tight">
            Sistem Sedang Dalam <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-indigo-300 to-indigo-400">
                Peningkatan & Pemeliharaan
            </span>
        </h1>

        <!-- Explanation & Custom Admin Message -->
        <div class="glass-card rounded-2xl p-6 sm:p-8 w-full shadow-2xl mb-8 border border-slate-800/80 text-left sm:text-center">
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-4">
                {{ $message ?? 'Kami sedang melakukan pembaruan berkala untuk memberikan kecepatan, kenyamanan, dan fitur terbaik di seluruh layanan ToTap Store (Top Up Game, CV Builder, dan POS Kasir).' }}
            </p>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800/80 border border-slate-700 text-xs text-slate-400 font-medium">
                <i class="fas fa-shield-check text-emerald-400"></i>
                <span>Data transaksi & akun Anda tetap 100% aman tersimpan.</span>
            </div>
        </div>

        <!-- Action & Contact Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-4 w-full">
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20ToTap%20Store,%20saya%20ingin%20bertanya%20terkait%20pemeliharaan%20sistem." 
               target="_blank" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-7 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition transform active:scale-95">
                <i class="fab fa-whatsapp text-lg"></i>
                <span>Hubungi Bantuan / WhatsApp</span>
            </a>

            <button onclick="window.location.reload()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 shadow-md transition transform active:scale-95">
                <i class="fas fa-sync-alt"></i>
                <span>Cek Kembali Sekarang</span>
            </button>
        </div>

    </main>

    <!-- Footer & Super Admin Access -->
    <footer class="w-full max-w-5xl flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-slate-800/80 text-xs text-slate-500 relative z-10">
        <p>© {{ date('Y') }} ToTap Store. Seluruh Hak Cipta Dilindungi.</p>
        
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-1 text-[11px] text-slate-500">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Auto-cek status aktif</span>
            </span>
            <a href="/login" class="text-slate-500 hover:text-slate-300 transition flex items-center gap-1.5">
                <i class="fas fa-lock text-[10px]"></i>
                <span>Masuk Super Admin</span>
            </a>
        </div>
    </footer>

    <!-- Auto-Recovery Poller Script (Auto-Restores user back when maintenance is turned OFF) -->
    <script>
    (function() {
        let checking = false;
        async function checkRecovery() {
            if (checking) return;
            checking = true;
            try {
                const res = await fetch('/api/system-status?t=' + Date.now(), {
                    headers: { 'Accept': 'application/json' },
                    cache: 'no-store'
                });
                if (res.ok) {
                    const data = await res.json();
                    if (!data.maintenance) {
                        const toast = document.createElement('div');
                        toast.style.cssText = 'position:fixed;top:24px;left:50%;transform:translateX(-50%);background:#059669;color:#fff;padding:14px 28px;border-radius:16px;font-weight:bold;font-size:14px;box-shadow:0 10px 40px rgba(0,0,0,0.6);z-index:999999;display:flex;align-items:center;gap:10px;animation:scaleToast 0.4s ease;border:1px solid rgba(255,255,255,0.2);';
                        toast.innerHTML = '<span>✅ Website telah kembali ONLINE! Membuka halaman...</span><style>@keyframes scaleToast{from{transform:translate(-50%,-20px);opacity:0}to{transform:translate(-50%,0);opacity:1}}</style>';
                        document.body.appendChild(toast);
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 1000);
                    }
                }
            } catch(e) {}
            finally {
                checking = false;
            }
        }

        setInterval(checkRecovery, 3000);
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) checkRecovery();
        });
    })();
    </script>

</body>
</html>
