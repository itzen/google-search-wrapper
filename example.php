<?php

if(file_exists('config.php')) {
    require 'config.php';
}
require 'vendor/autoload.php';

date_default_timezone_set('America/Chicago');

$gs = new \GoogleSearchWrapper\GoogleSearch('52.70.56.140:80');

$gs->search('Meme', 10);

var_dump($gs->getKeywordPosition('memes.com'));

var_dump($gs->getLastResults());