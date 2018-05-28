<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends Controller
{
    public function index()
    {
        $swagger = \Swagger\scan($this->get('kernel')->getRootDir());

        file_put_contents('swagger.json', $swagger);

        return $this->render('default/api.html.twig');
    }
}
