<?php

namespace API\MainBundle\Controller;

use API\MainBundle\Entity\Orders;
use Api\MainBundle\Manager\ResponseManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('APIMainBundle:Default:index.html.twig', array('name' => $name));
    }

    public function addOrderAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            /** @var EntityManager $em */
            $em                 = $this->getDoctrine()->getManager();
            $requestBag         = $request->request;

            $date   = $requestBag->get('request_date');
            $time   = $requestBag->get('request_time');
            $hall   = $requestBag->get('saloon');
            $seats  = $requestBag->get('seats');
            $name   = $requestBag->get('contact_name');
            $phone  = $requestBag->get('contact_phone');
            $email  = $requestBag->get('contact_email');
            $desc   = $requestBag->get('request_description');

            /** @var Orders $order */
            $order  = new Orders();
            $hall   = $em->getRepository('API\MainBundle\Entity\RestaurantHalls')->find($hall);

            $order->setContactName($name);
            $order->setContactPhone($phone);
            $order->setContactEmail($email);
            $order->setSeatsQuantity($seats);
            $order->setOrderDescription($desc);
            $order->setDateOrder(new \DateTime($date." ".$time.":00"));
            $order->setHall($hall);

            try {

                $em->persist($order);
                $em->flush();

            } catch (DBALException $e) {
                return $responseManager->returnErrorResponse('ORDER_SAVE_ERROR');
            }

            return $responseManager->returnDefaultResponse('ORDER_SAVE_OK');

        } else {
            return $responseManager->returnErrorResponse('ORDER_BAD_REQUEST');
        }
    }
}
