<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\BasketItemType;
use App\Form\CartType;
use App\Repository\BasketItemRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
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
//            $cart->getItems()->map(function ($value) use ($cart, $totalSum) {
//                return $cart->addItem($value, false, $totalSum);
//            });
            $cartManager->save($cart);

            return $this->redirectToRoute('app_basket');
        }

        return $this->render('basket/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }
}
