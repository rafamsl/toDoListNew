<?php

namespace App\Repository;

use App\Entity\ToDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToDo>
 *
 * @method ToDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDo[]    findAll()
 * @method ToDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDo::class);
    }

    public function save(ToDo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ToDo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function newToDo(object $newToDo)
    {
        $toDo = new ToDo();
        $deadline = new \DateTimeImmutable($newToDo->deadline);
        $toDo->setTitle($newToDo->title);
        $toDo->setDescription($newToDo->description);
        $toDo->setDeadline($deadline);
        $toDo->setPinned($newToDo->pinned);
        $this->save($toDo, true);
    }

    public function editToDo(ToDo $oldToDo, object $newToDo)
    {
        if(property_exists($newToDo, 'title')){
            $oldToDo->setTitle($newToDo->title);
        }
        if(property_exists($newToDo, 'description')){
            $oldToDo->setDescription($newToDo->description);
        }
        if(property_exists($newToDo, 'pinned')){
            $oldToDo->setPinned($newToDo->pinned);
        }
        if(property_exists($newToDo, 'deadline')){
            $deadline = new \DateTimeImmutable($newToDo->deadline);
            $oldToDo->setDeadline($deadline);
        }
        $this->save($oldToDo, true);
    }
    public function finishToDo(ToDo $toDo){
        $toDo->setStatus(2);
        $this->save($toDo, true);
    }

        /**
     * @return ToDo[] Returns an array of ToDo objects
     */
    public function findByQuery($query)
    {
        $queryBuilder = $this->createQueryBuilder('todo')
            ->orderBy('todo.pinned','DESC');

        if ($query->get('after')){
            $after = new \DateTime($query->get('after'));
            $queryBuilder->andWhere('todo.deadline > :after')
                ->setParameter('after', $after)
                ->andWhere('todo.deadline IS NOT NULL');
        }
        if ($query->get('before')){
            $before = new \DateTime($query->get('before'));
            $queryBuilder->andWhere('todo.deadline < :before')
                ->setParameter('before', $before)
                ->andWhere('todo.deadline IS NOT NULL');
        }
        if ($query->get('title')){
            $queryBuilder->andWhere('todo.title LIKE :title')
                ->setParameter('title', '%'.$query->get('title').'%');
        }

        if ($query->get('status')){
            $queryBuilder->andWhere('todo.status = :status')
                ->setParameter('status',$query->get('status'));
        }

        if ($query->get('sortBy')&&$query->get('direction')){
            $order = 'todo.'.$query->get('sortBy');
            $direction = $query->get('direction');
            $queryBuilder->orderBy($order,$direction);
        }

        if ($query->get('page')&&$query->get('limit')){
            $queryBuilder = $this->addPagination($queryBuilder,$query->get('page'),$query->get('limit'));
        }

        return $queryBuilder
            ->getQuery()
            ->getResult()
            ;
    }

    public function addPagination($query, $page, $limit){
        return $query->setMaxResults($limit)
            ->setFirstResult(($page-1)*$limit);
    }
    public function expireToDos(){
        $date = new \DateTime();
        $toDos = $this->createQueryBuilder('todo')
            ->andWhere('todo.deadline IS NOT NULL')
            ->andWhere('todo.deadline < :date')
            ->andWhere('todo.status = 1')
            ->setParameter('date',$date)
            ->getQuery()
            ->getResult();

        foreach($toDos as $toDo) {
            $toDo->setStatus(3);
        }
        $this->getEntityManager()->flush();

    }

//    public function findOneBySomeField($value): ?ToDo
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
