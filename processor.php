<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap.php';

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class) . '.php';
});

$prLog = json_decode(file_get_contents(__DIR__ . '/processor.json'), true) ?? [];

global $entityManager;
$activeJob = $entityManager->getRepository(\App\Models\Job::class)->findOneBy(
    ['status' => 1]
);

if (empty($prLog)) {

    $prLog['started'] = new DateTime('NOW');

} elseif (empty($prLog['lastJobExecutingAttempt'])) {

    if (!empty($activeJob)) {
        $activeJob->tryToRepeat();
    }
    $prLog['lastJobExecutingAttempt'] = new DateTime('NOW');
}

while (true) {
    global $entityManager;

    $jobs = $entityManager->getRepository(\App\Models\Job::class)->findBy(
        ['status' => 2],
    );

    if (empty($jobs)) {
        $prLog['finished'] = new DateTime('NOW');
        break;
    }

    $activeJob = $entityManager->getRepository(\App\Models\Job::class)->findBy(
        ['status' => 1]
    );
    if(!empty($activeJob)) {
        $job = $activeJob[0];

        $output = null;
        $result = null;
        exec($job->getCommand(), $output, $result);

        if (is_array($output)) {
            $output = implode("\n", $output);
        }

        file_put_contents('logs/jobs/' . $job->getName() . '.log', $output . "\nResult={$result}");

        $job->setDisabled($result);
    } else {
        $job = $jobs[0];

        $job->setActive();

        $prLog['lastJobExecutingAttempt'] = new DateTime('NOW');

        $output = null;
        $result = null;
        exec($job->getCommand(), $output, $result);

        if (is_array($output)) {
            $output = implode("\n", $output);
        }

        file_put_contents('logs/jobs/' . $job->getName() . '.log', $output . "\nResult={$result}");

        $job->setDisabled($result);


        $prLog['lastUpdated'] = new DateTime('NOW');
        file_put_contents(__DIR__ . '/processor.json', json_encode($prLog));
    }
    sleep(3);
}