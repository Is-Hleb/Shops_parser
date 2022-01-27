<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap.php';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

while (true) {

    $jobs = $entityManager->getRepository(\App\Models\Job::class)->findBy(
        ['status' => \App\TasksQueue\Job::WAITING],
    );

    if (empty($jobs)) {
        break;
    }

    $activeJob = $entityManager->getRepository(\App\Models\Job::class)->findBy(
        ['status' => \App\TasksQueue\Job::RUNNING]
    );

    $must_run_next = true;
    if (!empty($activeJob)) {
        $must_run_next = false;
    }

    if ($must_run_next) {
        $job = $jobs[0];

        $output = null;
        $result = null;

        $job->setStarted(new DateTime('NOW'));
        $job->setStatus(\App\TasksQueue\Job::RUNNING);
        $job->setActive(true);
        $entityManager->flush($job);

        exec($job->getCommand(), $output, $result);

        if(is_array($output)) {
            $output = implode("\n", $output);
        }

        file_put_contents('logs/jobs/' . $job->getName() . '.log', $output . "\nResult={$result}");

        if ($result == 0) {
            $job->setStatus(\App\TasksQueue\Job::ENDED);
        } else {
            $job->setStatus(\App\TasksQueue\Job::FAILED);
        }

        $job->setActive(false);
        $job->addLogs($output);
        $job->setFinished(new DateTime('NOW'));
        $entityManager->flush($job);
    }

    sleep(3);
}