<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/produits', name: 'app_products')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();
        // dd($products);
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/produits/{slug}', name: 'app_product_single')]
    public function show($slug): Response
    {
        /** @var Product $product */
        $product = $this->productRepository->findOneBySlug($slug);
    
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
