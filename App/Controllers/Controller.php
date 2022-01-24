<?php
namespace App\Controllers;

use JetBrains\PhpStorm\NoReturn;

abstract class Controller {
    protected array $post;
    protected array $get;

    public function __construct() {
        header('accept: application/json, text/plain, */*');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: *");
        header('Access-Control-Allow-Headers: content-type');
        $this->post = $_POST;
        $this->get = $_GET;
    }

    #[NoReturn] public function success($data = []) {
        $output['data'] = $data;
        $output['code'] = 'success';
        header('Content-Type: application/json');
        echo json_encode($output);
        exit();
    }

    #[NoReturn] public function error($errors = []) {
        if(is_string($errors)) {
            $errors = [$errors];
        }
        $output['data'] = $errors;
        $output['code'] = 'error';
        header('Content-Type: application/json');
        echo json_encode($output);
        exit();
    }
}