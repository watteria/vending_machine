<?php

namespace App\SharedKernel\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WatteriaCheckAPIController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {

    }

    public function testApi(Request $request): Response
    {

        return $this->render('check_api/test_form.html.twig');
    }
}