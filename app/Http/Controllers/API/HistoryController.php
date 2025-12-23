<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function history(Request $request)
    {
        $query = Task::where('user_id', Auth::user()->id)
            ->where(function ($innerQuery) {
                $innerQuery->where('is_done', true)
                    ->orWhere(function ($overdueQuery) {
                        $overdueQuery->where('is_done', false)
                            ->whereDate('deadline', '<', now()->toDateString());
                    });
            })
            ->orderBy('updated_at', 'desc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->input('year'));
        }

        if ($request->filled('is_done')) {
            $isDone = (int) $request->input('is_done');

            if ($isDone === 0) {
                $query->where('is_done', false)
                    ->whereDate('deadline', '<', now()->toDateString());
            } elseif ($isDone === 1) {
                $query->where('is_done', true);
            }
        }

        $tasks = $query->get();

        return response()->json([
            'status' => true,
            'data' => $tasks
        ]);
    }

    public function historyDetail($id)
    {
        $task = Task::where('user_id', Auth::id())
            ->where('is_done', true)
            ->where('id', $id)
            ->first();

        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $task,
        ]);
    }

}
