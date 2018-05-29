<?php

namespace App\Tests\controller\src;

use App\Tests\controller\src\Exception\TransferException;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class ApiClient
 * @package App\Tests\src
 */
class ApiClient
{
    private $options = [];

    /**
     * @var ResponseDataExtractor
     */
    protected $extractor;
    /**
     * @var ClientInterface
     */
    protected $client = null;

    public function __construct(ClientInterface $client, ResponseDataExtractor $extractor)
    {
        $this->client = $client;
        $this->extractor = $extractor;

        $this->setDefaultOptions();
    }

    /**
     * Set default options
     */
    private function setDefaultOptions()
    {
        // set default options
        $this->options = [
            'verify' => false,
//            'base_uri'    => getenv('FILE_API'),
            'base_uri' => 'http://rest-api.loc:81/api/',
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return object
     */
    public function send(RequestInterface $request, $options = [])
    {
        $options = empty($options) ? $this->options : $options;
        try {
            $response = $this->client->send($request, $options);
        } catch (TransferException $e) {
            $message = sprintf('Something went wrong when calling vault (%s).', $e->getMessage());
            dump($message);
        } catch (\Exception $e) {
            dump($e->getMessage());
            die();
        }

        return $this->extractor->extract($response);
    }

}