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
     * @return mixed
     */
    public function save($entity);

    /**
     * @param int $id
     * @return mixed
     */
    public function remove(int $id);
}