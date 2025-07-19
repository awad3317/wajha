<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
      //
    }

    public function sendNotification($deviceToken, $title, $body, $data = [])
{
    try {
        if (!$this->messaging) {
            $credentialsPath = config('firebase.projects.app.credentials') ?? env('FIREBASE_CREDENTIALS');
            
            if (empty($credentialsPath) || !file_exists($credentialsPath)) {
                throw new \RuntimeException('Firebase credentials file not found at: ' . $credentialsPath);
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        }

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $this->messaging->send($message);
        return true;
        
    } catch (\Exception $e) {
        \Log::error('FCM Error: ' . $e->getMessage(), [
            'device_token' => $deviceToken,
            'error_details' => $e->getTraceAsString()
        ]);
        return false;
    }
}
}