<?php

namespace App\Controller;

use App\DTO\EmployeeDTO;
use App\Entity\Employee;
use App\Exception\RepositoryDataNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @SWG\Get(
 *     path="/api/company/{companyId}/employers",
 *     summary="Get all employers by company",
 *     @SWG\Response(response="200", description="OK"),
 *     tags={"Employee"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Get(
 *     path="/api/company/{companyId}/employee/{id}",
 *     summary="Get employee by id from company",
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Employee by ID"),
 *     tags={"Employee"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Post(
 *     path="/api/company/{companyId}/employee",
 *     summary="Create a new employee",
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
 *              )
 *          )
 *     ),
 *     @SWG\Response(response="200", description="Create a new employee"),
 *     tags={"Employee"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Put(
 *     path="/api/company/{companyId}/employee/{id}",
 *     summary="Update a employee",
 *
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Employee by ID"),
 *     tags={"Employee"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Delete(
 *     path="/api/company/{companyId}/employee/{id}",
 *     summary="Delete employee by ID",
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Employee by ID"),
 *     @SWG\Response(response="500", description="You can not delete this employee, because in employee have a dependants"),
 *     tags={"Employee"},
 *     security={{"Dykyi":{}}}
 * )
 *
 */

/**
 * @Route("/api/company")
 *
 * Class EmployeeController
 * @package App\Controller
 */
class EmployeeController extends CommonController
{
    /**
     * Create Employee
     *
     * @FOSRest\Post("/{companyId}/employee")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $company = $this->getCompanyFromAttribute($request);
        $employee = new Employee();
        $employee->setCompany($company);

        $this->saveEmployee($employee, $request->request->all());

        return JsonResponse::create(['id' => $employee->getId()]);
    }

    /**
     * Get Employers
     *
     * @FOSRest\Get("/{companyId}/employers")
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function readsAction(int $companyId): JsonResponse
    {
        $data = $this->getEmployeeRepository()->getAllByCompanyId($companyId);

        return JsonResponse::create(['employers' => EmployeeDTO::serializes($data)]);
    }

    /**
     * Get Employee
     *
     * @FOSRest\Get("/{companyId}/employee/{id}")
     *
     * @param int $companyId
     * @param int $id
     * @return JsonResponse
     */
    public function readAction(int $companyId, int $id): JsonResponse
    {
        $data = $this->getEmployeeRepository()->getOneByCompanyId($companyId, $id);

        return JsonResponse::create(['employee' => EmployeeDTO::serialize($data)]);
    }

    /**
     * Update Employee
     *
     * @FOSRest\Put("/{companyId}/employee/{id}")
     *
     * @param Request $request
     * @param int $companyId
     * @param int $id
     * @return JsonResponse
     */
    public function updateAction(Request $request, int $companyId, int $id = 0): JsonResponse
    {
        $employee = $this->getEmployeeRepository()->getOneByCompanyId($companyId, $id);
        $this->saveEmployee($employee, $request->request->all(), false);

        return JsonResponse::create(['employee' => EmployeeDTO::serialize($employee)], 201);
    }

    /**
     * Delete Employee
     *
     * @FOSRest\Delete("/{companyId}/employee/{id}")
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteAction(int $companyId, int $id): JsonResponse
    {
        $this->getCompanyRepository()->getOneById($companyId);

        if (empty($this->getEmployeeRepository()->getAllByEmployers($companyId, $id))) {
            $this->getEmployeeRepository()->remove($id);
            return JsonResponse::create(['deleted' => true]);
        }

        throw new RepositoryDataNotFoundException('You can not delete this employee, because in employee have a dependants', 500);
    }

}