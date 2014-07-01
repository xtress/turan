<?php
namespace API\UserBundle\Controller;

use Api\MainBundle\Manager\ResponseManager;
use API\UserBundle\Entity\Clients;
use Api\UserBundle\Manager\ClientsManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    const SESSION_TIMED_OUT = 'timed';
    const RENEW_SESSION     = 'normal';
    const INVALID_TOKEN     = 'invalid_token';

    /**
     * Registers user inside app
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            $requestBag = $request->request;
            $username   = $requestBag->get('FIO');
            $phone      = $requestBag->get('phone');
            $password   = $requestBag->get('password');
            $email      = $requestBag->get('email');
            $clientsManager = $this->get('clients.manager');

            $clientData = array(
                'FIO'       => $username,
                'phone'     => $phone,
                'email'     => $email,
                'password'  => $password
            );

            $register = $clientsManager->registerClient($clientData);

            if ($register instanceof Clients) {

                if ($clientsManager->login($register, $password)) {
                    return $responseManager->returnDefaultResponse('USER_REGISTERED_AND_LOGGED_IN', $register->getUserInfo());
                } else {
                    return $responseManager->returnErrorResponse('USER_REGISTERED_BUT_NOT_LOGGED_IN');
                }

            } else {
                return $responseManager->returnErrorResponse('ERROR_WHILE_SAVING_USER');
            }


        } else {
            return $responseManager->returnErrorResponse('BAD_REQUEST');
        }
    }

    /**
     * Logs user in inside app
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            /** @var ClientsManager $clientsManager */
            $clientsManager = $this->get('clients.manager');
            $userName = $request->request->get('username');
            $pass     = $request->request->get('password');
            /** @var Clients $client */
            $client = $this->getDoctrine()->getManager()->getRepository('APIUserBundle:Clients')->findOneBy(array('email' => $userName));

            if ($client !== null) {
                $result = $clientsManager->login($client, $pass);

                if ($result) {
                    return $responseManager->returnDefaultResponse('USER_LOGGED_IN', $client->getUserInfo());
                } else {
                    return $responseManager->returnErrorResponse('USER_LOGIN_FAILED');
                }

            } else {
                return $responseManager->returnErrorResponse('BAD_REQUEST');
            }
        }else{
            return $responseManager->returnErrorResponse('BAD_REQUEST');
        }
    }

    /**
     * Returns user info
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUserAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            /** @var ClientsManager $clientsManager */
            $clientsManager = $this->get('clients.manager');

            $token = $request->request->get('token');
            $username = $request->request->get('username');
            $em = $this->getDoctrine()->getManager();

            if ($username !== null) {

                /** @var Clients $user */
                $user = $em->getRepository('APIUserBundle:Clients')->findOneBy(array('email' => $username));
//                $now = new \DateTime('now', new \DateTimeZone('Europe/Minsk'));
                $result = $clientsManager->checkUserSession($user, $token);

                if ($result === self::RENEW_SESSION) {

                    if ($clientsManager->updateClientSession($user)) {
                        return $responseManager->returnDefaultResponse('User info!', json_encode($user->getUserInfo(), JSON_UNESCAPED_UNICODE));
                    } else {
                        return $responseManager->returnErrorResponse('Could not set new token!');
                    }

                } else {

                    return $responseManager->returnErrorResponse('TRY_TO_LOGIN');

                }

            } else {

                return $responseManager->returnErrorResponse('User not found!');

            }

        } else {
            return $responseManager->returnErrorResponse('BAD_REQUEST');
        }

    }

    public function changeClientInfoAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            /** @var ClientsManager $clientsManager */
            $clientsManager = $this->get('clients.manager');

            $em         = $this->getDoctrine()->getManager();
            $username   = $request->request->get('username');
            $token      = $request->request->get('token');

            if ($username !== null) {

                /** @var Clients $client */
                $client = $em->getRepository('APIUserBundle:Clients')->findOneBy(array('email' => $username));

                $result = $clientsManager->checkUserSession($client, $token);

                if ($result == self::RENEW_SESSION) {

                    $newInfo = array(
                        'FIO'   => $request->request->get('FIO') !== null ? $request->request->get('FIO') : false,
                        'phone' => $request->request->get('phone') !== null ? $request->request->get('phone') : false,
                    );
                    $change = $clientsManager->changeClientInfo($client, $newInfo);

                    if ($change) {
                        $clientsManager->updateClientSession($client);
                        return $responseManager->returnDefaultResponse('USER_INFO_CHANGED', $client->getUserInfo());
                    } else {
                        return $responseManager->returnErrorResponse('TRY_AGAIN');
                    }

                } elseif ($result == self::SESSION_TIMED_OUT) {
                    return $responseManager->returnErrorResponse('TRY_TO_LOGIN');
                }

            }

        } else {
            return $responseManager->returnErrorResponse('BAD_REQUEST');
        }
    }

    /**
     * Logs out user from app
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logoutAction(Request $request)
    {
        /** @var ResponseManager $responseManager */
        $responseManager = $this->get('response.manager');

        if ($request->isXmlHttpRequest()) {

            $username   = $request->request->get('username');
            $token      = $request->request->get('token');
            $em         = $this->getDoctrine()->getManager();
            /** @var ClientsManager $clientsManager */
            $clientsManager = $this->get('clients.manager');

            if ($username !== null) {
                $client = $em->getRepository('APIUserBundle:Clients')->findOneBy(array('email' => $username));

                $result = $clientsManager->checkUserSession($client, $token);

                if ($result == self::RENEW_SESSION) {
                    $clientsManager->invalidateClientSession($client);
                    return $responseManager->returnDefaultResponse('LOGGED_OUT');
                } else {
                    return $responseManager->returnErrorResponse('COULDNOT_LOG_OUT');
                }
            }

        } else {
            return $responseManager->returnErrorResponse('BAD_REQUEST');
        }
    }
}