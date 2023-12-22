<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\AuditLog;
use Inertia\Inertia;
use Inertia\Response;


class NotificationController extends Controller

{

    public function index()
    {

    }
    public function getNotificationData()
    {
        // Get the notifications to be displayed in alert pop up
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        // Retrieve the count of Unread new notifications
        $notificationCount = Notification::where('read', 0)->count();

        return response()->json([
        'notifications'=>$notifications,
        'notificationCount'=>$notificationCount
    ]);

    }

    public function markAsRead($id,$auditlogid)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update(['read' => 1]);
            // mark the notification as read
            //return response()->json(['message' => 'Notification marked as read']);
        }

        //return response()->json(['message' => 'Notification not found'], 404);
        // redirect to the page on audit log 
        $audit = AuditLog::where('id', $auditlogid)->first();
        return Inertia::render('Audit/Create', [
            'audit' => $audit,
        ]);

    }
    public function markAllAsRead()
    {
        // Update all notifications where 'read' is 0 to mark them as read (read = 1)
        Notification::where('read', 0)->update(['read' => 1]);

        return response()->json(['message' => 'All notifications marked as read']);
    }



}
