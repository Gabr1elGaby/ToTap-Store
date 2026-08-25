<!-- Real-time Live System Heartbeat & Auto-Update Listener -->
@if(!request()->is('admin*'))
<script>
(function() {
    let checking = false;
    async function checkSystemHeartbeat() {
        if (checking) return;
        checking = true;
        try {
            const res = await fetch('/api/system-status?t=' + Date.now(), {
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });
            if (res.ok) {
                const data = await res.json();
                if (data.maintenance && !data.is_admin) {
                    if (!document.getElementById('maint-live-overlay')) {
                        const overlay = document.createElement('div');
                        overlay.id = 'maint-live-overlay';
                        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(11,15,25,0.96);z-index:99999999;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#fff;font-family:sans-serif;backdrop-filter:blur(10px);transition:all 0.3s ease;';
                        overlay.innerHTML = `
                            <div style="text-align:center;max-width:480px;padding:30px;animation:scaleIn 0.3s ease;">
                                <div style="width:72px;height:72px;margin:0 auto 20px;border-radius:22px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.4);display:flex;align-items:center;justify-content:center;font-size:30px;color:#fbbf24;box-shadow:0 0 30px rgba(245,158,11,0.2);">
                                    🛠️
                                </div>
                                <h2 style="font-size:22px;font-weight:800;margin-bottom:10px;color:#ffffff;letter-spacing:-0.5px;">Sistem Sedang Masuk Mode Maintenance</h2>
                                <p style="font-size:14px;color:#94a3b8;line-height:1.6;margin-bottom:24px;">Admin sedang mengaktifkan pemeliharaan sistem. Anda akan otomatis dialihkan ke halaman maintenance...</p>
                                <div style="display:inline-block;width:32px;height:32px;border:3px solid rgba(255,255,255,0.15);border-top-color:#6366f1;border-radius:50%;animation:maintSpin 0.8s linear infinite;"></div>
                                <style>
                                    @keyframes maintSpin{to{transform:rotate(360deg)}}
                                    @keyframes scaleIn{from{transform:scale(0.9);opacity:0}to{transform:scale(1);opacity:1}}
                                </style>
                            </div>
                        `;
                        document.body.appendChild(overlay);
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 1000);
                    }
                }
            } else if (res.status === 503) {
                window.location.href = '/';
            }
        } catch(e) {}
        finally {
            checking = false;
        }
    }

    // Auto-poll heartbeat every 3.5 seconds
    setInterval(checkSystemHeartbeat, 3500);
    
    // Auto-check immediately on tab focus / visibility
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) checkSystemHeartbeat();
    });
})();
</script>
@endif
