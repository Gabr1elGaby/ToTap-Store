<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$oldCodePattern = '/async checkout\(\) \{\s*if \(\!this\.data\.name/ms';

$newCode = <<<JS
async checkout() {
                    @guest
                    if(typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Akses Terbatas!',
                            text: 'Silakan Login atau Mendaftar terlebih dahulu untuk menyimpan dan mengunduh CV Anda.',
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
                    } else {
                        alert('Silakan login terlebih dahulu!');
                        window.location.href = "{{ route('login') }}";
                    }
                    return;
                    @endguest

                    if (!this.data.name
JS;

$content = preg_replace($oldCodePattern, $newCode, $content);
file_put_contents($file, $content);
echo "SweetAlert added via regex.\n";
