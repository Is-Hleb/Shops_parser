<?php

namespace App;


use App\TasksQueue\Job;
use PHPHtmlParser\Dom;
use App\TasksQueue\TasksQueue;

abstract class ShopsParserController
{
    abstract public function run();
    protected string $url = "";
    protected Job $currentJob;
    protected Dom $dom;
    private string $shopName;
    private mixed $content;


    public function __construct() {
        $connected = file_get_contents("http://bgaek.by/");
        if(!$connected) {
            exit(json_encode([
                'error' => 'connection error'
            ]));
        }
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
        $taskQueue = TasksQueue::getInstance();
        $this->currentJob = $taskQueue->getJobByName($this->shopName);
        $this->currentJob->putContent($this->shopName . ' Парсинг запущен');
    }

    public function cacheHas($key) : bool {
        return isset($this->content[$key]);
    }

    public function getSettings() : array {
        $fName = $this->shopName . '/' . 'settings.json';
        return json_decode(file_get_contents($fName), JSON_OBJECT_AS_ARRAY);
    }

    public function cacheSet($key, $value) {
        $this->content[$key] = $value;
    }

    public function cacheAdd(string $keyL1, array $value) {
        $this->content[$keyL1] = array_merge($value, $this->content[$keyL1]);
    }

    public function cacheGet($key) {
        return $this->content[$key] ?? false;
    }

    public function cacheGetAll() {
        return $this->content ?? false;
    }

    public function cacheClear() {
        $this->content = [];
    }

    public function __destruct() {
        $this->cacheUpdate();
    }

    public function cacheUpdate() : void {
        if(!CACHE_ON) {
            return;
        }
        $fName = $this->shopName . '/' . 'map.json';
        file_put_contents($fName, json_encode($this->content));
    }

    public function getLink(string $url, string $name = "Page") : string {
        return <<<LINK
            <a href="$url">$name</a>
        LINK;
    }

}