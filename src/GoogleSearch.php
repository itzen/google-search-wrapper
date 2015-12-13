<?php

namespace GoogleSearchWrapper;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class GoogleSearch {

    /**
     * @var bool
     */
    private $proxy;

    /**
     * @var string
     */
    private $user_agent;

    /**
     * @var \Goutte\Client
     */
    private $client;

    private $last_query;

    private $last_results;

    public function __construct($proxy = false, $user_agent = false){
        if($this->proxy && $this->isEliteProxy($proxy)) {
            $this->proxy = $proxy;
        }

        if($user_agent) {
            $this->user_agent = $user_agent;
        }
        else{
            $this->user_agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36';
        }

        $this->setUpClient();
    }

    private function isEliteProxy($proxy){
        //throw exception..
        return true;
    }

    private function setUpClient(){
        $this->client = new Client();
        if($this->user_agent){
            $this->client->setHeader('User-Agent', $this->user_agent);
        }
        if($this->proxy){
            $this->client->getClient()->setDefaultOption('config/curl/'.CURLOPT_PROXY, $this->proxy);
        }
    }

    public function search($keyword, $limit = 20){
        $keyword = urlencode($keyword);

        $this->last_results = [];
        $this->last_query = http_build_query([
            'q' => $keyword,
            'num' => $limit
        ]);

        $crawler = $this->client->request('GET', "https://www.google.com/search?" . $this->last_query);

        $this->last_results = $crawler->filter('.srg .g .rc')->each(function(Crawler $node, $position){
            $result = [
                'position' => $position + 1,
                'title' => $node->filter('h3.r a')->first()->text(),
                'link' => $node->filter('h3.r a')->first()->attr('href')
            ];

            $result['date'] = '';
            if($node->filter('.st .f')->getNode(0)){
                $result['date'] = $node->filter('.st .f')->first()->text();
            }

            $result['description'] = '';
            if($node->filter('.st')->getNode(0)){
                $result['description'] = $node->filter('.st')->first()->text();
                if( ! empty($result['date'])) {
                    $result['description'] = str_replace($result['date'], '', $result['description']);
                    $result['date'] = str_replace(' - ', '', $result['date']);
                }
            }

            return $result;
        });
    }

    public function getLastResults(){
        return $this->last_results;
    }

    /**
     * ex. yourawesomedomain.com
     * @param $domain
     */
    public function getKeywordPosition($domain_name){
        foreach($this->last_results as $result){
            if(strpos($result['link'], $domain_name) !== false){
                return $result['position'];
            }
        }
        return false;
    }

}