<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function history()
    {
        $tasks = Task::where('user_id', Auth::user()->id)
            ->where('is_done', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $tasks
        ]);
    }

    public function historyDetail($id)
    {
        $task = Task::where('user_id', Auth::user()->id)
            ->where('is_done', true)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $task
        ]);
    }

}
