<?php

namespace App\DTO;

use App\Entity\Company;

class CompanyDTO
{
    private function __construct()
    {
    }

    /**
     * @param Company $company
     * @return array
     */
    public static function serialize(Company $company): array
    {
        return [
            'name' => $company->getName(),
            'address' => $company->getAddress(),
        ];
    }
}