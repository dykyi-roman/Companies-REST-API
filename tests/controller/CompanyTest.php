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
class CompanyTest extends TestCase
{
    private $client;
    private $company;

    /** @var \Faker\Generator  */
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
    }

    public function testCreateCompany()
    {
        $this->assertEquals(true, is_int($this->company->id));
    }

    public function testGetCompanies()
    {
        $response = $this->client->send(new Request('GET', 'companies'));
        $this->assertTrue(true, is_array($response));
    }

    public function testGetCompany()
    {
        $request = new Request('GET', 'company/' . $this->company->id);
        $response = $this->client->send($request);
        $this->assertTrue(true, array_key_exists('data', $response));
    }

    public function testUpdateCompany()
    {
        $request = new Request('PUT', 'company/' . $this->company->id, [],
            json_encode([
                'name' => $this->faker->name,
                'address' => $this->faker->address
            ])
        );
        $response = $this->client->send($request);
        $this->assertTrue(true, array_key_exists('data', $response));
    }

    public function testDeleteCompany()
    {
        $request = new Request('DELETE', 'company/' . $this->company->id);
        $response = $this->client->send($request);
        $this->assertTrue(true, array_key_exists('deleted', $response));
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