<?php

namespace App\Controllers;

use App\Models\Job;

class JobsController extends Controller
{
    public function all() {
        $jobs = $this->entityManager->getRepository(Job::class)->findAll();
        $outputSettings = [];
        foreach ($jobs as $job) {
            $outputSettings[] = $job->getToRead();
        }
        $data['jobs'] = $outputSettings;
        $this->success($data);
    }

    public function create() {
        $job = new Job();
        $postJob = $this->post['job'];

        $job->setName($postJob['name']);
        $job->setStatus($postJob['status']);
        $job->setCommand($postJob['command']);
        $job->setActive($postJob['active']);
        $job->setExternalData($postJob['externalData']);
        $job->setStarted($postJob['started']);
        $job->setFinished($postJob['finished']);

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        $this->success($this->post);
    }
}