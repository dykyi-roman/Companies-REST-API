<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class CompanyFixtures
 */
class EmployeeFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $employee = $this->createEmployee($faker);
            $employee->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE.$i));
            $manager->persist($employee);
        }

        $manager->flush();
    }

    private function createEmployee(Generator $faker)
    {
        $employee = new Employee();
        $employee->setName($faker->firstName);
        $employee->setBirthday($faker->dateTime);
        $employee->setGender($faker->boolean);
        $employee->setPhone($faker->phoneNumber);
        $employee->setSalary($faker->unique()->numberBetween(5000, 7000));
        $employee->setNotes($faker->text);

        return $employee;
    }
}