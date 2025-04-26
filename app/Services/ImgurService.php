<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImgurService
{
    public function uploadBase64Image(string $base64Image): ?string
    {
        try {
            // Limitar a 1 petición cada 2 segundos (Imgur permite ~50/hr en modo gratuito)
            sleep(2); // ⚠️ Esto ralentiza la ejecución, pero evita exceder límites

            if (str_starts_with($base64Image, 'data:image')) {
                $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Client-ID ' . config('services.imgur.client_id'),
            ])->timeout(10)->post('https://api.imgur.com/3/image', [
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
