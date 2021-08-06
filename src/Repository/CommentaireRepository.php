<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    /**
      * @return Int 
      */
    public function NumberofComments($formation)
    {
        return $this->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.formation = :formation')
                    ->andWhere('c.etat = true')
                    ->setParameter('formation', $formation)
                    ->getQuery()
                    ->getSingleScalarResult();
    }
}
