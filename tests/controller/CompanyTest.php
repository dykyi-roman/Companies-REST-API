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
    /** @var ApiClient  */
    private $client;

    /** @var ResponseDataExtractor  */
    private $extractor;

    /** @var \Faker\Generator  */
    private $faker;

    private $company;

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
    }

    public function testCreateCompany()
    {
        $this->assertArrayHasKey('id', $this->company);
    }

    public function testGetCompanies()
    {
        $response = $this->client->send(new Request('GET', 'companies'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('companies', $this->extractor->extract($response));
    }

    public function testGetCompany()
    {
        $request = new Request('GET', 'company/' . $this->company['id']);
        $response = $this->client->send($request);
        $this->assertEquals(200, $response->getStatusCode());

        $data = $this->extractor->extract($response);

        $this->assertArrayHasKey('company', $data);
        $this->assertEquals(2, count($data['company']));
    }

    public function testUpdateCompany()
    {
        $request = new Request('PUT', 'company/' . $this->company['id'], [],
            json_encode([
                'name' => $this->faker->name,
                'address' => $this->faker->address
            ])
        );
        $response = $this->client->send($request);
        $this->assertEquals(201, $response->getStatusCode());

        $data = $this->extractor->extract($response);
        $this->assertArrayHasKey('company', $this->extractor->extract($response));
        $this->assertEquals(2, count($data['company']));
    }

    public function testDeleteCompany()
    {
        $request = new Request('DELETE', 'company/' . $this->company['id']);
        $response = $this->client->send($request);
        $this->assertArrayHasKey('deleted', $this->extractor->extract($response));
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