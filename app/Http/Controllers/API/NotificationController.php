<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET ALL NOTIFICATIONS
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'List of notifications',
            'data'    => $notifications,
        ]);
    }

    // GET DETAIL NOTIFICATION
    public function show(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$notification) {
            return response()->json([
                'status'  => false,
                'message' => 'Notification not found',
                'data'    => null,
            ], 404);
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail notification',
            'data'    => $notification,
        ]);
    }
}
