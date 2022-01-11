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

        // todo Не запускать, если не собрали все товары предыдущей категории
        foreach ($categories as $categoryURL) {
            if(!isset($cache[$categoryURL])) {
                $this->dom->loadFromUrl($categoryURL);
                // Название категории
                $title = $this->dom->find('.page-title')[0]->find('div')->text;
                // Ссылка на все товары категории
                $link = $this->dom->find('.all-link')[0]->find('a')->getAttribute('href');
                // Полная ссылка
                $curPageUrl = $this->url . $link;
                $lastLink = $link;
                $output[$categoryURL] = [
                    'category' => trim($title),
                    'url' => $this->url . $link
                ];
                $this->cacheSet('categories', array_merge($output, !$cache ? []:$cache));
                break;
            }
        }

        // Первая страница всех товаров категории
        $this->dom->loadFromUrl($curPageUrl);
        $pagesCount = $this->dom->find('.ib.page-num')[0];
        $pagesCount = $pagesCount->find('a');
        $pagesCount = $pagesCount[sizeof($pagesCount) - 1];
        $pagesCount = $pagesCount->text;


        // Получаем количество все страниц
        $pagesCount = intval($pagesCount);
        //ссылки на страницы. ссылки на продукты каждой страницы
        $limit = 4;
        // for по всем страницам
        for($curPage = 1; $curPage < $pagesCount; $curPage++) {
            if($limit-- <= 0) break;
            $pageLink = "https://www.e-katalog.ru{$lastLink}{$curPage}";
            $string = file_get_contents($pageLink); // получаем страницу и записываем в строку
            $this->dom->loadStr($string); // Создаём дом из строки

            $links = $this->dom->find('.model-short-title.no-u'); // Берём заголовки
            $products = [];
            foreach ($links as $link) {
                $name = $link->getAttribute('href'); // Берём ссылки
                $name = explode('.', $name);
                $name = substr($name[0], 1, strlen($name[0]) - 1); // Оставляем только название товара

                $span = $link->find('.u')[0];
                $productName = $span->text;
                $productLink = "https://www.e-katalog.ru/ek-item.php?resolved_name_={$name}&view_=tbl";

                $category = $output[$categoryURL]['category'];
                $product[] = [
                    'name' => $productName,
                    'link' => $productLink,
                ];
                $categoryName[] = [
                    'category: ' . $category => [
                        $product,
                    ]
                ];
                $this->cacheSet('categoryName', $categoryName);
            }
        }
    }
}






//https://www.e-katalog.ru/list/239/
//all-link
//Gorenje GI 5322 WF-B
//https://www.e-katalog.ru/ek-item.php?resolved_name_=GORENJE-GI-5322-WF-B&view_=tbl