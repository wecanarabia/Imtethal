<?php

namespace App\Strategies;

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
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'cache-control' => 'no-cache',
            'Authorization' => $this->token
        ];
    }

    public function send($data)
    {
        try {
            if ($data['email'] != null) {
                if (isset($data['view'])) {
                    $htmlBody = View::make($data['view'], [
                        'data' => $data,
                    ])->render();
                }else{
                    $htmlBody = $data['msg'];
                }
                $payload = [
                    "from" => ["address" => "noreply@eimtithal.com"],
                    "to" => [["email_address" => ["address" => $data['email'], 'name' => $data['name']??'']]],
                    "subject" => $data['subject'],
                    "htmlbody" => $htmlBody,
                ];
                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', "https://api.zeptomail.com/v1.1/email", [
                    'headers' => $this->headers,
                    'json' => $payload,
                ]);
                return $response->getBody();
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return 'Eror: ' . $e->getMessage();
        }
    }
}
