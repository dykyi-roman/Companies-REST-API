<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Employee;
use App\Form\FormType\CompanyType;
use App\Form\FormType\EmployeeType;
use App\Repository\Company\CompanyRepository;
use App\Repository\Employee\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommonController
 * @package App\Controller
 */
abstract class CommonController extends Controller
{
    protected function getCompanyRepository(): CompanyRepository
    {
        return $this->getDoctrine()->getRepository(Company::class);
    }

    protected function getEmployeeRepository(): EmployeeRepository
    {
        return $this->getDoctrine()->getRepository(Employee::class);
    }

    /**
     * @param Request $request
     * @return Company
     */
    protected function getCompanyFromAttribute(Request $request): Company
    {
        $companyId = $request->attributes->get('companyId');
        return $this->getCompanyRepository()->getOneById($companyId);
    }

    protected function saveEmployee(Employee $employee, array $data)
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->submit($data);
        $this->getEmployeeRepository()->save($employee);
    }

    protected function saveCompany(Company $company, array $data)
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->submit($data);
        $this->getCompanyRepository()->save($company);
    }

}