<?php

namespace GoogleKeywordPosition;

use Curl\Curl;

class GoogleKeywordPosition
{
    private $curl;

    public function __construct(){
        $this->curl = new Curl();
    }
}