<?php

namespace App\Repository;

/**
 * Interface RepositoryInterface
 * @package App\Repository
 */
interface RepositoryInterface
{
    /**
     * @param $entity
     * @param boolean $persist
     * @return mixed
     */
    public function save($entity, $persist);

    /**
     * @param int $id
     * @return mixed
     */
    public function remove(int $id);
}