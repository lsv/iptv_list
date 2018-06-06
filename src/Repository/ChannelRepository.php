<?php

namespace App\Repository;

use App\Entity\Channel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Channel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Channel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Channel[]    findAll()
 * @method Channel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChannelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Channel::class);
    }

    public function save($object, bool $flush = true): void
    {
        if ($object) {
            $this->_em->persist($object);
        }

        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Channel[]
     */
    public function findAllChannels(): array
    {
        return $this->getBuilder()->getQuery()->getResult();
    }

    public function findCountries(): array
    {
        $qb = $this->createQueryBuilder('channel');
        $qb->select('channel.country');
        $qb->distinct(true);
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param string $country
     *
     * @return Channel[]
     */
    public function findChannelsByCountry(?string $country): array
    {
        $qb = $this->getBuilder();
        if ($country) {
            $qb->andWhere($qb->expr()->eq('channel.country', ':country'));
            $qb->setParameter(':country', $country);
        } else {
            $qb->andWhere($qb->expr()->isNull('channel.country'));
        }
        return $qb->getQuery()->getResult();
    }

    protected function getBuilder($alias = 'channel'): QueryBuilder
    {
        $qb = $this->createQueryBuilder($alias);
        $qb->join($alias . '.links', 'links');
        $qb->addSelect('links');
        $qb->addOrderBy($alias . '.name', 'ASC');
        return $qb;
    }

}
