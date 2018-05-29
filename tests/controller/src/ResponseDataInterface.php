<?php

namespace App\Tests\controller\src;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ResponseDataInterface
 * @package App\Tests\src
 */
interface ResponseDataInterface
{
    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function extract(ResponseInterface $response);
}