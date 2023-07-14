<?php

namespace App\Repository;

use App\Entity\OurPartnerLogo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OurPartnerLogo>
 *
 * @method OurPartnerLogo|null find($id, $lockMode = null, $lockVersion = null)
 * @method OurPartnerLogo|null findOneBy(array $criteria, array $orderBy = null)
 * @method OurPartnerLogo[]    findAll()
 * @method OurPartnerLogo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OurPartnerLogoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OurPartnerLogo::class);
    }

    public function save(OurPartnerLogo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OurPartnerLogo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OurPartnerLogo[] Returns an array of OurPartnerLogo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OurPartnerLogo
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
