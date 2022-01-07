<?php

namespace App;
use Http\Client\Exception;
use PHPHtmlParser\Dom;

class ECatalog extends ShopsParserController {
    protected string $url = "https://www.e-katalog.ru";

    public function run() {
        $categories = $this->getSettings()['categories'];

        $output = [];
        $cache = $this->cacheGet('categories');
        d($cache);

        $limit = 4;

        foreach ($categories as $categoryURL) {
            if($limit-- <= 0) break;
            $this->dom->loadFromUrl($categoryURL);
            // sleep(0.3);
            // echo $this->getLink($categoryURL);
            $title = $this->dom->find('.page-title')[0]->find('div')->text;
            $link = $this->dom->find('.all-link')[0]->find('a')->getAttribute('href');
            $output[trim($title)] = [
                'category' => trim($title),
                'url' => $this->url . $link
            ];
        }
        $this->cacheSet('categories', $output);
    }
}