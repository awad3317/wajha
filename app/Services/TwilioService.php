<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $client;
    protected $whatsappFrom;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.auth_token')
        );
        $this->whatsappFrom = config('services.twilio.whatsapp_from');
    }

    public function sendOTP($to, $otpCode)
    {
        try {
            $message = $this->client->messages->create(
                "whatsapp:+967781152674", 
                array(
          "from" => "whatsapp:+14155238886",
          "contentSid" => "HXb5b62575e6e4ff6129ad7c8efe1f983e",
          "body" => "Your Message"
        )
            );

            return [
                'success' => true,
                'message_id' => $message->sid,
                'status' => $message->status
            ];

        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}