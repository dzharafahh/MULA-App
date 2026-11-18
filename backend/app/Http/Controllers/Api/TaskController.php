<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    // 1. Ambil semua tugas beserta sub-tugasnya (diurutkan berdasarkan deadline terdekat)
    public function index(): JsonResponse
    {
        $tasks = Task::with('subtasks')->orderBy('deadline', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    // 2. Simpan tugas baru
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string',
            'deadline' => 'nullable|date',
            'estimated_minutes' => 'nullable|integer',
            'priority' => 'nullable|in:rendah,sedang,tinggi'
        ]);

        $task = Task::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditambahkan.',
            'data' => $task
        ], 201);
    }

    // 3. Ambil detail 1 tugas
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $task->load('subtasks')
        ]);
    }

    // 4. Update tugas (misal menandai tugas sudah selesai)
    public function update(Request $request, Task $task): JsonResponse
    {
        $task->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data' => $task
        ]);
    }

    // 5. Hapus tugas
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.'
        ]);
    }
}