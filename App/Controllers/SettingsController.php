<?php

namespace App\Controllers;

use App\Models\Setting;

class SettingsController extends Controller
{
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
        $postSetting = $this->post['setting'];
        $setting = $this->entityManager->getReference(Setting::class, $postSetting['id']);

        $setting->setName($postSetting['name']);
        $setting->setValue($postSetting['value']);
        $setting->setCollection($postSetting['collection']);

        $this->entityManager->flush();
    }
}