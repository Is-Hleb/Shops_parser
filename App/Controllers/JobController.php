<?php

namespace App\Controllers;

use App\Models\Job;
use App\Models\JobTemplate;
use App\TasksQueue\JobRunner;

class JobController extends Controller
{

    public function all() {
        global $entityManager;
        $jobs = $entityManager->getRepository(Job::class)->findAll();
        $output = [];

        foreach ($jobs as $job) {
            $output[] = $job->getToRead();
        }

        $this->success([
            'jobs' => $output
        ]);
    }

    public function info() {
        $jobId = $this->get['job'];
        $this->success($jobId);
    }

    public function create() {
        $postJob = $this->post['job'];
        JobRunner::run($postJob);
        $this->success($this->post);
    }
}