<?php
$file = 'resources/views/components/auth-modals.blade.php';
$content = file_get_contents($file);

// 1. Hapus @click.away agar tidak gampang tertutup tidak sengaja
$content = str_replace('@click.away="showLogin = false"', '', $content);
$content = str_replace('@click.away="showRegister = false"', '', $content);

// 2. Tambahkan penanganan error generic 500 di Login
$oldLoginFetch = <<<JS
                    fetch('{{ route('login') }}', {
                        method: 'POST', body: new FormData(\$event.target), headers: { 'Accept': 'application/json' }
                    }).then(async response => {
                        if (response.ok) { window.location.reload(); }
                        else if (response.status === 422) { errors = (await response.json()).errors; }
                        loading = false;
                    });
JS;
$newLoginFetch = <<<JS
                    fetch('{{ route('login') }}', {
                        method: 'POST', body: new FormData(\$event.target), headers: { 'Accept': 'application/json' }
                    }).then(async response => {
                        if (response.ok) { window.location.reload(); }
                        else if (response.status === 422) { errors = (await response.json()).errors; }
                        else { alert('Terjadi kesalahan server. Kode: ' + response.status); }
                        loading = false;
                    }).catch(error => {
                        alert('Gagal menghubungi server.');
                        loading = false;
                    });
JS;
$content = str_replace($oldLoginFetch, $newLoginFetch, $content);

// 3. Tambahkan penanganan error generic 500 di Register
$oldRegFetch = <<<JS
                    fetch('{{ route('register') }}', {
                        method: 'POST', body: new FormData(\$event.target), headers: { 'Accept': 'application/json' }
                    }).then(async response => {
                        if (response.ok) { 
                            let data = await response.json();
                            if (data.requires_otp) {
                                step = 'otp';
                                phone = data.phone;
                            } else {
                                window.location.reload(); 
                            }
                        }
                        else if (response.status === 422) { errors = (await response.json()).errors; }
                        loading = false;
                    });
JS;
$newRegFetch = <<<JS
                    fetch('{{ route('register') }}', {
                        method: 'POST', body: new FormData(\$event.target), headers: { 'Accept': 'application/json' }
                    }).then(async response => {
                        if (response.ok) { 
                            let data = await response.json();
                            if (data.requires_otp) {
                                step = 'otp';
                                phone = data.phone;
                            } else {
                                window.location.reload(); 
                            }
                        }
                        else if (response.status === 422) { errors = (await response.json()).errors; }
                        else { alert('Terjadi kesalahan server. Kode: ' + response.status); }
                        loading = false;
                    }).catch(error => {
                        alert('Gagal menghubungi server.');
                        loading = false;
                    });
JS;
$content = str_replace($oldRegFetch, $newRegFetch, $content);

file_put_contents($file, $content);
echo "Frontend Modals fixed.\n";
