<?php

namespace Prajwal89\LaravelShareTo;

use Prajwal89\LaravelShareTo\LaravelShareTo;

class Share
{
    public static function Page(string $title,  string $urlToShare = '',  array $options = [])
    {
        return new LaravelShareTo($title, $urlToShare, $options);
    }
}