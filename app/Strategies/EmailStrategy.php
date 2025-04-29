<?php

namespace App\Strategies;

use GuzzleHttp\Client;
use App\Interfaces\SmsInterface;
use Illuminate\Support\Facades\View;

class EmailStrategy implements SmsInterface
{
    private $token;
    private $headers;
    public function __construct()
    {
        $this->token = env("ZOHO_MAIL_TOKEN");
        $this->headers = [
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'cache-control' => 'no-cache',
            'authorization' => $this->token
        ];
    }

    public function send($data)
    {
        try {
            if ($data['email'] != null) {
                if (isset($data['view'])) {
                    $htmlBody = View::make($data['view'], [
                        'data' => $data['html_msg'],
                    ])->render();
                }else{
                    $htmlBody = $data['html_msg'];
                } 
                $payload = [
                    "from" => ["address" => "noreply@eimtithal.com"],
                    "to" => [["email_address" => ["address" => $data['email'], 'name' => $data['name']]]],
                    "subject" => $data['subject'],
                    "htmlbody" => $htmlBody,
                ];  
                $client = new Client();
                $response = $client->post( "https://api.zeptomail.com/v1.1/email", [
                    'headers' => $this->headers,
                    'json' => $payload,
                ]);
                return $response->getBody()->getContents();
            }
        } catch (\Exception $e) {
            return 'Eror: ' . $e->getMessage();
        }
    }
}
