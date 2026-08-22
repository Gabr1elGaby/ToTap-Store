<?php
$file = 'routes/web.php';
$content = file_get_contents($file);

$oldRoute = "Route::get('/software', function () { return view('software.index'); })->name('software.index');";
$newRoute = "Route::get('/software', function () {
    \$softwareProducts = \App\Models\Product::where('is_active', true)->with(['plans' => function(\$q) {
        \$q->where('is_active', true)->orderBy('price');
    }])->get();
    return view('software.index', compact('softwareProducts'));
})->name('software.index');";

$content = str_replace($oldRoute, $newRoute, $content);
file_put_contents($file, $content);
echo "Updated software route.\n";
