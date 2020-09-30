<?php

namespace App\Repository;

use App\Entity\HolidaySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HolidaySearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method HolidaySearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method HolidaySearch[]    findAll()
 * @method HolidaySearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HolidaySearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HolidaySearch::class);
    }

    public function findByYearAndCountry($country, $year)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.country = :country')
            ->setParameter('country', $country)
            ->andWhere('h.year = :year')
            ->setParameter('year', $year)
            ->orderBy('h.holidayDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return HolidaySearch[] Returns an array of HolidaySearch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HolidaySearch
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
