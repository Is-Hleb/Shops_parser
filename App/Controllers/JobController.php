<?php

namespace App\Controllers;

use App\Models\Job;
use App\Models\JobTemplate;
use App\TasksQueue\JobRunner;
use App\TasksQueue\TasksQueue;

class JobController extends Controller
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
        $postJob = $this->post['job'];
        JobRunner::run($postJob);
        $this->success($this->post);
    }
}