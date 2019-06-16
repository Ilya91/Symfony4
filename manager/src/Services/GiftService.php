<?php


namespace App\Services;


use Psr\Log\LoggerInterface;

class GiftService
{
    public $gifts = ['flowers', 'car', 'piano', 'money', 'sugar', 'book', 'pen', 'knife', 'radio'];

    public function __construct(LoggerInterface $logger)
    {
        $logger->info('Hop hey!');
        shuffle($this->gifts);
    }
}