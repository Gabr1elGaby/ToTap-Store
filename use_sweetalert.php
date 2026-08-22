<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

// 1. Remove the ugly HTML modal
$content = preg_replace('/<!-- Nickname Check Modal -->.*?<\/div>\s*<\/div>\s*<\/div>\s*<\/div>/s', '', $content);

// 2. Add SweetAlert2 and rewrite the JS logic
$oldJs = <<<JS
    <script>
        const form = document.getElementById('topup-form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah submit langsung
            
            // Cek apakah produk dan ID sudah diisi
            const formData = new FormData(form);
            if (!formData.get('product_id')) {
                alert('Pilih nominal top up terlebih dahulu!');
                return;
            }
            if (!formData.get('player_id')) {
                alert('Masukkan ID Anda!');
                return;
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Mengecek ID...';
            submitBtn.disabled = true;
            
            // Lakukan pengecekan nickname
            fetch('{{ route("topup.check-nickname", \$game->slug) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                if (data.result === false) {
                    alert('GAGAL: ' + (data.message || 'ID tidak ditemukan atau tidak valid!'));
                } else if (data.data) {
                    // Berhasil, tampilkan modal konfirmasi
                    document.getElementById('nickname-text').innerText = data.data; // VIP Reseller biasanya mengembalikan string nickname di data
                    document.getElementById('nickname-modal').classList.remove('hidden');
                } else {
                    // Fallback kalau API sukses tapi format beda
                    document.getElementById('nickname-text').innerText = 'Tidak dapat memuat nickname, tapi ID valid.';
                    document.getElementById('nickname-modal').classList.remove('hidden');
                }
            })
            .catch(err => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                alert('Terjadi kesalahan saat mengecek ID.');
                console.error(err);
            });
        });
        
        function closeModal() {
            document.getElementById('nickname-modal').classList.add('hidden');
        }
        
        function submitForm() {
            // Bypass event listener dan submit langsung
            form.submit();
        }
    </script>
JS;

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
                
                if (data.result === false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ID Tidak Ditemukan!',
                        text: data.message || 'Silakan periksa kembali Player ID / Zone ID Anda.'
                    });
                } else {
                    // Coba ektrak nickname dari berbagai kemungkinan response
                    let nickname = 'Nama tidak diketahui (Tapi ID Valid)';
                    if (typeof data.data === 'string') nickname = data.data;
                    else if (data.nickname) nickname = data.nickname;
                    else if (data.name) nickname = data.name;
                    else if (data.data && data.data.nickname) nickname = data.data.nickname;
                    else if (data.data && data.data.name) nickname = data.data.name;
                    else if (data.message && data.message !== 'Success') nickname = data.message;
                    
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
                            form.submit(); // Lanjutkan proses form
                        }
                    });
                }
            })
            .catch(err => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error Server', text: 'Terjadi kesalahan saat mengecek ID.' });
                console.error(err);
            });
        });
    </script>
JS;

$content = str_replace($oldJs, $newJs, $content);
file_put_contents($file, $content);
echo "SweetAlert2 injected.\n";
