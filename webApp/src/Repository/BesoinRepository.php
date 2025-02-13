<?php

namespace App\Repository;

use App\Entity\Besoin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Besoin>
 */
class BesoinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Besoin::class);
    }

    //    /**
    //     * @return Besoin[] Returns an array of Besoin objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Besoin
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllBesoins(): array
    {   
        return $this->getEntityManager()->getRepository(Besoin::class)
            ->createQueryBuilder('b')
            ->leftJoin('b.competences', 'c')
            ->leftJoin('b.salaries', 's')
            ->addSelect('c', 's')
            ->orderBy('b.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllBesoinsByClientId($id): array
    {
        return $this->getEntityManager()->getRepository(Besoin::class)
            ->createQueryBuilder('b')
            ->leftJoin('b.competences', 'c')
            ->leftJoin('b.salaries', 's')
            ->addSelect('c', 's')
            ->where('b.client_id = :id')
            ->setParameter('id', $id)
            ->orderBy('b.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
