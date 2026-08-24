<?php
$ctrl = file_get_contents(__DIR__ . "/app/Http/Controllers/TopupController.php");
echo "<h2>TopupController.php on Server:</h2><pre>" . htmlspecialchars(substr($ctrl, 0, 1500)) . "</pre>";
