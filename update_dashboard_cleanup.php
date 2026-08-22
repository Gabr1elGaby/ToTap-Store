<?php
$file = 'resources/views/admin/dashboard.blade.php';
$content = file_get_contents($file);

// 1. Remove Total and Status from thead of Pesanan Terbaru
$content = str_replace(
    "<th class=\"border-b py-2 px-4\">Total</th>\n                                  <th class=\"border-b py-2 px-4\">Status</th>",
    '',
    $content
);

// 2. Remove Total td
$content = str_replace(
    "<td class=\"border-b py-2 px-4\">Rp {{ number_format(\$order->amount, 0, ',', '.') }}\n}}</td>",
    '',
    $content
);

// Also try without newline
$content = str_replace(
    "<td class=\"border-b py-2 px-4\">Rp {{ number_format(\$order->amount, 0, ',', '.') }}\r\n}}</td>",
    '',
    $content
);

// 3. Remove Status td block
$pattern = '/<td class="border-b py-2 px-4">\s*@if\(\$order->payment_status === \'PAID\'\)[\s\S]*?@endif\s*<\/td>/';
$content = preg_replace($pattern, '', $content);

// 4. Fix BASIC badge visibility - use inline style instead of Tailwind arbitrary colors
$content = str_replace(
    "class=\"inline-block px-2 py-0.5 text-xs font-bold rounded {{ strtolower(\$sub->plan->name) === 'pro' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-700' }}\"",
    "style=\"display:inline-block; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700; background:{{ strtolower(\$sub->plan->name) === 'pro' ? '#FEF3C7' : '#DBEAFE' }}; color:{{ strtolower(\$sub->plan->name) === 'pro' ? '#92400E' : '#1D4ED8' }};\"",
    $content
);

file_put_contents($file, $content);
echo "Updated dashboard: removed Total/Status, fixed badge.\n";
