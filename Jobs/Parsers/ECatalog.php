<?php

namespace Jobs\Parsers;

class ECatalog extends ShopsParserController
{
    protected string $url = "https://www.e-katalog.ru";

    private function getCategory(): array {

        // Take category from job data

        $category = $this->currentJob->getExternalData()['Category'];

        // add Log with input category
        $this->currentJob->addLogs($category, 'info');


        while(!$this->dom->loadFromUrl($category)) {
            $this->currentJob->addLogs("Пытаюсь получить категорию...", 'info');
            sleep(5);
        }
        // Название категории
        $title = $this->dom->find('.page-title')[0]->find('div')->text;
        // Ссылка на все товары категории
        $link = $this->dom->find('.all-link')[0]->find('a')->getAttribute('href');
        $link = $this->url . $link;

        $output[$category] = [
            'category' => trim($title),
            'url' => $link,
        ];

        return array_merge([
            'categories' => $output
        ], [
            'productsLink' => $link,
            'categoryLink' => $category
        ]);

    }

    private function parse(): void {
        $loadedCategory = $this->getCategory();


        $productsLink = $loadedCategory['productsLink'];
        $categoryLink = $loadedCategory['categoryLink'];

        // Первая страница всех товаров категории
        $this->dom->loadFromUrl($productsLink);

        $pagesCount = $this->dom->find('.ib.page-num')[0];
        $pagesCount = $pagesCount->find('a');
        $pagesCount = $pagesCount[sizeof($pagesCount) - 1];
        $pagesCount = $pagesCount->text;

        // Получаем количество все страниц
        $pagesCount = intval($pagesCount);
        $products = [];

        //ссылки на страницы. ссылки на продукты каждой страницы

        // for по всем страницам
        for ($curPage = 1; $curPage < $pagesCount; $curPage++) {

            // Current page with 24 products of same category
            $pageLink = "{$productsLink}{$curPage}";
            $string = file_get_contents($pageLink); // получаем страницу и записываем в строку
            $this->dom->loadStr($string); // Создаём дом из строки
            $links = $this->dom->find('.model-short-title.no-u'); // Берём заголовки

            // outputArray

            if (count($links) <= 1) {
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

                $products[$categoryLink][] = [
                    'name' => $productName,
                    'link' => $productsLinkToInsert
                ];;
            }
            $this->currentJob->addLogs("end of uploading $curPage page", 'info');
        }
        // Update products with saving all previous values
        $this->currentJob->addContent($products);
    }

    public function run() {
        $this->currentJob->addLogs(
            "START PROCESSING CATEGORY "
                . $this->currentJob->getExternalData()['Category']
            , 'info');
        
        $this->parse();
    }
}






//https://www.e-katalog.ru/list/239/
//all-link
//Gorenje GI 5322 WF-B
//https://www.e-katalog.ru/ek-item.php?resolved_name_=GORENJE-GI-5322-WF-B&view_=tbl