<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap.php';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

while (true) {
    global $entityManager;

    $jobs = $entityManager->getRepository(\App\Models\Job::class)->findBy(
        ['status' => 2],
    );

    if (empty($jobs)) {
        break;
    }

    $activeJob = $entityManager->getRepository(\App\Models\Job::class)->findBy(
        ['status' => 1]
    );

    $must_run_next = true;
    if (!empty($activeJob)) {
        $must_run_next = false;
    }

    if ($must_run_next) {
        $job = $jobs[0];

        $output = null;
        $result = null;

        $job->setActive();

        exec($job->getCommand(), $output, $result);

        if(is_array($output)) {
            $output = implode("\n", $output);
        }

        file_put_contents('logs/jobs/' . $job->getName() . '.log', $output . "\nResult={$result}");

        $job->setDisabled($result);
    }

    sleep(3);
}