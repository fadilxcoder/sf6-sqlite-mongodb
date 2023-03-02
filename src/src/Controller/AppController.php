<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'root')]
    public function index(): JsonResponse
    {
        $a = 2;
        $b = 5;
        $c = $a * $b;
        $d = $c + $b + $c;
        $c = $d * $d;
        $a = $c / $a;

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AppController.php',
            'val' => $a,
        ]);
    }
}
