<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function searchWhere($titre = "", $type = "", $page = 1, $limit = 5)
    {
        $entityManager = $this->getEntityManager();
        $titre = strtolower($titre);
        $type = strtolower($type);
        // $genre = strtolower($genre);

        $dql = $entityManager->createQuery(
            "SELECT l
            FROM App\Entity\Livre l
            WHERE LOWER(l.Titre) LIKE :titre 
             AND LOWER(l.Type) LIKE :type
            ORDER BY l.Titre ASC
            "
        )->setParameter('titre', '%' . $titre . '%')
            ->setParameter('type', '%' . $type . '%');

        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        // dd($paginator);
        return $paginator;
    }



    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
