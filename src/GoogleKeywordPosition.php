<?php

namespace GoogleKeywordPosition;

use Curl\Curl;
use Sunra\PhpSimple\HtmlDomParser;

class GoogleKeywordPosition
{
    private $curl;

    public function __construct(){
        $this->curl = new Curl();
        $this->curl->setUserAgent('Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36');
        $this->curl->setopt(CURLOPT_SSL_VERIFYPEER, FALSE);
    }

    public function getKeywordPosition($domain, $keyword){
        $domain = (string)$domain;
        preg_match("/([-a-z0-9]+\.[a-z]{2,6})/i", $domain, $domain);
        if($domain !== false) {
            $domain = $domain[0];
        }
        else{
            //Domain exception
        }

        $keyword = (string)$keyword;
        $keyword = urlencode($keyword);

        $this->curl->get("https://www.google.com/search", array(
            "q" => $keyword,
            "num" => 100
        ));

        if ($this->curl->error) {
            echo $this->curl->error_code;
            return false;
        }

        return $this->parseKeywordPositionFromGoogleOutput($domain, $this->curl->response);
    }

    private function parseKeywordPositionFromGoogleOutput($domain, $output){
        $dom_parser = new HtmlDomParser();
        $html_doc = str_get_html($output);

        $search_results = $html_doc->find('#rso .g .rc .s cite._Rm');

        if(count($search_results) == 0){
            echo "Fetching results failed";
            return false;
        }

        $keyword_position = 0;

        foreach($search_results as $position => $search_result){
            if(strpos($search_result->plaintext, $domain) !== false){
                $keyword_position = $position;
                break;
            }
        }

        return $keyword_position;
    }
}