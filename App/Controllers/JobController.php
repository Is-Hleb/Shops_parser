<?php

namespace App\Controllers;

use App\Models\Job;
use App\Models\JobTemplate;
use App\Models\Log;
use App\TasksQueue\JobRunner;
use function global\d;

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

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function info() {
        $output = [];
        $jobId = $this->get['job'];
        $logs = Job::find($jobId)->getLogs();
        $jobName = Job::find($jobId)->getName();
        $logsFile = file_get_contents('logs/jobs/' . $jobName . '.log', true);
        $contents = Job::find($jobId)->getContentsToRead();
        $output['logs'][] = $logs;
        $output['logs_file'] = $logsFile;
        $output['contents'][] = $contents;
        $this->success($output);
    }

    public function delete() {
        $id = $this->get['id'];
        $job = $this->entityManager->getRepository(Job::class)->find($id);
        $this->entityManager->remove($job);
        $this->entityManager->flush();
        $this->success($job);
    }

    public function deleteJobs() {
        $id = $this->post['id'];
        foreach ($id as $value) {
            $job = $this->entityManager->getRepository(Job::class)->find($value);
            $this->entityManager->remove($job);
            $this->entityManager->flush();
        }
        $this->success();
    }

    public function create() {
        $postJob = $this->post['job'];
        JobRunner::run($postJob);
        $this->success($this->post);
    }
}