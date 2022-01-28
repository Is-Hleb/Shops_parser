<?php

namespace App\Controllers;

use App\Models\Setting;

class SettingsController extends Controller
{
    private function uploadSettings() {

        $json = file_get_contents('ECatalog/settings.json');
        $json = json_decode($json, true);
        $categories = $json['categories'];

        foreach ($categories as $category) {
            $setting = new Setting();
            $setting->setName('Category');
            $setting->setValue($category);
            $setting->setCollection('ECatalog/categories');
            $this->entityManager->persist($setting);
            $this->entityManager->flush();
        }

    }

    public function all() {

        $settings = $this->entityManager->getRepository(Setting::class)->findAll();
        $outputSettings = [];
        foreach ($settings as $setting) {
            $outputSettings[] = [
                'id' => $setting->getId(),
                'name' => $setting->getName(),
                'value' => $setting->getValue(),
                'collection' => $setting->getCollection()
            ];
        }
        $data['settings'] = $outputSettings;
        $this->success($data);
    }

    public function allCollections() {
        $settings = $this->entityManager->getRepository(Setting::class)->findAll();
        $collections = [];
        foreach ($settings as $setting) {
            $collections[$setting->getCollection()] = $setting->getCollection();
        }
        $output['collections'] = array_values($collections);
        $this->success($output);
    }

    public function create() {
        $setting = new Setting();
        $postSetting = $this->post['setting'];

        $setting->setName($postSetting['name']);
        $setting->setValue($postSetting['value']);
        $setting->setCollection($postSetting['collection']);

        $this->entityManager->persist($setting);
        $this->entityManager->flush();

        $this->success($this->post);
    }

    public function delete() {
        $id = $this->post['setting']['id'];
        $setting = $this->entityManager->getRepository(Setting::class)->find($id);
        $this->entityManager->remove($setting);
        $this->entityManager->flush();
        $this->success();
    }

    public function edit() {
        $settingId = $this->get['setting'];
        $postSetting = $this->post['setting'];

        $setting = $this->entityManager->getReference(Setting::class, $settingId);
        $setting->setName($postSetting['name']);
        $setting->setValue($postSetting['value']);
        $setting->setCollection($postSetting['collection']);

        $this->entityManager->flush();
    }
}