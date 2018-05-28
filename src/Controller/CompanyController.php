<?php

namespace App\Controller;

use App\DTO\CompanyDTO;
use App\Entity\Company;
use App\Exception\RepositoryDataNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as FOSRest;

/**
 * @SWG\Info(title="Companies REST API", version="1.0")
 *
 * @SWG\Get(
 *     path="/companies",
 *     summary="Get all companies",
 *     @SWG\Response(response="200", description="OK"),
 *     tags={"Company"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Get(
 *     path="/company/{id}",
 *     summary="Get company by id",
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Company by ID"),
 *     tags={"Company"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Post(
 *     path="/company",
 *     summary="Create a new company",
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
 *                  property="address",
 *                  type="string"
 *              )
 *          )
 *     ),
 *     @SWG\Response(response="200", description="Create a new company"),
 *     tags={"Company"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Put(
 *     path="/company/{id}",
 *     summary="Update a new company",
 *
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Company by ID"),
 *     tags={"Company"},
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Delete(
 *     path="/file/{id}",
 *     summary="Delete company by ID",
 *     @SWG\Response(response="200", description="OK"),
 *     @SWG\Response(response="404", description="Not found Company by ID"),
 *     @SWG\Response(response="500", description="You can not delete this company, because in company have a employee"),
 *     tags={"Company"},
 *     security={{"Dykyi":{}}}
 * )
 *
 */

/**
 * @Route("/api")
 *
 * Class CompanyController
 * @package App\Controller
 */
class CompanyController extends CommonController
{
    /**
     * Create Company
     *
     * @FOSRest\Post("/company")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $company = new Company();
        $this->saveCompany($company, $request->request->all());

        return JsonResponse::create(['id' => $company->getId()]);
    }

    /**
     * Get company
     *
     * @FOSRest\Get("/companies")
     *
     * @return JsonResponse
     */
    public function readsAction(): JsonResponse
    {
        $data = $this->getCompanyRepository()->getAll();
        return JsonResponse::create(['data' => $data]);
    }

    /**
     * Get company
     *
     * @FOSRest\Get("/company/{id}")
     *
     * @param int $id
     * @return JsonResponse
     */
    public function readAction(int $id): JsonResponse
    {
        $data = $this->getCompanyRepository()->getOneById($id);

        return JsonResponse::create(['data' => CompanyDTO::serialize($data)]);
    }


    /**
     * Update company
     *
     * @FOSRest\Put("/company/{id}")
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateAction(Request $request, int $id = 0): JsonResponse
    {
        /** @var Company $company */
        $company = $this->getCompanyRepository()->getOneById($id);
        $this->saveCompany($company, $request->request->all());

        return JsonResponse::create(['data' => CompanyDTO::serialize($company)]);
    }

    /**
     * Delete Company
     *
     * @FOSRest\Delete("/company/{id}")
     *
     * @param int $id
     * @return JsonResponse
     * @internal param Request $request
     */
    public function deleteAction(int $id): JsonResponse
    {
        if (empty($this->getEmployeeRepository()->getAllByCompanyId($id)))
        {
            $this->getCompanyRepository()->remove($id);
            return JsonResponse::create(['deleted' => true]);
        }

        throw new RepositoryDataNotFoundException('You can not delete this company, because in company have a employee', 500);
    }
}