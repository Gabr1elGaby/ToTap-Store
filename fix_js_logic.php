<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$scriptStart = strpos($content, '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>');
if ($scriptStart !== false) {
    $newJs = <<<JS
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const form = document.getElementById('topup-form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah submit langsung
            
            const formData = new FormData(form);
            if (!formData.get('product_id')) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih nominal top up terlebih dahulu!' });
                return;
            }
            if (!formData.get('player_id')) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Masukkan ID Anda terlebih dahulu!' });
                return;
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Mengecek ID... <i class="fas fa-spinner fa-spin ml-2"></i>';
            submitBtn.disabled = true;
            
            fetch('{{ route("topup.check-nickname", \$game->slug) }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // VIP Reseller SUCCESS selalu result: true
                if (data.result === true) {
                    let nickname = 'Nama tidak diketahui (Tapi ID Valid)';
                    if (typeof data.data === 'string') nickname = data.data;
                    else if (data.nickname) nickname = data.nickname;
                    else if (data.name) nickname = data.name;
                    else if (data.data && data.data.nickname) nickname = data.data.nickname;
                    else if (data.data && data.data.name) nickname = data.data.name;
                    
                    Swal.fire({
                        title: 'Konfirmasi Nickname',
                        html: `Halo <strong>\${nickname}</strong>!<br>Apakah nama ini sudah benar?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjut Bayar!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    // Jika result false atau error dari Laravel Exception
                    let errorMsg = 'Silakan periksa kembali Player ID / Zone ID Anda.';
                    if (data.message && data.message.includes('cURL')) {
                        errorMsg = 'Koneksi ke server pusat sedang gangguan (Timeout). Silakan coba lagi.';
                    } else if (data.message) {
                        errorMsg = data.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'ID Tidak Valid atau Gangguan Server!',
                        text: errorMsg
                    });
                }
            })
            .catch(err => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error Server', text: 'Terjadi kesalahan internal saat mengecek ID.' });
                console.error(err);
            });
        });
    </script>
</x-app-layout>
JS;

    $content = substr_replace($content, $newJs, $scriptStart);
    file_put_contents($file, $content);
    echo "JS fixed.\n";
} else {
    echo "Marker not found!\n";
}
