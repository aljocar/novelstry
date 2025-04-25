<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImgurService
{
    public function uploadBase64Image(string $base64Image): ?string
    {
        try {
            // Limpia el encabezado de la imagen base64 si está presente
            if (str_starts_with($base64Image, 'data:image')) {
                $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Client-ID ' . config('services.imgur.client_id'),
            ])->timeout(15)->post('https://api.imgur.com/3/image', [
                'image' => $base64Image,
                'type' => 'base64'
            ]);

            return $response->successful() 
                ? $response->json()['data']['link']
                : null;

        } catch (\Exception $e) {
            Log::error('ImgurService Error: ' . $e->getMessage());
            return null;
        }
    }
}