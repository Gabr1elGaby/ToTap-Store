<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$styles = <<<HTML
    <style>
        /* Fallback Tailwind JIT classes */
        @media (min-width: 768px) {
            .md\:hidden { display: none !important; }
            .md\:flex { display: flex !important; }
            .md\:h-screen { height: 100vh !important; }
            .md\:overflow-hidden { overflow: hidden !important; }
            .md\:overflow-y-auto { overflow-y: auto !important; }
            .md\:flex-row { flex-direction: row !important; }
            .md\:w-\[400px\] { width: 400px !important; }
            .md\:p-8 { padding: 2rem !important; }
        }
    </style>
</head>
HTML;

$content = str_replace('</head>', $styles, $content);

file_put_contents($file, $content);
echo "Added fallback CSS.\n";
