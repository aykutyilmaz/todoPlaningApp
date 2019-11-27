<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\UserModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/", name="Todo_task_list")
     * @param \App\Service\TaskService $taskService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(TaskService $taskService):Response
    {
        // Bütün taskları getir
        $allTasks = $taskService->getAllTasks();
        
        // Developer Listesini getir.
        $devs = UserModel::developers;
        
        foreach ($allTasks as $item){
            $dev = &$devs[array_search($item->getLevel(), array_column($devs, 'difficulty'))];
            
            // Taskları level'a göre Developerlara atıyoruz
            array_push($dev['tasks'],
                ["name" => $item->getTitle(), "level"=>$item->getLevel(), "duration"=> $item->getDuration()]);
            
            // İşi yapan kişinin ne kadar sürecede yapacagını hesaplayıp toplam zamana ekliyoruz.
            $dev["totalTaskTime"] += ($item->getDuration() / (int)substr($dev['difficulty'], 0, strpos($dev['difficulty'], 'x')));
        }
        
        $backlogs = [];
        
        foreach ($devs as &$item){
            // Süreye göre sıralıyoruz
            array_multisort(array_column($item['tasks'], 'duration'), SORT_DESC, $item['tasks']);
            
            // Haftalık çalışma saatinden fazla iş atanan kişilerin üzerinden işleri backlog'a alıyoruz.
            if($item['totalTaskTime'] > UserModel::WeeklyWorkingHours) {
                foreach ($item['tasks'] as $key => &$task) {
                    if($item['totalTaskTime'] <= UserModel::WeeklyWorkingHours) {
                        continue;
                    }
                    
                    array_push($backlogs, $task);
                    $item['totalTaskTime'] -= $task['duration'];
    
                    unset($item['tasks'][$key]);
                }
            }
        }
        
        // Developerlar üzerlerindeki islerine göre azdan çoka sıralanıyor.
        array_multisort(array_column($devs, 'totalTaskTime'), SORT_ASC, $devs);
        
        // Tasklar sürelerine göre çoktan aza sıralanıyor.
        array_multisort(array_column($backlogs, 'duration'), SORT_DESC, $backlogs);
        
        // developerlara haftalık çalışma saatlerini doldurabilacek kadar işleri atıyoruz.
        foreach ($devs as &$dev){
            if($dev['totalTaskTime'] >= UserModel::WeeklyWorkingHours) {
                continue;
            }
    
            foreach ($backlogs as $key => &$task){
                if($dev['totalTaskTime'] + $task['duration'] /
                    (int)substr($dev['difficulty'], 0, strpos($dev['difficulty'], 'x')) >= UserModel::WeeklyWorkingHours) {
                    continue;
                }
                
                array_push($dev['tasks'], $task);
                
                $dev['totalTaskTime'] += $task['duration'] / (int)substr($dev['difficulty'], 0, strpos($dev['difficulty'], 'x'));
                
                unset($backlogs[$key]);
            }
        }
    
        array_multisort(array_column($devs, 'Name'), SORT_ASC, $devs);
        
    
        return $this->render('base.html.twig', array(
            'developers' => $devs,
            'backlogs' => $backlogs
        ));
        
    }
}