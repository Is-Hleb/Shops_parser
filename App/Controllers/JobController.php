<?php

namespace App\Controllers;

use App\Models\Job;
use App\Models\JobTemplate;
use App\Models\JobContent;
use App\Models\Log;
use App\TasksQueue\TasksQueue;

class JobController extends Controller
{
    public function all() {
        $jobs = $this->entityManager->getRepository(Job::class)->findAll();
        $outputJobs = [];
        foreach ($jobs as $job) {
            $outputJob = $job->getToRead();
            $outputJob['jobTemplate'] = $job->getJobTemplate()->getToRead();
            $outputJob['jobContents'] = $job->getJobContents();
            $outputJob['jobLogs'] = $job->getJobLogs();
            $outputJobs[] = $outputJob;
        }
        $data['jobs'] = $outputJobs;
        $this->success($data);
    }

    public function getContent() {
        $jobContentId = $this->post['jobContent']['id'];
        $jobContent = $this->entityManager->getRepository(JobContent::class)->find($jobContentId)->getContent();
        $this->success($jobContent);
    }

    public function create() {
        $postJob = $this->post['job'];
        $jobTemplate =
            $this->entityManager->getRepository(JobTemplate::class)
                ->find($postJob['jobTemplate']['id']);

        $job = Job::toJobsQueue($postJob['name'], $postJob['externalData'], $jobTemplate);

        \App\TasksQueue\Job::setJob($job);
        TasksQueue::getInstance()->runLast();

        $this->success($this->post);
    }
}