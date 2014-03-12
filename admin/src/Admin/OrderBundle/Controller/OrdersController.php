<?php

namespace Admin\OrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrdersController extends Controller
{

    const ORDERS_ENTITY         = 'Admin\OrderBundle\Entity\Orders';
    const ORDERS_STATUS_ENTITY  = 'Admin\OrderBundle\Entity\OrdersStatus';
    const ORDERS_HALLS_ENTITY   = 'Admin\OrderBundle\Entity\RestaurantHalls';

    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $orderRepo = $em->getRepository(self::ORDERS_ENTITY);

        $orderList = $orderRepo->findAll();

        return $this->render('AdminOrderBundle:Orders:list.html.twig', array(
            'orders' => $orderList,
        ));
    }

}
