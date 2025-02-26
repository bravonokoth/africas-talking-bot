<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;

class AfricasTalkingService
{
    protected $sms;

    public function __construct()
    {
        $username = config('services.africastalking.username'); // 'sandbox'
        $apiKey   = config('services.africastalking.api_key');
        $AT       = new AfricasTalking($username, $apiKey);
        $this->sms = $AT->sms();
    }

    public function sendMessage(string $to, string $message): array
    {
        return $this->sms->send([
            'to' => $to,
            'message' => $message,
            'from' => config('services.africastalking.sender_id', ''),
        ]);
    }
}