<?php

namespace App\Controller;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/', name: 'root')]
    public function index(DocumentManager $dm): JsonResponse
    {
        $a = 2;
        $b = 5;
        $c = $a * $b;
        $d = $c + $b + $c;
        $c = $d * $d;
        $a = $c / $a;

        $product = new Product();
        $product
            ->setName('A Foo Bar')
            ->setPrice('19.99')
        ;

        $dm->persist($product);
        $dm->flush();


        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AppController.php',
            'val' => $a,
            'Created product id ' => $product->getId()
        ]);
    }
}
