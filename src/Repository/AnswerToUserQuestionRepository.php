<?php

namespace App\Repository;

use App\Entity\AnswerToUserQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnswerToUserQuestion>
 *
 * @method AnswerToUserQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerToUserQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerToUserQuestion[]    findAll()
 * @method AnswerToUserQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerToUserQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerToUserQuestion::class);
    }

    public function save(AnswerToUserQuestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AnswerToUserQuestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AnswerToUserQuestion[] Returns an array of AnswerToUserQuestion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnswerToUserQuestion
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
