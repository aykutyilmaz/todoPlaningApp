<?php

namespace App\Repository;

use App\Entity\TaskEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TaskEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskEntity[]    findAll()
 * @method TaskEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TaskEntity::class);
    }
    
}
