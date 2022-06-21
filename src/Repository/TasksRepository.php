<?php

namespace App\Repository;

use App\Entity\Actions;
use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tasks>
 *
 * @method Tasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasks[]    findAll()
 * @method Tasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksRepository extends ServiceEntityRepository
{
    private $actionsRepository;

    public function __construct(ManagerRegistry $registry, ActionsRepository $actionsRepository)
    {
        parent::__construct($registry, Tasks::class);
        $this->actionsRepository = $actionsRepository;
    }

    public function add(Tasks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tasks $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUserId($value): array
    {
        return $this->createQueryBuilder('t')
           ->andWhere('t.user = :val')
           ->andWhere('t.archived = false')
           ->setParameter('val', $value)
           ->orderBy('t.id', 'DESC')
           ->getQuery()
           ->getResult()
           ;
    }

    public function isArchived($value) :array
    {
        return $this->createQueryBuilder('t')
           ->andWhere('t.user = :val')
           ->andWhere('t.archived = true')
           ->setParameter('val', $value)
           ->orderBy('t.id', 'DESC')
           ->getQuery()
           ->getResult()
           ;
    }

    public function associateAction($actions, $task) 
    {
        foreach ($actions as $key => $action) {
            if(isset($action['id']))
            {
                $currentAction = $this->actionsRepository->find($action['id']);
            }
            else
            {
                $currentAction = new Actions();
            }
            $actions[$key] = $currentAction->initiateAction($action);
            $task->addAction($actions[$key]);
        }
    }
//    /**
//     * @return Tasks[] Returns an array of Tasks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tasks
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
