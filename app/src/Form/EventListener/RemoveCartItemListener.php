<?php

namespace App\Form\EventListener;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Order;
use App\Repository\BasketItemRepository;
use App\Repository\BasketRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RemoveCartItemListener implements EventSubscriberInterface
{
//    private $basketRepository;

///**
//* CartManager constructor.
//*
//* @param BasketRepository $basketRepository
//*/
//    public function __construct(BasketRepository $basketRepository)
//    {
//        $this->basketRepository = $basketRepository;
//    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::POST_SUBMIT => 'postSubmit'];
    }

    /**
     * Removes items from the cart based on the data sent from the user.
     *
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $basket = $form->getData();

        if (!$basket instanceof Basket) {
            return;
        }

        // Removes items from the cart
        foreach ($form->get('items')->all() as $child) {
            if ($child->get('remove')->isClicked()) {
                $basket->removeItem($child->getData());
                break;
            }
        }
    }
}