<?php
$file = 'resources/views/cv/templates/student.blade.php';
$content = file_get_contents($file);

// Add $volunteers variable
$org_regex = '/\$organizations = .*?;/s';
$new_org = <<<PHP
\$organizations = isset(\$userData['cv']['organizations']) && is_array(\$userData['cv']['organizations']) ? collect(\$userData['cv']['organizations'])->map(fn(\$i) => (object)\$i) : collect([]);
        \$volunteers = isset(\$userData['cv']['volunteers']) && is_array(\$userData['cv']['volunteers']) ? collect(\$userData['cv']['volunteers'])->map(fn(\$i) => (object)\$i) : collect([]);
        \$org_and_vol = \$organizations->concat(\$volunteers);
PHP;

$content = preg_replace($org_regex, $new_org, $content);

// Replace loop variable
$content = str_replace(
    '@if(count($organizations) > 0)',
    '@if(count($org_and_vol) > 0)',
    $content
);
$content = str_replace(
    '@foreach($organizations as $org)',
    '@foreach($org_and_vol as $org)',
    $content
);

file_put_contents($file, $content);
echo "Added volunteers to student template.\n";
