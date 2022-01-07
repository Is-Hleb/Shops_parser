<?php

namespace App;


use PHPHtmlParser\Dom;


abstract class ShopsParserController
{
    abstract public function run();
    protected string $url = "";
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