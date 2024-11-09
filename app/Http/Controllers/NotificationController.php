<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        $user = auth()->user();

        DB::table('notifications')
            ->where('user_id', $user->id)
            ->update(['is_read' => 1]);

        return redirect()->back()->with(['message' => 'Notifikasi telah dibaca semua', 'status' => 'success']);
    }
}
