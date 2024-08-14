<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

use App\Models\Guru;

class SubscriptionController extends Controller
{
    // Handle subscription dari guru
    public function subscribe(Request $request)
    {
        // $request->validate([
        //     'endpoint' => 'required|string',
        //     'public_key' => 'required|string',
        //     'auth_token' => 'required|string',
        // ]);


        // Simpan subscription
        $subscription = new Subscription();
        $subscription->user_id = Auth::id();
        $subscription->endpoint = $request->endpoint;
        $subscription->public_key = $request->keys['p256dh'];
        $subscription->auth_token = $request->keys['auth'];
        $subscription->save();

        return response()->json(['success' => true, 'message' => 'Subscribed successfully']);
    }

    // Handle unsubscription
    public function unsubscribe(Request $request)
    {
        $guru = auth()->user(); // Mengasumsikan guru sudah login

        Subscription::where('guru_id', $guru->id)
            ->where('endpoint', $request->endpoint)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Unsubscribed successfully']);
    }
}
