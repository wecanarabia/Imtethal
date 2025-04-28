<?php

namespace App\Interfaces;

interface SmsInterface
{
    public function send(array $data);
}
