<?php

namespace App\Repository\Company;

/**
 * Interface CompanyRepositoryInterface
 * @package AppBundle\Repository
 */
interface CompanyRepositoryInterface
{
    /**
     * @param $id
     * @return mixed|null
     * @throws \App\Exception\RepositoryDataNotFoundException
     */
    public function getOneById(int $id);

    /**
     * @return array
     * @throws \App\Exception\RepositoryDataNotFoundException
     */
    public function getAll(): array;
}