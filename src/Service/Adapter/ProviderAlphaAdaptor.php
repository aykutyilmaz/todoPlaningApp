<?php

namespace App\Service\Adapter;

use App\Entity\TaskEntity;
use App\Helper\Util;

class ProviderAlphaAdaptor implements TaskProviderInterface
{
    const URL = 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa';

    private $tasks;

    public function saveTask(): string
    {
        $tasks = $this->exchangeRates;
        if (!empty($tasks)) {
            foreach ($tasks as $item){
                //save db $item
            }
        }
    }

    /**
     * @return array
     */
    public function parseTask():array
    {
        $this->tasks = [];
        $response = $this->getTask();
        
        foreach ($response as $item){
            $task = new TaskEntity();
            
            $task->setLevel($item['zorluk']);
            $task->setDuration($item['sure']);
            $task->setTitle($item['id']);
            $this->tasks[] = $task;
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