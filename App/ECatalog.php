<?php

namespace App;

use Http\Client\Exception;
use PHPHtmlParser\Dom;

class ECatalog extends ShopsParserController
{
    protected string $url = "https://www.e-katalog.ru";

    private function getCategory(): array
    {
        $cache = $this->cacheGet('categories');
        $categories = $this->getSettings()['categories'];
        foreach ($categories as $categoryURL) {
            if (!isset($cache[$categoryURL])) {
                $this->dom->loadFromUrl($categoryURL);
                // Название категории
                $title = $this->dom->find('.page-title')[0]->find('div')->text;
                // Ссылка на все товары категории
                $link = $this->dom->find('.all-link')[0]->find('a')->getAttribute('href');
                $link = $this->url . $link;
                $output[$categoryURL] = [
                    'category' => trim($title),
                    'url' => $link,
                ];

                $this->cacheSet('categories', array_merge($output, !$cache ? [] : $cache));
                $this->cacheUpdate();

                // current category
                // dd($output);
                return array_merge([
                    'categories' => $output
                ], [
                    'productsLink' => $link,
                    'categoryLink' => $categoryURL
                ]);
            }
        }
    }

    private function parse() : void {
        // return;
        $loadedCategory = $this->getCategory();
        $productsLink = $loadedCategory['productsLink'];
        $categoryLink = $loadedCategory['categoryLink'];
        $allCategories = $this->cacheGet('categories');

        // Первая страница всех товаров категории
        $this->dom->loadFromUrl($productsLink);

        $pagesCount = $this->dom->find('.ib.page-num')[0];
        $pagesCount = $pagesCount->find('a');
        $pagesCount = $pagesCount[sizeof($pagesCount) - 1];
        $pagesCount = $pagesCount->text;

        // Массив продуктов
        $products = $this->cacheGet('products');
        // Получаем количество все страниц
        $pagesCount = intval($pagesCount);
        //ссылки на страницы. ссылки на продукты каждой страницы
        $limit = 4;
        // for по всем страницам
        for ($curPage = 1; $curPage < $pagesCount; $curPage++) {

            // Current page with 24 products of same category
            $pageLink = "{$productsLink}{$curPage}";
            $string = file_get_contents($pageLink); // получаем страницу и записываем в строку
            $this->dom->loadStr($string); // Создаём дом из строки
            $links = $this->dom->find('.model-short-title.no-u'); // Берём заголовки

            if(count($links) <= 1) {
                break;
            }

            foreach ($links as $link) {
                $name = $link->getAttribute('href'); // Берём ссылки
                $name = explode('.', $name);
                $name = substr($name[0], 1, strlen($name[0]) - 1); // Оставляем только название товара

                $span = $link->find('.u')[0];
                $productName = $span->text;

                // Link to product properties
                $productsLinkToInsert = "https://www.e-katalog.ru/ek-item.php?resolved_name_={$name}&view_=tbl";

                $category = $allCategories[$categoryLink]['category'];
                $products[$category][] = [
                    'name' => $productName,
                    'link' => $productsLinkToInsert
                ];
            }

            // Update products with saving all previous values
            $this->cacheSet('products', $products);
            $this->cacheUpdate();
        }
    }

    public function run()
    {
        $this->parse();
    }
}






//https://www.e-katalog.ru/list/239/
//all-link
//Gorenje GI 5322 WF-B
//https://www.e-katalog.ru/ek-item.php?resolved_name_=GORENJE-GI-5322-WF-B&view_=tbl