<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Simpan dan ambil file upload lewat disk Storage default.
 *
 * - Lokal / hosting biasa : disk "public" (storage/app/public) + storage:link.
 * - Laravel Cloud          : disk S3 / object storage di-inject otomatis oleh Cloud
 *   (bersifat persisten, tidak hilang saat redeploy).
 */
class Uploads
{
    /**
     * Simpan file ke disk default dan kembalikan path relatif.
     */
    public static function store(UploadedFile $file, string $folder): string
    {
        return Storage::disk()->putFile(trim($folder, '/'), $file);
    }

    /**
     * Ubah path relatif menjadi URL yang bisa dibuka browser.
     */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk()->url($path);
    }

    /**
     * Hapus file dari disk default.
     */
    public static function delete(?string $path): void
    {
        if ($path) {
            Storage::disk()->delete($path);
        }
    }
}