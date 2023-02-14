<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\OrderItem;
use App\Form\CartType;
use App\Repository\OrderRepository;
use App\Service\CartManager;
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
            $totalSum = $cart->getTotal();
            foreach ($cart->getItems() as $item) {
                $cart->addItem($item, false, $totalSum);
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
    public function makeOrder(CartManager $cartManager, OrderRepository $orderRepository): Response
    {
        $basket = $cartManager->getCurrentBasket();
        foreach ($basket->getItems() as $item) {
            $order = new OrderItem();
            $order->setProduct($item->getProduct())
                ->setStatus(OrderItem::STATUS_CART)
                ->setSum($item->getSum())
                ->setQuantity($item->getQuantity())
                ->setTotalSum($item->getTotalSum());
            $orderRepository->save($order, true);
        }

        $basket->setStatus(Basket::STATUS_BASKET['closed']);
        $cartManager->save($basket);

        return $this->render('basket/order.html.twig', [
            'basket' => $basket
        ]);
    }
}
