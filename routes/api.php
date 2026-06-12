<?php

use App\Http\Controllers\ChatController;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

Route::middleware('throttle:10,60')->post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name'    => 'required|string|max:100|regex:/^[^\r\n\t\x00]+$/',
        'email'   => 'required|email|max:100|regex:/^[^\r\n\t\x00]+$/',
        'subject' => 'required|string|max:150|regex:/^[^\r\n\t\x00]+$/',
        'message' => 'required|string|max:2000',
    ]);

    Mail::to('soyls191@gmail.com')->send(new ContactFormMail(
        $validated['name'],
        $validated['email'],
        $validated['subject'],
        $validated['message'],
    ));

    return response()->json(['message' => 'Mensaje enviado correctamente.']);
});

Route::post('/chat', [ChatController::class, 'send']);
