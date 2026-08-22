<?php
$file = 'resources/views/topup/checkout.blade.php';
$content = file_get_contents($file);

$oldScript = <<<HTML
    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ \$transaction->snap_token }}', {
HTML;

$newScript = <<<HTML
    <script>
        function openMidtrans() {
            snap.pay('{{ \$transaction->snap_token }}', {
                onSuccess: function(result){
                    document.getElementById('pay-button').innerHTML = 'Memverifikasi Pembayaran...';
                    document.getElementById('pay-button').disabled = true;
                    
                    fetch('{{ route("topup.checkout.verify", \$transaction->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            alert('Pembayaran Berhasil! Diamond sedang dikirim ke akun Anda.');
                            window.location.href = '/';
                        } else {
                            alert('Menunggu pembayaran diselesaikan...');
                        }
                    });
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!"); console.log(result);
                },
                onError: function(result){
                    alert("Pembayaran gagal!"); console.log(result);
                },
                onClose: function(){
                    console.log('User menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        }
        
        // Buka popup otomatis saat halaman dimuat!
        window.onload = function() {
            openMidtrans();
        };

        // Jika user menutup popup dan ingin membuka lagi
        document.getElementById('pay-button').onclick = function(){
            openMidtrans();
        };
    </script>
HTML;

// Remove the old script entirely and replace it
$content = preg_replace('/<script>\s*document\.getElementById\(\'pay-button\'\).*?<\/script>/s', $newScript, $content);
file_put_contents($file, $content);
echo "Auto-popup script injected.\n";
