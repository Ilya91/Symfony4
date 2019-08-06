<?php


namespace App\Services;


class MyService
{
    public function __construct($adminEmail, $second_service )
    {
        dump($adminEmail);
        dump($second_service);
    }
}