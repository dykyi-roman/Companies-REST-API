<?php

namespace App\Controller;

use App\DTO\EmployeeDTO;
use App\Entity\Company;
use App\Entity\Employee;
use App\Exception\RepositoryDataNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @SWG\Get(
 *     path="/api/company/{companyId}/employee/{employeeId}/dependants",
 *     summary="Get all dependants from employee",
 *     @SWG\Response(response="200", description="OK"),
 *     tags={"Dependant"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Post(
 *     path="/api/company/{companyId}/employee/{employeeId}/dependant",
 *     summary="Create a new dependant",
 *     @SWG\Parameter(
 *          name="body",
 *          in="body",
 *          required=true,
 *          @SWG\Schema(
 *              @SWG\Property(
 *                  property="name",
 *                  type="string"
 *              ),
 *              @SWG\Property(
 *                  property="phone",
 *                  type="string"
 *              ),
 *              @SWG\Property(
 *                  property="gender",
 *                  type="boolean"
 *              ),
 *              @SWG\Property(
 *                  property="birthday",
 *                  type="datetime"
 *              ),
 *              @SWG\Property(
 *                  property="salary",
 *                  type="float"
 *              ),
 *              @SWG\Property(
 *                  property="notes",
 *                  type="string"
 *              ),
 *              @SWG\Property(
 *                  property="dependant_id",
 *                  type="integer"
 *              )
 *          )
 *     ),
 *     @SWG\Response(response="200", description="Create a new dependant"),
 *     tags={"Dependant"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Put(
 *     path="/api/company/{companyId}/employee/{employeeId}/dependant/{id}",
 *     summary="Update a dependant",
 *
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Dependant by ID"),
 *     tags={"Dependant"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Delete(
 *     path="/api/company/{companyId}/employee/{employeeId}/dependant/{id}",
 *     summary="Delete dependant by ID",
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Dependant by ID"),
 *     tags={"Dependant"},
 *     security={{"Dykyi":{}}}
 * )
 *
 */

/**
 * @Route("/api/company")
 *
 * Class DependantController
 * @package App\Controller
 */
class DependantController extends CommonController
{
    /**
     * Create Dependant
     *
     * @FOSRest\Post("/{companyId}/employee/{employeeId}/dependant")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $company = $this->getCompanyFromAttribute($request);
        $employee = $this->getEmployeeFromAttribute($request, $company);
        $dependant = $this->createDependant($company, $employee);

        $this->saveEmployee($dependant, $request->request->all());

        return JsonResponse::create(['id' => $dependant->getId()]);
    }

    /**
     * Get Dependant
     *
     * @FOSRest\Get("/{companyId}/employee/{employeeId}/dependants")
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function readsAction(int $companyId, int $employeeId): JsonResponse
    {
        $data = $this->getEmployeeRepository()->getAllByEmployers($companyId, $employeeId);
        return JsonResponse::create(['data' => EmployeeDTO::serializes($data)]);
    }

    /**
     * Update Dependant
     *
     * @FOSRest\Put("/{companyId}/employee/{employeeId}/dependant/{id}")
     *
     * @param Request $request
     * @param int $companyId
     * @param int $id
     * @return JsonResponse
     */
    public function updateAction(Request $request, int $companyId, int $employeeId, int $id = 0): JsonResponse
    {
        $this->getCompanyRepository()->getOneById($companyId);
        $dependant = $this->getEmployeeRepository()->getOneByCompanyId($companyId, $id);

        if ($dependant->getDependant() == $employeeId) {
            $this->saveEmployee($dependant, $request->request->all(), false);
            return JsonResponse::create(['data' => EmployeeDTO::serialize($dependant)]);
        }

        throw new RepositoryDataNotFoundException('Dependant not found in Employee', 404);
    }

    /**
     * Delete Dependant
     *
     * @FOSRest\Delete("/{companyId}/employee/{employeeId}/dependant/{id}")
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteAction(int $companyId, int $employeeId, int $id): JsonResponse
    {
        $this->getCompanyRepository()->getOneById($companyId);
        $dependant = $this->getEmployeeRepository()->getOneByCompanyId($companyId, $id);

        if ($dependant->getDependant() == $employeeId) {
            $this->getEmployeeRepository()->remove($id);
            return JsonResponse::create(['deleted' => true]);
        }

        throw new RepositoryDataNotFoundException('Dependant not found in Employee', 404);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return Employee
     */
    private function getEmployeeFromAttribute(Request $request, Company $company): Employee
    {
        /** @var Employee $employee */
        $employeeId = $request->attributes->get('employeeId');
        return $this->getEmployeeRepository()->getOneByCompanyId($company->getId(), $employeeId);
    }

    /**
     * @param Company $company
     * @param Employee $employee
     * @return Employee
     */
    private function createDependant(Company $company, Employee $employee): Employee
    {
        $dependant = new Employee();
        $dependant->setCompany($company);
        $dependant->setDependant($employee->getId());

        return $dependant;
    }

}