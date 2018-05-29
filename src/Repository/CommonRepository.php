<?php

namespace App\Repository;

use App\Exception\RepositoryDataNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * Class CommonRepository
 * @package App\Repository
 */
abstract class CommonRepository extends EntityRepository
{
    /**
     * @param $entity
     * @param bool $persist
     */
    public function save($entity, $persist = true): void
    {
        $em = $this->getEntityManager();
        if ($persist) {
            $em->persist($entity);
        }
        $em->flush();
    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {
        $entity = $this->find($id);
        $this->assertEntity($entity, $id);

        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }

    /**
     * @param $entity
     * @param int $id
     */
    protected function assertEntity($entity, int $id): void
    {
        if ($entity === null || empty($entity)) {
            $message = sprintf((static::class)::ERROR_MESSAGE, 'by ID ' . $id);
            throw new RepositoryDataNotFoundException($message, 404);
        }
    }

    /**
     * @param $entity
     */
    protected function assertEntices($entity): void
    {
        if (empty($entity)) {
            $message = sprintf((static::class)::ERROR_MESSAGE, '');
            throw new RepositoryDataNotFoundException($message, 404);
        }
    }
}