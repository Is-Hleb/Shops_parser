<?php
namespace App\Controllers;

use App\Models\JobTemplate;

class JobTemplateController extends Controller {
    public function all() {
        $jobTemplates = $this->entityManager->getRepository(JobTemplate::class)->findAll();
        $output = [];
        foreach ($jobTemplates as $jobTemplate) {
            $output[] = [
                'id' => $jobTemplate->getId(),
                'name' => $jobTemplate->getName(),
                'class' => $jobTemplate->getClass(),
                'method' => $jobTemplate->getMethod(),
                'isArrayInput' => $jobTemplate->getIsArrayInput(),
                'description' => $jobTemplate->getDescription(),
            ];
        }
        $this->success([
            'jobTemplates' => $output
        ]);
    }

    public function create() {
        $postJobTemplate = $this->post['jobTemplate'];
        $jobTemplate = new JobTemplate();

        $jobTemplate->setName($postJobTemplate['name']);
        $jobTemplate->setClass($postJobTemplate['class']);
        $jobTemplate->setMethod($postJobTemplate['method']);
        $jobTemplate->setIsArrayInput($postJobTemplate['isArrayInput']);
        $jobTemplate->setDescription($postJobTemplate['description']);

        $this->entityManager->persist($jobTemplate);
        $this->entityManager->flush();

        $this->success();
    }

}