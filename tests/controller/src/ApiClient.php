<?php

namespace App\Tests\controller\src;

use App\Tests\controller\src\Exception\TransferException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;

/**
 * Class ApiClient
 * @package App\Tests\src
 */
class ApiClient
{
    private $url;

    private $options = [];

    /**
     * @var ResponseDataExtractor
     */
    protected $extractor;
    /**
     * @var ClientInterface
     */
    protected $client = null;

    public function __construct(ClientInterface $client, ResponseDataExtractor $extractor, string $url)
    {
        $this->url = $url;
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
            'base_uri' => $this->url,
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
        try {
            $response = $this->client->send($request, array_merge($this->options, $options));
        } catch (TransferException $e) {
            $message = sprintf('Something went wrong when calling vault (%s).', $e->getMessage());
            dump($message);
            die();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            dump($responseBodyAsString);
            die();
        }

        return $this->extractor->extract($response);
    }

}