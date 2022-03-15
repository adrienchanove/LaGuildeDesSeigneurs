<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    public function findOneByIdentifier($identifier)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c', 'p')
            ->leftJoin('c.player', 'p')
            ->where('c.identifier = :identifier')
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //find many character by minimum intelligence
    public function findManyByIntelligence($lvl)
    {
        return $this
            ->createQueryBuilder('c')
            ->select('c', 'p')
            ->leftJoin('c.player', 'p')
            ->where('c.intelligence >= :lvl')
            ->setParameter('lvl', $lvl)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Character
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
