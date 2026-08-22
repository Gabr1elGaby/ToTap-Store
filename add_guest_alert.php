<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldJs = <<<JS
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah submit langsung
            
            const formData = new FormData(form);
JS;

$newJs = <<<JS
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah submit langsung
            
            @guest
            Swal.fire({
                icon: 'warning',
                title: 'Akses Terbatas!',
                text: 'Silakan Login atau Mendaftar terlebih dahulu untuk melanjutkan pembelian.',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
            return;
            @endguest
            
            const formData = new FormData(form);
JS;

$content = str_replace($oldJs, $newJs, $content);
file_put_contents($file, $content);
echo "SweetAlert for guests added to TopUp.\n";
