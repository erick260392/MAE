<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // O usa ImageManagerStatic si prefieres
use File;
use Illuminate\Support\Str;

class ProcessProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:product-images {--quality=90}'; // Opcional: --quality=X para definir la calidad
    // protected $signature = 'process:product-images {--output-dir=} {--quality=90}'; // Si quieres definir la carpeta de salida

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process product images to have a black background and improved quality.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $imagePath = 'public/images/storage/products';
        $outputDir = 'public/images/processed_products'; // Carpeta para guardar las imágenes procesadas
        $quality = $this->option('quality');

        // Asegurarse de que el driver GD esté disponible
        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            $this->error('Image processing requires the GD or Imagick extension. Please install one.');
            return 1;
        }

        // Usar el driver GD
        $manager = new ImageManager(new Driver());

        // Obtener todos los archivos de imagen en el directorio
        $files = Storage::files($imagePath);

        if ($files === false || empty($files)) {
            $this->warn("No image files found in the specified directory: {$imagePath}");
            return 0;
        }

        // Crear el directorio de salida si no existe
        if (!Storage::exists($outputDir)) {
            if (!Storage::makeDirectory($outputDir)) {
                $this->error("Could not create output directory: {$outputDir}");
                return 1;
            }
            $this->info("Created output directory: {$outputDir}");
        }

        $processedCount = 0;
        $failedCount = 0;

        foreach ($files as $file) {
            // Ignorar archivos que no sean imágenes comunes (puedes añadir más extensiones)
            $basename = basename($file);
            $extension = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                 $this->line("Skipping non-image file: {$basename}");
                continue;
            }

            $this->line("Processing: {$basename}");

            try {
                // Leer la imagen original
                $image = $manager->read(Storage::path($file));
                $originalWidth = $image->width();
                $originalHeight = $image->height();

                // Crear un nuevo lienzo con fondo negro del mismo tamaño que la imagen original
                $newImage = $manager->create($originalWidth, $originalHeight, '#000000'); // Fondo negro

                // Colocar la imagen original centrada sobre el fondo negro
                // Cálculo para centrar la imagen original
                $xOffset = ($originalWidth - $image->width()) / 2;
                $yOffset = ($originalHeight - $image->height()) / 2;
                $newImage->place($image, $xOffset, $yOffset);

                // Definir la ruta de guardado
                $destinationPath = "{$outputDir}/{$basename}";

                // Guardar la imagen procesada
                // Usamos la extensión original para guardar el tipo de archivo
                // La calidad se aplica principalmente a JPG y WebP
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $newImage->toJpeg($quality)->save(Storage::path($destinationPath));
                        break;
                    case 'png':
                        // Para PNGs, la calidad afecta la compresión.
                        // Si la imagen original era PNG, esto mantiene la transparencia
                        // y la coloca sobre el fondo negro.
                        $newImage->save(Storage::path($destinationPath), $quality);
                        break;
                    case 'gif':
                        // GIF puede no soportar bien la calidad así, guardamos por defecto
                        $newImage->save(Storage::path($destinationPath));
                        break;
                    case 'webp':
                         $newImage->save(Storage::path($destinationPath), $quality);
                         break;
                    default:
                        $this->warn("Unsupported format for saving: {$extension} for file {$basename}");
                        continue 2; // Saltar al siguiente archivo
                }

                // Si quieres sobrescribir la original, descomenta la línea de abajo y comenta la de arriba
                // $newImage->save(Storage::path($file), $quality);

                $processedCount++;

            } catch (\Exception $e) {
                $this->error("Error processing {$basename}: " . $e->getMessage());
                $failedCount++;
            }
        }

        if ($processedCount > 0) {
            $this->info("Successfully processed {$processedCount} images.");
        }
        if ($failedCount > 0) {
            $this->warn("Failed to process {$failedCount} images.");
        }
        if ($processedCount === 0 && $failedCount === 0) {
             $this->warn('No images were processed. Please check the directory path and file types.');
        }


        return 0;
    }
}
