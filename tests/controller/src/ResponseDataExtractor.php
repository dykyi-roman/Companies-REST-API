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
     * @return array
     */
    public function extract(ResponseInterface $response): array
    {
        $responseBody = $response->getBody(true);
        $rawDecoded = json_decode($responseBody, true);
        if ($rawDecoded === null) {
            throw new ClientException(sprintf("Can't decode response"));
        }
        return $rawDecoded;
    }

}