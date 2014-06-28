<?php

namespace API\UserBundle\Controller;

use Api\MainBundle\Manager\ResponseManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    const SESSION_TIMED_OUT = 'timed';
    const RENEW_SESSION     = 'normal';
    const INVALID_TOKEN     = 'invalid_token';

    public function addOrderAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            /** @var EntityManager $em */
            $em                 = $this->getDoctrine()->getManager();
            $requestBag         = $request->request;
            $clientOrdersMan    = $this->get('client.orders.manager');
            $clientsManager     = $this->get('clients.manager');

            $date   = $requestBag->get('request_date');
            $time   = $requestBag->get('request_time');
            $hall   = $requestBag->get('saloon');
            $seats  = $requestBag->get('seats');
            $token  = $requestBag->get('token');
            $desc   = $requestBag->get('request_description');

            if ($token !== null && $this->checkToken($token) !== self::SESSION_TIMED_OUT) {

                $orderData = array(
                    'date'              => new \DateTime($date." ".$time.":00"),
                    'hall'              => $hall,
                    'seats'             => $seats,
                    'desc'              => $desc,
                    'clientToken'       => $token,
                    'client'            => null
                );

                $result = $clientOrdersMan->addClientOrder($orderData);

                if ($result['status']) {

                    $this->sendEmailNativeNotification($result['orderData'], true);
                    $this->sendEmailNativeNotification($result['orderData'], false);

                    return $responseManager->returnDefaultResponse(
                        'ORDER_SAVE_OK',
                        json_encode(
                            array(
                                'order' => $result['orderData'],
                                'token' => $clientsManager->updateClientSession($result['orderData']->getClients())
                            ),
                            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                        )
                    );

                } else {
                    return $responseManager->returnErrorResponse('ORDER_SAVE_ERROR');
                }

            } else {
                return $responseManager->returnErrorResponse('SESSION_TIMED_OUT');
            }

        } else {
            return $responseManager->returnErrorResponse('ORDER_BAD_REQUEST');
        }
    }

    public function getUserOrdersListAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            $requestBag = $request->request;

            $token      = $requestBag->get('token');
            $username   = $requestBag->get('username');

            if ($token !== null && $this->checkToken($token) !== self::SESSION_TIMED_OUT) {

                $clientOrdersManager    = $this->get('client.orders.manager');
                $clientsManager         = $this->get('clients.manager');

                $user                   = $clientsManager->getUser($username);
                $orderList              = $clientOrdersManager->getOrderListForClient($user);

                if ($orderList !== null) {

                    return $responseManager->returnDefaultResponse(
                        'ORDER_LIST_LOADED_OK',
                        json_encode(
                            array(
                                'orderList' => $this->reformatOrderList($orderList),
                                'token'     => $clientsManager->updateClientSession($user)
                            ),
                            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                        )
                    );

                } else {
                    return $responseManager->returnErrorResponse('NO_ORDERS_FOUND');
                }

            } else {
                return $responseManager->returnErrorResponse('SESSION_TIMED_OUT');
            }

        } else {
            return $responseManager->returnErrorResponse('ORDER_BAD_REQUEST');
        }
    }

    public function removeOrderAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            $requestBag = $request->request;

            $token      = $requestBag->get('token');
            $order   = $requestBag->get('order');

            if ($token !== null && $this->checkToken($token) !== self::SESSION_TIMED_OUT) {

                $clientsManager         = $this->get('clients.manager');
                $clientOrdersManager    = $this->get('client.orders.manager');

                $user = $clientsManager->getClientByToken($token);
                $result = $clientOrdersManager->removeOrder($user, $token, $order);

                if ($result) {

                    return $responseManager->returnDefaultResponse(
                        'ORDER_REMOVE_OK',
                        json_encode(
                            array(
                                'token'     => $clientsManager->updateClientSession($user)
                            ),
                            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                        )
                    );
                } else {
                    return $responseManager->returnErrorResponse('ORDER_CANNOT_BE_REMOVED');
                }

            } else {
                return $responseManager->returnErrorResponse('SESSION_TIMED_OUT');
            }

        } else {
            return $responseManager->returnErrorResponse('ORDER_BAD_REQUEST');
        }
    }

    private function checkToken($token)
    {
        $clientsManager = $this->get('clients.manager');

        return $clientsManager->checkToken($token);
    }

    private function reformatOrderList($orderList)
    {
        $newList = array();

        foreach ($orderList as $order) {
            $newList[] = $order->getOrderData();
        }

        return $newList;
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
