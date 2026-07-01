<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Storage File Serving
|--------------------------------------------------------------------------
| Serves files from storage/app/public without needing a symlink.
| This is needed on hosts (e.g. Hostinger) where exec() and symlink()
| are disabled, making `php artisan storage:link` fail.
*/
Route::get('/storage-file/{path}', function (string $path) {
    // Prevent path traversal attacks
    $path = str_replace(['..', "\0"], '', $path);

    $fullPath = storage_path('app/public/' . $path);

    if (! file_exists($fullPath) || ! is_file($fullPath)) {
        abort(404);
    }

    $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

    return response()->file($fullPath, [
        'Content-Type'        => $mimeType,
        'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
    ]);
})->where('path', '.+');
