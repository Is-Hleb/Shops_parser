<?php

namespace App\Controllers;

use Doctrine\ORM\EntityManager;
use JetBrains\PhpStorm\NoReturn;

abstract class Controller
{
    protected array $post;
    protected array $get;
    protected EntityManager $entityManager;

    public function __construct() {
        header('accept: application/json, text/plain, */*');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: *");
        header('Access-Control-Allow-Headers: content-type');
        $json = file_get_contents('php://input');

        global $entityManager;
        $this->entityManager = $entityManager;
        $this->post = json_decode($json, true) ?? [];

        $this->get = $_GET;
        $parsed = parse_url($_SERVER['REQUEST_URI']);
        if(is_array($parsed) && !empty($parsed['query'])) {
            $data = $parsed['query'];
            $values = explode('&', $data);
            $output = [];
            foreach ($values as $value) {
                $datum = explode('=', $value);
                $output[$datum[0]] = $datum[1] ?? null;
            }
            $this->get = $output;
        }

    }

    #[NoReturn] public function success($data = []) {
        $output['data'] = $data;
        $output['code'] = 'success';
        header('Content-Type: application/json');
        echo json_encode($output);
        exit();
    }

    #[NoReturn] public function error($errors = []) {
        if (is_string($errors)) {
            $errors = [$errors];
        }
        $output['data'] = $errors;
        $output['code'] = 'error';
        header('Content-Type: application/json');
        echo json_encode($output);
        exit();
    }

    #[NoReturn] public function downloadJson(string $json, string $fileName = null) : void{

        if(!$fileName) {
            $file_name = date('Y-m-d h:m') . ".json";
        }

        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header("Content-Type: application/json; charset=utf-8");

        echo $json;
        exit();
    }
}