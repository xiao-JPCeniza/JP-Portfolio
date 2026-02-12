<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:5000',
        ]);

        $message = ContactMessage::create($validated);

        try {
            Mail::raw(
                "New message from your portfolio\n\n" .
                "From: {$message->name} <{$message->email}>\n\n" .
                "Message:\n{$message->message}",
                function ($mail) use ($message) {
                    $mail->to(config('mail.contact_notify'))
                        ->subject('Portfolio: New message from ' . $message->name);
                }
            );
        } catch (\Throwable $e) {
            report($e);
            // Don't fail the request; message is still saved
        }

        return response()->json(['success' => true, 'message' => 'Message sent!']);
    }
}
