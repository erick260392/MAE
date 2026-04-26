<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Alignment;
use Intervention\Image\Drivers\Gd\Driver;

#[Signature('images:process {--path= : Directorio relativo a storage/app/public} {--quality=88 : Calidad JPEG (1-100)} {--dry-run : Solo muestra qué se procesaría sin modificar archivos}')]
#[Description('Procesa imágenes de productos: aplica fondo negro y optimiza calidad')]
class ProcessProductImages extends Command
{
    public function handle(): int
    {
        $relativePath = $this->option('path') ?? 'products';
        $quality      = (int) $this->option('quality');
        $dryRun       = $this->option('dry-run');

        $directory = storage_path('app/public/' . $relativePath);

        if (! is_dir($directory)) {
            $this->error("Directorio no encontrado: {$directory}");
            return self::FAILURE;
        }

        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $files = collect(
            iterator_to_array(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
                )
            )
        )
            ->filter(fn ($f) => in_array(strtolower($f->getExtension()), $extensions))
            ->map(fn ($f) => $f->getPathname())
            ->values();

        if ($files->isEmpty()) {
            $this->warn('No se encontraron imágenes en el directorio.');
            return self::SUCCESS;
        }

        $this->info("Procesando {$files->count()} imagen(es) en: {$directory}");
        $dryRun && $this->warn('Modo dry-run: no se modificará ningún archivo.');

        $manager  = new ImageManager(Driver::class);
        $total    = $files->count();
        $done     = 0;
        $errors   = 0;
        $start    = microtime(true);

        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(
            " %current%/%max% [%bar%] %percent:3s%%  ⏱ %elapsed:6s% / ~%estimated:-6s%  💾 %memory:6s%\n  📄 %message%"
        );
        $bar->setMessage('Iniciando...');
        $bar->start();

        foreach ($files as $path) {
            $filename = basename($path);
            $bar->setMessage($filename);

            try {
                $image  = $manager->decode($path);
                $w      = $image->width();
                $h      = $image->height();

                $canvas = $manager->createImage($w, $h);
                $canvas->fill('#000000');
                $canvas->insert($image, 0, 0, Alignment::CENTER);

                if (! $dryRun) {
                    $canvas->encode(new \Intervention\Image\Encoders\JpegEncoder($quality))->save($path);
                }

                $done++;
            } catch (\Throwable $e) {
                $errors++;
                $bar->setMessage("⚠ Error: {$filename}");
                $this->newLine(2);
                $this->warn("  {$filename}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->setMessage('¡Completado!');
        $bar->finish();

        $elapsed = round(microtime(true) - $start, 1);
        $this->newLine(2);
        $this->info("✅ {$done}/{$total} imágenes procesadas en {$elapsed}s" . ($errors ? " — ⚠ {$errors} errores" : ''));

        return self::SUCCESS;
    }
}
