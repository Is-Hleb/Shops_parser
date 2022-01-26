<?php

namespace App;


use App\TasksQueue\Job;
use PHPHtmlParser\Dom;
use App\TasksQueue\TasksQueue;

abstract class ShopsParserController
{
    abstract public function run($category);
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
        $taskQueue = TasksQueue::getInstance();
        $this->currentJob = $taskQueue->getJobById(JOB_ID);
        $this->currentJob->addLog($this->shopName . ' Парсинг запущен', 'info');
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


}