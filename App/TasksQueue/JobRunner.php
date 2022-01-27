<?php

namespace App\TasksQueue;

use App\Models\Job;
use App\Models\JobTemplate;

abstract class JobRunner
{
    private static function randomName(): string {
        $validSims = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm";
        $name = "";
        for ($i = 0; $i < rand(5, 12); $i++) {
            $name .= $validSims[rand(0, strlen($validSims) - 1)];
        }
        return $name;
    }

    public static function run(array $jobPost) {
        global $entityManager;
        $template = $entityManager->getReference(JobTemplate::class, $jobPost['jobTemplate']['id']);
        if ($template->getIsArrayInput() || count($jobPost['externalData']) === 1) {
            self::runOne($jobPost['name'], $jobPost['externalData'], $template);
        } else {
            $name = 1;
            foreach ($jobPost['externalData'] as $datum) {
                self::runOne($jobPost['name'] . $name++, [$datum], $template);
            }
        }
    }

    private static function runOne(string $jobName, array $externalData, JobTemplate $jobTemplate) {
        global $entityManager;

        $jobs = $entityManager->getRepository(Job::class)->findBy(
            ['status' => \App\TasksQueue\Job::RUNNING]
        );

        if (empty($jobs)) {
            $command = 'php processor.php > processor.log&';
            if (PHP_OS == 'WINNT') {
                $command = "START /B $command";
                $command = str_replace('&', '', $command);
            }
            exec($command);
        }

        $job = Job::toJobsQueue($jobName, $externalData, $jobTemplate);
        $job = \App\TasksQueue\Job::setJob($job);
        $job->execute();
    }


}