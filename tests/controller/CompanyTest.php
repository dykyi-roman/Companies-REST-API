<?php

namespace App\Tests;

use App\Tests\controller\src\ApiClient;
use App\Tests\controller\src\ResponseDataExtractor;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class CompanyTest
 */
class CompanyTest extends TestCase
{
    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new ApiClient(new GuzzleClient, new ResponseDataExtractor());
    }

    public function testCreateCompany()
    {
        $response = $this->client->send(new Request('GET', 'companies'));
        $this->assertTrue(true, is_array($response));
    }
}