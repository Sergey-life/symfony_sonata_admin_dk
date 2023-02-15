<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\OrderItem;
use App\Form\AddToBasketType;
use App\Form\CartType;
use App\Repository\BasketRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\CartManager;
use FontLib\Table\Type\name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(CartManager $cartManager, Request $request): Response
    {
        $cart = $cartManager->getCurrentBasket();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*
             * todo - Зробити методи для додавання та видалення продукту
             */
            foreach ($cart->getItems() as $item) {
                $cart->addItem($item, false);
            }

            $cartManager->save($cart);

            return $this->redirectToRoute('app_basket');
        }

        return $this->render('basket/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
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

            $item = $form->getData();
            $item->setProduct($product);
            $item->setBasket($basket);

            $basket->addItem($item, true);

            $cartManager->save($basket);

            return $this->redirectToRoute('app_basket');
        }

        return $this->renderForm('product/detail.html.twig', [
            'product' => $productRepository->find($id),
            'form' => $form
        ]);
    }

    #[Route('/order', name: 'app_make_order')]
    public function makeOrder(CartManager $cartManager): Response
    {
        $basket = $cartManager->getCurrentBasket();
        $cartManager->createOrder($basket);

        return $this->render('basket/order.html.twig', [
            'basket' => $basket
        ]);
    }

    #[Route('/delete-basket', name: 'app_delete_basket')]
    public function delete(CartManager $cartManager, BasketRepository $basketRepository)
    {
        $basket = $cartManager->getCurrentBasket();
        $basketRepository->remove($basket, true);

        $this->addFlash('success', 'Кошик успішно видалено!');

        return $this->redirectToRoute('app_basket');
    }
}
