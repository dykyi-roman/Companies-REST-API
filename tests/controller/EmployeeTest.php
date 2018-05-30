<?php

namespace App\Tests;

use Faker\Factory;
use GuzzleHttp\Psr7\Request;
use App\Tests\controller\src\ApiClient;
use App\Tests\controller\src\ResponseDataExtractor;
use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class CompanyTest
 */
class EmployeeTest extends TestCase
{
    /** @var ApiClient  */
    private $client;

    /** @var \Faker\Generator */
    private $faker;

    /** @var ResponseDataExtractor  */
    private $extractor;

    private $company;
    private $employee;

    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
        (new Dotenv())->load(__DIR__ . '/../../.env');

        $this->client = new ApiClient(new GuzzleClient, getenv('API_URL'));
        $this->extractor = new ResponseDataExtractor();
    }

    public function setUp()
    {
        $this->company = $this->createCompany();
        $this->employee = $this->createEmployee($this->company['id']);
    }

    public function testCreateEmployee()
    {
        $this->assertEquals(true, is_int($this->employee['id']));
    }

    public function testGetEmployees()
    {
        $response = $this->client->send(new Request('GET', sprintf('company/%s/employers', $this->company['id'])));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('employers', $this->extractor->extract($response));
    }

    public function testGetEmployee()
    {
        $url = sprintf('company/%s/employee/%s', $this->company['id'], $this->employee['id']);
        $request = new Request('GET', $url);
        $response = $this->client->send($request);
        $this->assertEquals(200, $response->getStatusCode());

        $data = $this->extractor->extract($response);
        $this->assertArrayHasKey('employee', $data);
        $this->assertEquals(6, count($data['employee']));
    }

    public function testUpdateEmployee()
    {
        $url = sprintf('company/%s/employee/%s', $this->company['id'], $this->employee['id']);
        $request = new Request('PUT', $url, [],
            json_encode([
                'name' => $this->faker->firstName,
                'phone' => $this->faker->phoneNumber,
                'gender' => (int)$this->faker->boolean,
                'salary' => random_int(100,7000),
                'notes' => $this->faker->text,
                'birthday' => '2000-03-03',
            ])
        );
        $response = $this->client->send($request);
        $this->assertEquals(201, $response->getStatusCode());

        $data = $this->extractor->extract($response);
        $this->assertArrayHasKey('employee', $data);
        $this->assertEquals(6, count($data['employee']));
    }

    public function testDeleteCompany()
    {
        $url = sprintf('company/%s/employee/%s', $this->company['id'], $this->employee['id']);
        $request = new Request('DELETE', $url);
        $response = $this->client->send($request);

        $this->assertArrayHasKey('deleted', $this->extractor->extract($response));
    }

    private function createEmployee(int $companyId)
    {
        $url = sprintf('company/%s/employee', $companyId);
        $request = new Request('POST', $url, [], json_encode([
                'name' => $this->faker->firstName,
                'phone' => $this->faker->phoneNumber,
                'gender' => (int)$this->faker->boolean,
                'salary' => random_int(100,7000),
                'notes' => $this->faker->text,
                'birthday' => '2000-03-03',
            ])
        );
        $response = $this->client->send($request);

        return $this->extractor->extract($response);
    }

    private function createCompany()
    {
        $request = new Request('POST', 'company', [], json_encode([
                'name' => $this->faker->name,
                'address' => $this->faker->address
            ])
        );
        $response = $this->client->send($request);

        return $this->extractor->extract($response);
    }
}