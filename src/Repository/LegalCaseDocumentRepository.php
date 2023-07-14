<?php

namespace App\Repository;

use App\Entity\LegalCaseDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LegalCaseDocument>
 *
 * @method LegalCaseDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalCaseDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalCaseDocument[]    findAll()
 * @method LegalCaseDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalCaseDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalCaseDocument::class);
    }

    public function save(LegalCaseDocument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LegalCaseDocument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return LegalCaseDocument[] Returns an array of LegalCaseDocument objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LegalCaseDocument
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
