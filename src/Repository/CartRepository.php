<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    // /**
    //  * @return Cart[] Returns an array of Cart objects
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
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByUserId($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCartProductsByUserId($user_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT c.id as cart_id, p.price * cp.quantity as subtotal, cp.quantity, cp.product_id, p.price, p.name, p.image, p.description
            FROM cart c
            left join cart_product cp
            on c.id = cp.cart_id
            join product p
            on cp.product_id = p.id
            WHERE c.user_id = :user_id
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        return $stmt->fetchAll();
    }
}
