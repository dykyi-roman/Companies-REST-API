<?php

namespace App\Tests\controller\src;

use App\Tests\controller\src\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseDataExtractor
 */
class ResponseDataExtractor implements ResponseDataInterface
{
    /**
     * @param ResponseInterface $response
     *
     * @throws \RuntimeException
     *
     * @return object
     */
    public function extract(ResponseInterface $response)
    {
        $responseBody = $response->getBody()->getContents();
        $rawDecoded = json_decode($responseBody);
        if ($rawDecoded === null) {
            throw new ClientException(sprintf("Can't decode response"));
        }
        return $rawDecoded;
    }

}