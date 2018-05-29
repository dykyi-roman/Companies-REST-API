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
    private $client;
    private $company;
    private $employee;

    /** @var \Faker\Generator */
    private $faker;

    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
        (new Dotenv())->load(__DIR__ . '/../../.env');

        $this->client = new ApiClient(new GuzzleClient, new ResponseDataExtractor(), getenv('API_URL'));
    }

    public function setUp()
    {
        $this->company = $this->createCompany();
        $this->employee = $this->createEmployee($this->company->id);
    }

    public function testCreateEmployee()
    {
        $this->assertEquals(true, is_int($this->employee->id));
    }

    public function testGetEmployees()
    {
        $response = $this->client->send(new Request('GET', sprintf('company/%s/employers', $this->company->id)));
        $this->assertTrue(true, is_array($response));
    }

    public function testGetEmployee()
    {
        $url = sprintf('company/%s/employee/%s', $this->company->id, $this->employee->id);
        $request = new Request('GET', $url);
        $response = $this->client->send($request);
        $this->assertTrue(true, array_key_exists('data', $response));
    }

    public function testUpdateEmployee()
    {
        $url = sprintf('company/%s/employee/%s', $this->company->id, $this->employee->id);
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
        $this->assertTrue(true, array_key_exists('data', $response));
    }

    public function testDeleteCompany()
    {
        $url = sprintf('company/%s/employee/%s', $this->company->id, $this->employee->id);
        $request = new Request('DELETE', $url);
        $response = $this->client->send($request);

        $this->assertTrue(true, array_key_exists('deleted', $response));
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

        return $this->client->send($request);
    }

    private function createCompany()
    {
        $request = new Request('POST', 'company', [], json_encode([
                'name' => $this->faker->name,
                'address' => $this->faker->address
            ])
        );

        return $this->client->send($request);
    }
}