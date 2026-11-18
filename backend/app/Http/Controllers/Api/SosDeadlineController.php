<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SosDeadlineController extends Controller
{
    public function index(): JsonResponse
    {
        $now = Carbon::now();

        // Ambil semua tugas yang belum selesai, urutkan deadline terdekat
        $tasks = Task::where('status', '!=', 'completed')
                     ->whereNotNull('deadline')
                     ->orderBy('deadline', 'asc')
                     ->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'status_warning' => 'aman',
                'message'        => 'Tidak ada tugas mendekati deadline. Istirahat sebentar, kamu layak dapat jeda!',
                'recommended_schedule' => []
            ]);
        }

        // Hitung total estimasi menit semua tugas
        $totalMinutes = $tasks->sum('estimated_minutes');
        $totalHours   = round($totalMinutes / 60, 1);

        // Hitung jam tersisa hingga deadline terdekat
        $nearestDeadline    = Carbon::parse($tasks->first()->deadline);
        $hoursUntilDeadline = round($now->diffInMinutes($nearestDeadline) / 60, 1);

        // Tentukan level peringatan
        $statusWarning = 'aman';
        $message       = 'Tugasmu masih terkendali. Ambil satu langkah kecil hari ini ya!';

        if ($totalHours > $hoursUntilDeadline) {
            $statusWarning = 'bahaya';
            $message       = 'Waktumu cukup padat! Fokus ke 1 tugas paling mendesak dulu. Jangan forsir dirimu sampai larut malam.';
        } elseif ($tasks->count() >= 2 && $hoursUntilDeadline <= 48) {
            $statusWarning = 'waspada';
            $message       = 'Ada beberapa tugas berdekatan. Yuk mulai dari yang paling dekat deadlinenya!';
        }

        // Buat rekomendasi jadwal
        $schedule = $tasks->map(function ($task) use ($now) {
            $deadline      = Carbon::parse($task->deadline);
            $hoursLeft     = round($now->diffInMinutes($deadline) / 60, 1);
            $urgencyScore  = max(0, 100 - ($hoursLeft * 2));

            return [
                'id'            => $task->id,
                'task_title'    => $task->title,
                'category'      => $task->category,
                'deadline'      => $deadline->locale('id')->diffForHumans(),
                'hours_left'    => $hoursLeft,
                'urgency_score' => round($urgencyScore),
                'action_plan'   => $this->generateActionPlan($task->estimated_minutes)
            ];
        });

        return response()->json([
            'status_warning'        => $statusWarning,
            'message'               => $message,
            'total_workload_hours'  => $totalHours,
            'recommended_schedule'  => $schedule
        ]);
    }

    private function generateActionPlan(int $estimatedMinutes): string
    {
        if ($estimatedMinutes <= 30) {
            return 'Kerjakan langsung dalam 1 sesi pendek, lalu tandai selesai!';
        } elseif ($estimatedMinutes <= 90) {
            return 'Bagi menjadi 2 sesi: 45 menit fokus, 15 menit istirahat.';
        } else {
            return 'Kerjakan 45 menit, istirahat 15 menit. Ulangi maksimal 3 sesi per hari.';
        }
    }
}