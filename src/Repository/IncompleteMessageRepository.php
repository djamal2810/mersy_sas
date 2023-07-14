<?php

namespace App\Repository;

use App\Entity\IncompleteMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IncompleteMessage>
 *
 * @method IncompleteMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncompleteMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncompleteMessage[]    findAll()
 * @method IncompleteMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncompleteMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncompleteMessage::class);
    }

    public function save(IncompleteMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IncompleteMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return IncompleteMessage[] Returns an array of IncompleteMessage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IncompleteMessage
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
