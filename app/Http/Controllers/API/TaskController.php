<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $r)
    {
        $r->validate([
            'is_done' => 'nullable|in:0,1',
        ]);

        $query = Task::where('user_id', Auth::id());

        if ($r->has('is_done')) {
            $query->where('is_done', (int) $r->input('is_done'));
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status'  => true,
            'message' => 'List of tasks',
            'data'    => $tasks,
        ]);
    }

    public function show($id)
    {
        $task = Task::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (! $task) {
            return response()->json([
                'status'  => false,
                'message' => 'Task not found',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Task detail',
            'data'    => $task,
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'       => 'required',
            'description' => 'nullable',
            'deadline'    => 'nullable|date',
            'start_at'    => 'nullable|date',
            'end_at'      => 'nullable|date',
            'type'        => 'required|in:task,agenda',
            'is_priority' => 'boolean',
        ]);

        $data['user_id'] = Auth::id();

        $task = Task::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Task created successfully',
            'data'    => $task,
        ], 201);
    }

    public function update(Request $r, $id)
    {
        $task = Task::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (! $task) {
            return response()->json([
                'status'  => false,
                'message' => 'Task not found',
                'data'    => null,
            ], 404);
        }

        $data = $r->validate([
            'title'       => 'sometimes|required',
            'description' => 'nullable',
            'deadline'    => 'nullable|date',
            'start_at'    => 'nullable|date',
            'end_at'      => 'nullable|date',
            'is_done'     => 'boolean',
            'type'        => 'in:task,agenda',
            'is_priority' => 'boolean',
        ]);

        $task->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Task updated successfully',
            'data'    => $task,
        ]);
    }

    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (! $task) {
            return response()->json([
                'status'  => false,
                'message' => 'Task not found',
                'data'    => null,
            ], 404);
        }

        $task->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Task deleted successfully',
            'data'    => null,
        ]);
    }
}
