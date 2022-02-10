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
        $job = Job::find($jobId);

        $logs = $job->getLogs();
        $jobName = $job->getName();
        if(is_file('logs/jobs/' . $jobName . '.log')) {
            $logsFile = file_get_contents('logs/jobs/' . $jobName . '.log', true);
        } else {
            $logsFile = "";
        }
        $contents = $job->getContentsToRead();
        $output['logs'] = $logs;
        $output['log_file'] = $logsFile;
        $output['contents'] = $contents;
        $output['externalData'] = $job->getExternalData();
        $this->success($output);
    }

    public function delete() {
        $id = $this->get['job'];
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

    public function restart() {
        $id = $this->get['job'];
        $job = Job::find($id);
        $job->tryToRepeat();
    }

    public function create() {
        $postJob = $this->post['job'];
        JobRunner::run($postJob);
        $this->success($this->post);
    }
}