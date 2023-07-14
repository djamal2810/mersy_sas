<?php

namespace App\Repository;

use App\Entity\RejectionMotive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RejectionMotive>
 *
 * @method RejectionMotive|null find($id, $lockMode = null, $lockVersion = null)
 * @method RejectionMotive|null findOneBy(array $criteria, array $orderBy = null)
 * @method RejectionMotive[]    findAll()
 * @method RejectionMotive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RejectionMotiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RejectionMotive::class);
    }

    public function save(RejectionMotive $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RejectionMotive $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RejectionMotive[] Returns an array of RejectionMotive objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RejectionMotive
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
