<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $notification = auth()->user()->notifications()->where('id', $request->id)->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
