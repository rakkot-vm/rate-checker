<?php

namespace App\Repository;

use App\Entity\ExchangeRateThreshold;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExchangeRateThreshold>
 *
 * @method ExchangeRateThreshold|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRateThreshold|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRateThreshold[]    findAll()
 * @method ExchangeRateThreshold[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRateThresholdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRateThreshold::class);
    }

    public function findAllIndexedByPair()
    {
        $qb = $this->createQueryBuilder('t');

        $query = $qb->indexBy('t', 't.pair')->getQuery();

        return $query->getResult();
    }

    public function updateOrAdd(ExchangeRateThreshold $threshold): void
    {
        $existsThreshold = $this->findOneBy([
            'pair' => $threshold->getPair(),
        ]);

        if ($existsThreshold !== null) {
            $existsThreshold->setRate($threshold->getRate());
            $existsThreshold->setMode($threshold->getMode());

            $this->getEntityManager()->flush($existsThreshold);
        } else {
            $this->getEntityManager()->persist($threshold);
        }
    }
}
