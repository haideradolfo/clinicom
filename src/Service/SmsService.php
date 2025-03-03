<?php

namespace App\Service;

use Twilio\Rest\Client;

class SmsService
{
    public function __construct(
        private string $twilioSid,
        private string $twilioToken,
        private string $twilioNumber
    ) {}

    public function sendSms(string $to, string $message): void
    {
        $client = new Client($this->twilioSid, $this->twilioToken);
        
        $client->messages->create(
            $to,
            [
                'from' => $this->twilioNumber,
                'body' => $message
            ]
        );
    }
}