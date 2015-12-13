<?php

if(file_exists('config.php')) {
    require 'config.php';
}
require 'vendor/autoload.php';

date_default_timezone_set('America/Chicago');

$gs = new \GoogleSearchWrapper\GoogleSearch('149.202.56.98:8888');

$gs->search('Meme', 10);

echo "<pre>";
var_dump($gs->getKeywordPosition('memes.com'));
var_dump($gs->getLastResults());