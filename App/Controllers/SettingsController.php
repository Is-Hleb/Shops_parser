<?php
namespace App\Controllers;

class SettingsController extends Controller {
    public function all() {
        $json = file_get_contents('ECatalog/settings.json');
        $data = json_decode($json, true);
        $this->success($data);
    }
}