<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class ImgurService
{
    public function uploadBase64Image(string $base64Image): ?string
    {
        try {
            // Limpiar el encabezado si es necesario
            if (str_starts_with($base64Image, 'data:image')) {
                $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            }

            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            $result = $cloudinary->uploadApi()->upload(
                "data:image/jpeg;base64," . $base64Image,
                [
                    'upload_preset' => 'laravel_upload'
                ]
            );

            return $result['secure_url'];
        } catch (\Exception $e) {
            Log::error('Cloudinary Error: ' . $e->getMessage());
            return null;
        }
    }
}
