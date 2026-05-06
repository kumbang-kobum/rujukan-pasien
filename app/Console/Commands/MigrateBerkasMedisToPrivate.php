<?php

namespace App\Console\Commands;

use App\Models\BerkasMedis;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateBerkasMedisToPrivate extends Command
{
    protected $signature = 'berkas:migrate-private {--execute : Jalankan migrasi. Tanpa opsi ini hanya simulasi.}';

    protected $description = 'Pindahkan file berkas medis lama dari public disk ke private local disk.';

    public function handle(): int
    {
        $execute = (bool) $this->option('execute');
        $stats = [
            'dicek' => 0,
            'sudah_private' => 0,
            'akan_dipindah' => 0,
            'dipindah' => 0,
            'duplikat_public_dihapus' => 0,
            'duplikat_public_terdeteksi' => 0,
            'file_tidak_ditemukan' => 0,
            'gagal' => 0,
        ];

        if (!$execute) {
            $this->warn('Mode simulasi. Tambahkan --execute untuk benar-benar memindahkan file.');
        }

        BerkasMedis::query()
            ->whereNotNull('path')
            ->orderBy('id')
            ->chunkById(100, function ($berkasList) use ($execute, &$stats) {
                foreach ($berkasList as $berkas) {
                    $stats['dicek']++;

                    $path = $berkas->path;
                    $existsOnPrivate = Storage::disk('local')->exists($path);
                    $existsOnPublic = Storage::disk('public')->exists($path);

                    if ($existsOnPrivate) {
                        $stats['sudah_private']++;

                        if ($existsOnPublic) {
                            if ($execute) {
                                Storage::disk('public')->delete($path);
                                $stats['duplikat_public_dihapus']++;
                            } else {
                                $stats['duplikat_public_terdeteksi']++;
                            }
                        }

                        continue;
                    }

                    if (!$existsOnPublic) {
                        $stats['file_tidak_ditemukan']++;
                        $this->line("Tidak ditemukan: {$path}");
                        continue;
                    }

                    if (!$execute) {
                        $stats['akan_dipindah']++;
                        continue;
                    }

                    if ($this->copyPublicToPrivate($path)) {
                        Storage::disk('public')->delete($path);
                        $stats['dipindah']++;
                    } else {
                        $stats['gagal']++;
                        $this->error("Gagal memindahkan: {$path}");
                    }
                }
            });

        $this->table(['Status', 'Jumlah'], collect($stats)->map(
            fn ($value, $key) => [str_replace('_', ' ', $key), $value]
        )->values()->all());

        if (!$execute) {
            $this->info('Simulasi selesai. Jalankan: php artisan berkas:migrate-private --execute');
        }

        return self::SUCCESS;
    }

    private function copyPublicToPrivate(string $path): bool
    {
        $stream = Storage::disk('public')->readStream($path);

        if ($stream === false) {
            return false;
        }

        try {
            Storage::disk('local')->put($path, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return Storage::disk('local')->exists($path);
    }
}
