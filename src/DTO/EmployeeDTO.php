<?php

namespace App\DTO;

use App\Entity\Employee;

/**
 * Class EmployeeDTO
 * @package App\DTO
 */
class EmployeeDTO
{
    private function __construct()
    {
    }

    /**
     * @param Employee $employee
     * @return array
     */
    public static function serialize(Employee $employee): array
    {
        return [
            'name' => $employee->getName(),
            'phone' => $employee->getPhone(),
            'birthday' => $employee->getBirthday(),
            'gender' => $employee->getGender(),
            'salary' => $employee->getSalary(),
            'notes' => $employee->getNotes(),
        ];
    }

    /**
     * @param array $employers
     * @return array
     */
    public static function serializes(array $employers): array
    {
        $result = [];
        if (is_array($employers))
        {
            foreach ($employers as $employee) {
                $result[] = self::serialize($employee);
            }
        }

        return $result;
    }
}