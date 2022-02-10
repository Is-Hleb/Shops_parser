<?php

namespace Jobs\Parsers;

use App\Models\Job;
use PHPHtmlParser\Dom;


abstract class ShopsParserController
{
    abstract public function run();
    protected string $url = "";
    protected Job $currentJob;
    protected Dom $dom;
    private string $shopName;
    private mixed $content;


    public function __construct() {
        $this->dom = new Dom;
        $array = explode('\\', static::class);
        $this->shopName = end($array);
        if(CACHE_ON) {
            if(!is_dir($this->shopName)) {
                mkdir($this->shopName);
            }
            $fName = $this->shopName . '/' . 'map.json';

            if(!file_exists($fName))
                fopen($fName, 'w');

            $this->content = json_decode(file_get_contents($fName), JSON_OBJECT_AS_ARRAY);
        }
        $this->currentJob = Job::find(JOB_ID);
        $this->currentJob->addLogs($this->shopName . ' Парсинг запущен', 'info');
    }

}