<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class CompanyFixtures
 */
class CompanyFixtures extends Fixture
{
    const COMPANY_REFERENCE = 'company-ref';

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $company = $this->createCompany($faker);
            $manager->persist($company);
            $this->addReference(self::COMPANY_REFERENCE.$i, $company);
        }

        $manager->flush();
    }

    private function createCompany(Generator $faker)
    {
        $company = new Company();
        $company->setName($faker->name);
        $company->setAddress($faker->address);

        return $company;
    }
}