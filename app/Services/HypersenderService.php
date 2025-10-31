<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class HypersenderService
{
    protected $client;
    protected $baseUrl;
    protected $apiPath;
    protected $instanceId;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('hypersender.base_url');
        $this->apiPath = '/api/whatsapp/v1';
        $this->instanceId = config('hypersender.instance_id');
        $this->token = config('hypersender.token');
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);
    }

    /**
     * إرسال رسالة نصية عبر واتساب
     *
     * @param string $chatId
     * @param string $text
     * @param string|null $replyTo
     * @param bool $linkPreview
     * @return array
     */
    public function sendTextMessage($chatId, $text, $replyTo = null, $linkPreview = false)
    {
        $endpoint = "{$this->apiPath}/{$this->instanceId}/send-text";
        try {
            
            $response = $this->client->post($endpoint,[
                'json' => [
                    'chatId' => $chatId.'@c.us',
                    'text' => $text,
                    'reply_to' => $replyTo,
                    'link_preview' => $linkPreview,
                ],
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (GuzzleException $e) {
            Log::error('Hypersender API Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status_code' => $e->getCode(),
            ];
        }
    }
}