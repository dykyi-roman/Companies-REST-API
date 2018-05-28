<?php

namespace App\Repository\Employee;

use App\Entity\Company;
use App\Repository\CommonRepository;
use App\Repository\RepositoryInterface;

/**
 * Class EmployeeRepository
 * @package App\Repository
 */
class EmployeeRepository extends CommonRepository implements RepositoryInterface, EmployeeRepositoryInterface
{
    public const ERROR_MESSAGE = 'Not Fount Employee %s';


    /**
     * {@inheritdoc}
     */
    public function getAllByCompanyId($companyId)
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin(Company::class, 'c', 'WITH', 'e.company = c.id')
            ->where('e.company = :companyId')
            ->setParameter('companyId', $companyId)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getOneByCompanyId($companyId, $id)
    {
        $entity = $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin(Company::class, 'c', 'WITH', 'e.company = c.id')
            ->where('e.company = :companyId and e.id = :employeeId')
            ->setParameters(['companyId' => $companyId, 'employeeId' => $id])
            ->getQuery()
            ->execute();

        $this->assertEntity($entity, $id);

        return $entity[0];
    }

    public function getAllByEmployers($companyId, $employeeId)
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin(Company::class, 'c', 'WITH', 'e.company = c.id')
            ->where('e.company = :companyId and e.dependant_id = :employeeId')
            ->setParameters(['companyId' => $companyId, 'employeeId' => $employeeId])
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getOneByCompanyIdAndEmployee($companyId, $employeeId, $id)
    {
        $entity = $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin(Company::class, 'c', 'WITH', 'e.company = c.id')
            ->where('e.company = :companyId and e.id = :employeeId and e.dependant_id = :dependant_id')
            ->setParameters([
                'companyId' => $companyId,
                'employeeId' => $employeeId,
                'dependant_id' => $id,
            ])
            ->getQuery()
            ->execute();

        $this->assertEntity($entity, $id);

        return $entity[0];
    }

}