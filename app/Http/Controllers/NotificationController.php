<?php

namespace App\Http\Controllers;

use App\Models\Notification;


class NotificationController extends Controller

{

    public function index()
    {

    }
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update(['read' => 1]);
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }
    public function markAllAsRead()
    {
        // Update all notifications where 'read' is 0 to mark them as read (read = 1)
        Notification::where('read', 0)->update(['read' => 1]);

        return response()->json(['message' => 'All notifications marked as read']);
    }



}
