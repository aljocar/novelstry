<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImgurService
{
    public function uploadBase64Image(string $base64Image): ?string
    {
        try {
            // Verificar si la imagen está vacía
            if (empty($base64Image)) {
                throw new \Exception("La imagen está vacía");
            }

            // Limpiar encabezado
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);

            $client = new \GuzzleHttp\Client([
                'base_uri' => 'https://api.imgur.com/3/',
                'headers' => [
                    'Authorization' => 'Client-ID ' . config('services.imgur.client_id'),
                    'Accept' => 'application/json',
                ],
                'timeout' => 15,
            ]);

            $response = $client->post('image', [
                'form_params' => [
                    'image' => $base64Image,
                    'type' => 'base64'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 200 || !isset($data['data']['link'])) {
                Log::error('Imgur API Error', [
                    'status' => $response->getStatusCode(),
                    'response' => $data
                ]);
                return null;
            }

            return $data['data']['link'];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Imgur Request Error', [
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Imgur Service Error', ['message' => $e->getMessage()]);
            return null;
        }
    }
}