<?php

namespace App\Service\Adapter;

use App\Entity\TaskEntity;
use App\Helper\Util;

class ProviderBetaAdaptor implements TaskProviderInterface
{
    const URL = 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7';

    private $tasks;

    public function saveTask(): string
    {
        // TODO: Implement saveTask() method.
    }

    public function parseTask():array
    {
        $this->tasks = [];
        $response = $this->getTask();
        
        foreach ($response as $item){
            foreach ($item as $key => $value){
                $task = new TaskEntity();
                $task->setLevel($value['level']);
                $task->setDuration($value['estimated_duration']);
                $task->setTitle($key);
                $this->tasks[] = $task;
            }
        }
        
        return $this->tasks;
    }

    /**
     * @return mixed
     */
    private function getTask()
    {
        return Util::makeRequest(self::URL,true);
    }
}