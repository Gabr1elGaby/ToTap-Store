<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldCode = <<<JS
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
JS;

$newCode = <<<JS
            }).then((result) => {
                if (result.isConfirmed) {
                    window.dispatchEvent(new CustomEvent('open-login'));
                }
            });
JS;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "topup updated.\n";
