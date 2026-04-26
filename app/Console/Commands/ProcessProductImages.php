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
        $files = collect(scandir($directory))
            ->filter(fn ($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $extensions))
            ->values();

        if ($files->isEmpty()) {
            $this->warn('No se encontraron imágenes en el directorio.');
            return self::SUCCESS;
        }

        $this->info("Procesando {$files->count()} imagen(es) en: {$directory}");
        $dryRun && $this->warn('Modo dry-run: no se modificará ningún archivo.');

        $manager = new ImageManager(Driver::class);
        $bar     = $this->output->createProgressBar($files->count());
        $bar->start();

        foreach ($files as $filename) {
            $path = $directory . '/' . $filename;

            try {
                $image  = $manager->decode($path);
                $w      = $image->width();
                $h      = $image->height();

                // Lienzo negro del mismo tamaño
                $canvas = $manager->createImage($w, $h);
                $canvas->fill('#000000');

                // Pegar imagen original encima centrada
                $canvas->insert($image, 0, 0, Alignment::CENTER);

                if (! $dryRun) {
                    $canvas->encode(new \Intervention\Image\Encoders\JpegEncoder($quality))->save($path);
                }
            } catch (\Throwable $e) {
                $this->newLine();
                $this->warn("Error en {$filename}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('¡Listo!');

        return self::SUCCESS;
    }
}
