<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ImageHelper
{
    public static function storeImage($image, $folder, $width = 500, $height = 500)
    {
        $filename = self::sanitizeFileName(now()->format('Ymd_His') . '_' . $image->getClientOriginalName());
        $path = public_path('uploads/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Cargar la imagen usando Intervention Image
        $img = Image::make($image);

        // Ajustar la orientación según los metadatos EXIF
        $img->orientate();

        // Redimensionar la imagen a las medidas proporcionadas
        $img->fit($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Guardar la imagen redimensionada
        $img->save($path . '/' . $filename);

        return url('uploads/' . $folder . '/' . $filename);
    }

    public static function storeImageWithDimensions($image, $folder, $verticalWidth = 739, $verticalHeight = 1600)
    {
        $filename = self::sanitizeFileName(now()->format('Ymd_His') . '_' . $image->getClientOriginalName());
        $path = public_path('uploads/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Cargar la imagen usando Intervention Image
        $img = Image::make($image);

        // Ajustar la orientación según los metadatos EXIF
        $img->orientate();

        // Determinar dimensiones según orientación
        if ($img->width() > $img->height()) {
            // Imagen horizontal
            $width = $verticalHeight; // 1600
            $height = $verticalWidth; // 739
        } else {
            // Imagen vertical
            $width = $verticalWidth;  // 739
            $height = $verticalHeight; // 1600
        }

        // Redimensionar la imagen según las dimensiones calculadas
        $img->fit($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Guardar la imagen redimensionada
        $img->save($path . '/' . $filename);

        return url('uploads/' . $folder . '/' . $filename);
    }

    public static function storeFile($pdf, $folder)
    {
        $originalName = $pdf->getClientOriginalName();
        $filename = self::sanitizeFileName(now()->format('Ymd_His') . '_' . $originalName);
        $path = public_path('uploads/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Guardar el archivo PDF
        $pdf->move($path, $filename);

        return [
            'url' => url('uploads/' . $folder . '/' . $filename),
            'name' => $originalName,
            'type' => $pdf->getClientOriginalExtension()
        ];
    }

    public static function deleteImage($imagePath)
    {
        // Verificar si el archivo existe y eliminarlo
        if (File::exists(public_path(parse_url($imagePath, PHP_URL_PATH)))) {
            File::delete(public_path(parse_url($imagePath, PHP_URL_PATH)));
        }
    }

    private static function sanitizeFileName($filename)
    {
        // Eliminar caracteres especiales y reemplazar espacios con guiones bajos
        $filename = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $filename);
        // Eliminar múltiples guiones bajos consecutivos
        $filename = preg_replace('/_+/', '_', $filename);
        // Limitar la longitud del nombre de archivo (opcional)
        $filename = Str::limit($filename, 255);

        return $filename;
    }
}
