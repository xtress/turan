<?php

namespace Admin\OrderBundle\Controller;

use Admin\OrderBundle\Entity\Orders;
use Admin\OrderBundle\Form\OrderType;
use Admin\OrderBundle\Repository\OrdersRepository;
use Api\MainBundle\Manager\ResponseManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrdersController extends Controller
{

    const ORDERS_ENTITY         = 'Admin\OrderBundle\Entity\Orders';
    const ORDERS_STATUS_ENTITY  = 'Admin\OrderBundle\Entity\OrdersStatus';
    const ORDERS_HALLS_ENTITY   = 'Admin\OrderBundle\Entity\RestaurantHalls';

    public function listAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $deleted = $request->request->get('deletedOrders') == 'false' ? false : true;
            /** @var ResponseManager $responseManager */
            $responseManager = $this->get('response.manager');
            return $responseManager->returnDefaultResponse('', $this->getFreshOrdersList($deleted));
        } else {
            $deleted = false;
        }

        return $this->render('AdminOrderBundle:Orders:list.html.twig', array(
            'content' => $this->getFreshOrdersList($deleted),
        ));
    }

    public function editAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var OrdersRepository $orderRepo */
        $orderRepo = $em->getRepository(self::ORDERS_ENTITY);
        /** @var Orders $order */
        $order = $orderRepo->find($orderId);
        $form = $this->createForm(new OrderType());

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $order->setOrdersStatus($em->getRepository(self::ORDERS_STATUS_ENTITY)->find($data->getOrdersStatus()->getId()));
                $em->persist($order);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('admin_order_list'));
        } else {
            return $this->redirect($this->generateUrl('admin_order_list'));
        }
    }

    public function viewAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var OrdersRepository $orderRepo */
        $orderRepo = $em->getRepository(self::ORDERS_ENTITY);
        /** @var Orders $order */
        $order = $orderRepo->find($orderId);
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        $form = $this->createForm(new OrderType(), $order);

        return $responseManager->returnDefaultResponse(
            '',
            $this->renderView('AdminOrderBundle:Orders:order-form.html.twig', array(
                'form' => $form->createView(),
                'orderId' => $orderId,
            ))
        );
    }

    public function deleteAction(Request $request, $orderId)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $orderRepo = $em->getRepository(self::ORDERS_ENTITY);
            /** @var Orders $order */
            $order = $orderRepo->find($orderId);

            $order->setOrdersStatus($em->getRepository(self::ORDERS_STATUS_ENTITY)->find(4));
            $em->persist($order);
            $em->flush();

            $deleted = $request->request->get('deletedOrders');

            return $responseManager->returnDefaultResponse('Заказ удален!', $this->getFreshOrdersList($deleted));

        } else {
            $responseManager->returnErrorResponse('Неверный запрос!');
        }
    }

    private function getFreshOrdersList($deleted = false)
    {
        $em = $this->getDoctrine()->getManager();
        $orderRepo = $em->getRepository(self::ORDERS_ENTITY);

        $list = $orderRepo->getAllOrders($deleted);

        return $this->renderView('AdminOrderBundle:Orders:partial-orders-list.html.twig', array(
            'orders' => $list
        ));
    }

}
