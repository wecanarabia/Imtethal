<?php

namespace App\Strategies;

use App\Interfaces\SmsInterface;

class UnformalWhatsappStrategy implements SmsInterface
{
    private $token;
    private $headers;
    public function __construct()
    {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'cache-control' => 'no-cache',
        ];
    }

    public function send($data)
    {
        try {
            if ($data['phone'] != null) {
                if ($data['phone'][0] == "+") {
                    $data['phone'] = substr($data['phone'], 1);
                }
                $client = new \GuzzleHttp\Client();
                $url = "http://dash.nashme.net/api/send?number=" . $data['phone'] . "&type=text&message=" . $data['msg'] . " &instance_id=" . env('INSTANCE_ID') . "&access_token=" . env('ACCESS_TOKEN');
                $response = $client->request('POST', $url, [
                    'headers' => $this->headers,
                    'json' => "",
                ]);
                return $response->getBody()->getContents();
            }
        } catch (\Exception $e) {
            return 'Eror: ' . $e->getMessage();
        }
    }
}
