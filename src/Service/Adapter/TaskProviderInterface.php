<?php

namespace App\Service\Adapter;

interface TaskProviderInterface {
    public function parseTask():array;
    public function saveTask():string;
}