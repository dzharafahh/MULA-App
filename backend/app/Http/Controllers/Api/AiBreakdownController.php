<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subtask;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;

class AiBreakdownController extends Controller
{
    public function breakdown(Task $task, GeminiService $gemini): JsonResponse
    {
        try {
            // Hapus sub-tugas lama jika sudah pernah dipecah sebelumnya
            $task->subtasks()->delete();

            // Panggil Gemini AI
            $steps = $gemini->breakDownTask($task->title);

            // Simpan hasil pecahan ke database
            $savedSubtasks = [];
            foreach ($steps as $step) {
                $savedSubtasks[] = Subtask::create([
                    'task_id'            => $task->id,
                    'title'              => $step['title'],
                    'estimated_minutes'  => $step['estimated_minutes'] ?? 15,
                    'order'              => $step['order'],
                    'is_completed'       => false
                ]);
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Tugas berhasil dipecah oleh MULA AI.',
                'subtasks' => $savedSubtasks
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}