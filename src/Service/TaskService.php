<?php
namespace App\Service;

use App\Entity\TaskEntity;
use App\Helper\Util;
use App\Repository\TasksRepository;
use App\Service\Adapter\TaskProviderInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TaskService
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
 * @var LoggerInterface $em
 */
    private $logger;

    /**
     * @var TasksRepository $em
     */
    private $tasksRepository;

    /**
     * TaskService constructor.
     *
     * @param EntityManagerInterface                 $entityManager
     * @param \Psr\Log\LoggerInterface               $logger
     * @param \App\Repository\TasksRepository $tasksRepository
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, TasksRepository $tasksRepository)
    {
        $this->em                = $entityManager;
        $this->tasksRepository   = $tasksRepository;
        $this->logger            = $logger;
    }

    /**
     * updates exchange rates
     */
    public function getTasks(): void
    {
        try {
            $tasks = $this->getTasksFromProviders();
            $this->addTasks($tasks);
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }
    }
    

    private function getTasksFromProviders(): array
    {
        $providerPaths = Util::getImplementingClasses(TaskProviderInterface::class);
        
        $tasks = [];

        try {
            /** @var TaskProviderInterface $provider */
            foreach ($providerPaths as $item){
                $provider = new $item();
                $tasks[] = $provider->parseTask();
            }
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }

        return $tasks;
    }

    /**
     * @param $tasks
     */
    private function addTasks($tasks): void
    {
        try{
            foreach ($tasks as $item){
                foreach ($item as $value){
                    $this->em->persist($value);
                    $this->em->flush($value);
                }
            }
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }
    }
    
    public function getAllTasks() {
        $criteria = Criteria::create();
        return $this->tasksRepository->matching($criteria)->toArray();
    }
}
