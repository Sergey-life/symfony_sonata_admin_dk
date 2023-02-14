<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Form\AddToBasketType;
use App\Repository\ProductRepository;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'all.products')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('product/{id}', name: 'show.product')]
    public function show(int $id, ProductRepository $productRepository, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToBasketType::class);

        $form->handleRequest($request);

        $product = $productRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $basket = $cartManager->getCurrentBasket();
//
//            if (!$basket) {
//                $cartManager->saveBasket($basket);
//            }

            $item = $form->getData();
            $item->setProduct($product);
            $item->setBasket($basket);

//            $basket = $cartManager->getCurrentBasket();
            $basket->addItem($item, true);

//            $totalSum = $cart->getTotal();
//            foreach ($cart->getItems() as $item) {
//                $item->setTotalSum($totalSum);
//            }

            $cartManager->save($basket);

            return $this->redirectToRoute('app_basket');
        }

        return $this->renderForm('product/detail.html.twig', [
            'product' => $productRepository->find($id),
            'form' => $form
        ]);
    }
}
