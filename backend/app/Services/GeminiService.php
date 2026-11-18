<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class GeminiService
{
    protected string $apiKey;
    protected string $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function breakDownTask(string $taskTitle): array
    {
        $prompt = "Kamu adalah asisten mindful productivity untuk mahasiswa yang sering menunda tugas karena kewalahan.
Tugas mahasiswa: '{$taskTitle}'.
Pecah tugas ini menjadi 4-5 langkah kecil yang tidak menakutkan.
SYARAT WAJIB:
- Langkah 1 HARUS berupa tindakan mikro berdurasi tepat 2 menit (misal: buka dokumen, tulis judul, baca 1 halaman).
- Langkah berikutnya bertahap antara 15-30 menit.
- Gunakan bahasa santai dan tidak menekan.
- Balas HANYA dengan array JSON murni tanpa penjelasan tambahan.
Format JSON: [{\"order\":1,\"title\":\"...\",\"estimated_minutes\":2},{\"order\":2,\"title\":\"...\",\"estimated_minutes\":20}]";

        $response = null;
        $maxRetry = 3;

        for ($i = 0; $i < $maxRetry; $i++) {
            $response = Http::timeout(30)->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'response_mime_type' => 'application/json'
                ]
            ]);

            if ($response->status() === 503) {
                sleep(3); // tunggu 3 detik lalu coba lagi
                continue;
            }
            break;
        }

        if ($response->status() === 503) {
            throw new Exception("AI MULA sedang sibuk, coba lagi dalam beberapa detik ya!");
        }

        if ($response->failed()) {
            throw new Exception("Gagal terhubung ke AI: " . $response->body());
        }

        $rawText = $response->json('candidates.0.content.parts.0.text');
        $decoded = json_decode($rawText, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            throw new Exception("Format respons AI tidak valid.");
        }

        return $decoded;
    }
}