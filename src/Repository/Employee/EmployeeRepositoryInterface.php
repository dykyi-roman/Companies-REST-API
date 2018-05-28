<?php

namespace App\Repository\Employee;

/**
 * Interface EmployeeRepositoryInterface
 * @package App\Repository
 */
interface EmployeeRepositoryInterface
{
    /**
     * @param $companyId
     * @return mixed
     * @throws \App\Exception\RepositoryDataNotFoundException
     */
    public function getAllByCompanyId($companyId);

    /**
     * @param $companyId
     * @param $id
     * @return mixed
     * @throws \App\Exception\RepositoryDataNotFoundException
     */
    public function getOneByCompanyId($companyId, $id);
}