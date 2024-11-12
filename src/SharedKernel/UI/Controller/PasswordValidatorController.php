<?php

namespace App\SharedKernel\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PasswordValidatorController extends AbstractController
{

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $password = $data['password'] ?? '';
        if ($password === $this->getParameter('app.passwpord')) {
            return new JsonResponse(['accesoPermitido' => true], 200);
        }

        return new JsonResponse(['accesoPermitido' => false], 403);
    }
}
