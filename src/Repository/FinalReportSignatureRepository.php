<?php

namespace App\Repository;

use App\Entity\FinalReportSignature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FinalReportSignature>
 *
 * @method FinalReportSignature|null find($id, $lockMode = null, $lockVersion = null)
 * @method FinalReportSignature|null findOneBy(array $criteria, array $orderBy = null)
 * @method FinalReportSignature[]    findAll()
 * @method FinalReportSignature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinalReportSignatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinalReportSignature::class);
    }

    public function save(FinalReportSignature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FinalReportSignature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FinalReportSignature[] Returns an array of FinalReportSignature objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FinalReportSignature
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
