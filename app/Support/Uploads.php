<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Menyimpan file upload langsung di dalam folder public/uploads sehingga
 * gambar selalu bisa diakses tanpa perlu `php artisan storage:link`.
 * Ini membuat fitur gambar tetap berfungsi di hosting apa pun.
 */
class Uploads
{
    public const DIRECTORY = 'uploads';

    /**
     * Simpan file ke public/uploads/{folder} dan kembalikan path relatif.
     */
    public static function store(UploadedFile $file, string $folder): string
    {
        $folder = trim($folder, '/');

        $name = Str::random(40).'.'.strtolower($file->getClientOriginalExtension() ?: $file->extension());

        $destination = public_path(self::DIRECTORY.'/'.$folder);

        if (! is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $file->move($destination, $name);

        return $folder.'/'.$name;
    }

    /**
     * Ubah path relatif menjadi URL yang bisa dibuka browser.
     * Memakai URL relatif terhadap root agar tidak bergantung pada APP_URL.
     */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return '/'.self::DIRECTORY.'/'.ltrim($path, '/');
    }

    /**
     * Hapus file dari public/uploads.
     */
    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $full = public_path(self::DIRECTORY.'/'.ltrim($path, '/'));

        if (is_file($full)) {
            @unlink($full);
        }
    }
}
