<?php
namespace App\Controllers;

use App\Models\Job;
use App\Models\JobContent;

class ContentController extends Controller {
    public function downloadAsJson(): void {
        $id = $this->get['content'];
        $job = $this->get['job'];

        $jobName = Job::find($job)->getName();

        $content = $this->entityManager->getReference(JobContent::class, $id);

        $json = $content->getJson();
        $fileName = $jobName . date('yy-mm-dd') . '.json';
        parent::downloadJson($json, $fileName);

    }
}