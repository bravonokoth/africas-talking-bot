<?php

namespace App\Http\Controllers;

use App\Services\AfricasTalkingService;
use App\Models\Lead;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(AfricasTalkingService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function webhook(Request $request)
    {
        $phone = $request->input('from');
        $message = strtolower($request->input('text'));

        \Log::info('Incoming SMS webhook:', $request->all());

        if (str_contains($message, 'lead')) {
            $response = "Please provide your name, email, and phone number to generate a lead.";
        } elseif (str_contains($message, 'name') && str_contains($message, 'email') && str_contains($message, 'phone')) {
            preg_match('/name\s*:\s*(\w+)/i', $message, $nameMatch);
            preg_match('/email\s*:\s*([\w\.-]+@[\w\.-]+)/i', $message, $emailMatch);
            preg_match('/phone\s*:\s*(\+\d+)/i', $message, $phoneMatch);

            if ($nameMatch && $emailMatch && $phoneMatch) {
                Lead::create([
                    'name' => $nameMatch[1],
                    'email' => $emailMatch[1],
                    'phone' => $phoneMatch[1],
                ]);
                $response = "Thank you! Your lead has been captured. We’ll get back to you soon.";
            } else {
                $response = "Please send in this format: 'Name: [your name], Email: [your email], Phone: [your phone]'";
            }
        } else {
            $response = "Hi! To generate a lead, say 'lead' or send your name, email, and phone number.";
        }

        $result = $this->chatService->sendMessage($phone, $response);
        \Log::info('SMS response:', $result); // Add this to debug

        return response()->json([
            'status' => $result['status'] === 'success' ? 'success' : 'error',
            'response' => $result,
        ]);
    }
}