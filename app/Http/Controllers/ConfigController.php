<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConfigController extends Controller
{
    public function index(){
        return view('dashboard.config.index');
    }

    public function pushNotification()
    {
        return view('dashboard.config.notification');
    }

    public function sendPushNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        // Aqui você pode buscar todos os tokens do banco
        $tokens = \DB::table('user_devices')->pluck('fcm_token')->toArray();

        $response = Http::withHeaders([
            'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => $tokens, // lista de tokens
            'notification' => [
                'title' => $request->title,
                'body' => $request->body,
                'sound' => 'default',
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'teste',
            ]
        ]);

        return back()->with('success', 'Notificação enviada! Status: ' . $response->status());
    }
}
