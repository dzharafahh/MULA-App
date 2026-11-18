<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    // Simpan sesi timer yang selesai
    public function store(Request $request): JsonResponse
    {
        $log = ActivityLog::create([
            'activity_type' => $request->activity_type ?? 'timer_2min_completed',
            'notes'         => $request->notes ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Langkah kecilmu sudah tercatat. Keren!',
            'data'    => $log
        ], 201);
    }

    // Ambil statistik untuk halaman Jejak Langkah
    public function stats(): JsonResponse
    {
        $totalSessions  = ActivityLog::where('activity_type', 'timer_2min_completed')->count();
        $todaySessions  = ActivityLog::where('activity_type', 'timer_2min_completed')
                                     ->whereDate('created_at', today())
                                     ->count();
        $weekSessions   = ActivityLog::where('activity_type', 'timer_2min_completed')
                                     ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                     ->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'total_sessions' => $totalSessions,
                'today'          => $todaySessions,
                'this_week'      => $weekSessions,
                'message'        => "Kamu sudah mengambil {$weekSessions} langkah kecil minggu ini. Luar biasa!"
            ]
        ]);
    }
}