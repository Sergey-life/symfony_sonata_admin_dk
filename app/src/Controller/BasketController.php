<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\OrderItem;
use App\Form\CartType;
use App\Repository\BasketRepository;
use App\Repository\OrderRepository;
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

        if ($form->isSubmitted() /**&& $form->isValid()*/) {
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
