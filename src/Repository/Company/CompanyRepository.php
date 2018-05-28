<?php

namespace App\Repository\Company;

use App\Repository\CommonRepository;
use App\Repository\RepositoryInterface;

/**
 * Class CompanyRepository
 * @package App\Repository
 */
class CompanyRepository extends CommonRepository implements RepositoryInterface, CompanyRepositoryInterface
{
    public const ERROR_MESSAGE = 'Not found Company %s';

    /**
     * {@inheritdoc}
     *
     */
    public function getOneById(int $id)
    {
        $entity = $this->find($id);
        $this->assertEntity($entity, $id);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        $entices = $this->findAll();
        $this->assertEntices($entices);

        return $entices;
    }
}