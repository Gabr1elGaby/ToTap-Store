<?php
$file = 'app/Http/Controllers/TopUpController.php';
$content = file_get_contents($file);

$oldBlock = <<<PHP
            \$product->_qty = \$qty;
PHP;

$newBlock = <<<PHP
            \$product->_qty = \$qty;
            
            // Buat nama pendek yang rapi (Hapus tulisan bonus dalam kurung agar rapi di HP)
            \$shortName = \$product->name;
            \$shortName = preg_replace('/\\(.*?\\)/', '', \$shortName); // Hapus semua dalam kurung
            \$shortName = str_ireplace('Diamonds', 'DM', \$shortName); // Ubah Diamonds jadi DM biar makin pendek
            \$shortName = str_ireplace('Diamond', 'DM', \$shortName);
            \$shortName = str_ireplace('Bonus', '', \$shortName);
            \$shortName = str_ireplace('First Top Up', '', \$shortName);
            \$shortName = preg_replace('/\\s+\\+\\s+/', ' ', \$shortName); // Hapus spasi + spasi
            // Jika nama hasil regex kosong, pakai qty saja
            if (trim(\$shortName) == '' || trim(\$shortName) == 'DM' || trim(\$shortName) == '+') {
                \$shortName = \$qty . ' DM';
            }
            \$product->_short_name = trim(\$shortName);
PHP;

$content = str_replace($oldBlock, $newBlock, $content);
file_put_contents($file, $content);
echo "TopUpController updated with short_name.\n";
