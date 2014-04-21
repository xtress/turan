<?php

namespace API\MainBundle\Controller;

use API\MainBundle\Entity\Orders;
use Api\MainBundle\Manager\ResponseManager;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            $status = $em->getRepository('API\MainBundle\Entity\OrdersStatus')->find(1);

            $order->setContactName($name);
            $order->setContactPhone($phone);
            $order->setContactEmail($email);
            $order->setSeatsQuantity($seats);
            $order->setOrderDescription($desc);
            $order->setDateOrder(new \DateTime($date." ".$time.":00"));
            $order->setHall($hall);
            $order->setOrdersStatus($status);

            try {
                $em->persist($order);
                $em->flush();

                $this->sendEmailNativeNotification($order, true);
                $this->sendEmailNativeNotification($order, false);

            } catch (DBALException $e) {
                return $responseManager->returnErrorResponse('ORDER_SAVE_ERROR');
            }

            return $responseManager->returnDefaultResponse('ORDER_SAVE_OK');

        } else {
            return $responseManager->returnErrorResponse('ORDER_BAD_REQUEST');
        }
    }

   /** @var Orders $order */
   public function sendEmailNativeNotification($order, $mailForAdmin){

        $mailBody = $this->renderView('APIMainBundle:Mail:email.html.twig', array('order'=>$order,'mailForAdmin'=>$mailForAdmin));
        $to  = 'info@turan.by';
        $subject = $this->container->get('translator')->trans('NEW_ORDER_EMAIL_TITLE');
        if ($mailForAdmin == false){
            $to = $order->getContactEmail();
            $subject = $this->container->get('translator')->trans('NEW_ORDER_USER_EMAIL_TITLE');
        }

        $headers  = 'MIME-Version: 1.0' .PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' .PHP_EOL;
        $headers .= 'To: '. $to .PHP_EOL;
        $headers .= 'From: noreply.turan@gmail.com' .PHP_EOL;
        //$headers .= 'Bcc: darkos.cpp@gmail.com' .PHP_EOL;

        mail($to, $subject, $mailBody, $headers);

    }

   public function sendEmailNotification($order){
	    $mailBody = $this->renderView('APIMainBundle:Mail:email.html.twig', array('order'=>$order));
        $message = \Swift_Message::newInstance()
            ->setSubject($this->container->get('translator')->trans('NEW_ORDER_EMAIL_TITLE'))
            ->setFrom('noreply.turan@gmail.com')
            ->setTo('darkos.cpp@gmail.com')
            ->setBody(
                $mailBody, 'text/html', 'utf-8');
        $this->get('mailer')->send($message);
   }

   public function testMailAction(Request $request){

      $order=$this->getDoctrine()->getManager()->getRepository('API\MainBundle\Entity\Orders')->find(1);
      $this->sendEmailNativeNotification($order, true);
      var_dump($order->getId());
      var_dump('end');exit;
   }


}