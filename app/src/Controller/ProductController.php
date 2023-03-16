<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ImportPrice;
use App\Service\ProductImporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @param ProductRepository $productRepository
     * @return Response
     */
    #[Route('/product', name: 'all.products')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @param ProductImporter $productImporter
     * @return Response
     */
    #[Route('/update-prods-and-cats', name: 'update.prods.and.cats')]
    public function updateProdsAndCats(ProductImporter $productImporter): Response
    {
        $productImporter->updateProdsAndCats();
        $this->addFlash('success', 'Товари та категорії успішно оновлено!');

        return $this->render('product/update_prods_and_cats.html.twig');
    }

    #[Route('/update-prod-price', name: 'update.prod.price')]
    public function updatePrice(ImportPrice $importPrice): Response
    {
        $importPrice->updatePrice();
        $this->addFlash('success', 'Ціну на товари успішно оновленно');

        return $this->render('product/update_price.html.twig');
    }
}
