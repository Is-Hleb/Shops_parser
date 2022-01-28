<?php

namespace App\TasksQueue;

use App\Models\Job;
use App\Models\JobTemplate;
use function Parser\Helper\random_string;

abstract class JobRunner
{

    public static function run(array $jobPost) {
        global $entityManager;
        $template = $entityManager->getReference(JobTemplate::class, $jobPost['jobTemplate']['id']);

        $jobName = empty(trim($jobPost['name'])) ? random_string() : $jobPost['name'];

        if ($template->getIsArrayInput() || count($jobPost['externalData']) === 1) {
            self::runOne($jobName, $jobPost['externalData'], $template);
        } else {
            $name = 1;
            foreach ($jobPost['externalData'] as $datum) {
                self::runOne(
                    $jobName . $name++,
                    [$datum],
                    $template
                );
            }
        }
    }

    private static function runOne(string $jobName, array $externalData, JobTemplate $jobTemplate) {
        global $entityManager;

        $jobs = $entityManager->getRepository(Job::class)->findBy(
            ['status' => 1]
        );

        if (empty($jobs)) {
            $command = 'php processor.php > processor.log&';
            if (PHP_OS == 'WINNT') {
                $command = "START /B $command";
                $command = str_replace('&', '', $command);
            }
            exec($command);
        }

        Job::addToQueue($jobName, $externalData, $jobTemplate);
    }


}