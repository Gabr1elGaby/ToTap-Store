<?php
$file = 'app/Http/Controllers/Api/WebhookController.php';
$content = file_get_contents($file);

// 1. Insert GetId for Store
$content = str_replace(
    "'subscription_ends_at' => \$sub->end_date,",
    "'subscription_ends_at' => \$sub->end_date,\n                                'user_limit' => \$order->plan->user_limit,",
    $content
);

// 2. Update existing Store
$content = str_replace(
    "'subscription_ends_at' => \$sub->end_date,",
    "'subscription_ends_at' => \$sub->end_date,\n                                        'user_limit' => \$order->plan->user_limit,",
    $content
);

file_put_contents($file, $content);
echo "Updated WebhookController.php\n";
