<?php

namespace App\Repository;

use App\Entity\CartType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CartType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartType[]    findAll()
 * @method CartType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CartType::class);
    }

    // /**
    //  * @return CartType[] Returns an array of CartType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CartType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
