<?php

namespace Api\UserBundle\Manager;


use API\MainBundle\Entity\Orders;
use API\MainBundle\Repository\OrdersRepository;
use API\UserBundle\Entity\Clients;
use Doctrine\DBAL\DBALException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class ClientOrdersManager {

    private $doctrine;
    private $clientsManager;

    public function __construct(RegistryInterface $doctrine, ClientsManager $clientsManager)
    {
        $this->doctrine = $doctrine;
        $this->clientsManager = $clientsManager;
    }

    public function addClientOrder($orderData)
    {
        /** @var Orders $order */
        $order  = new Orders();
        $em     = $this->doctrine->getManager();
        $cm     = $this->clientsManager;

        $hall   = $em->getRepository('API\MainBundle\Entity\RestaurantHalls')->find($orderData['hall']);
        $status = $em->getRepository('API\MainBundle\Entity\OrdersStatus')->find(1);

        $orderData['client'] = $cm->getClientByToken($orderData['clientToken']);

        $order->setContactName($orderData['client']->getUsername());
        $order->setContactPhone($orderData['client']->getPhone());
        $order->setContactEmail($orderData['client']->getEmail());
        $order->setSeatsQuantity($orderData['seats']);
        $order->setOrderDescription($orderData['desc']);
        $order->setDateOrder($orderData['date']);
        $order->setHall($hall);
        $order->setOrdersStatus($status);
        $order->setClients($orderData['client']);

        try {

            $em->persist($order);
            $em->flush();

            return array(
                'status' => true,
                'orderData' => $order
            );

        } catch (\Exception $e) {
            return array(
                'status' => false,
                'orderData' => null
            );
        }

    }

    public function getOrderListForClient(Clients $client)
    {
        $em     = $this->doctrine->getManager();
        /** @var OrdersRepository $repo */
        $repo   = $em->getRepository('APIMainBundle:Orders');

        $orderList = $repo->getOrderListForUser($client->getId());

        return $orderList;
    }

    public function removeOrder(Clients $client, $token, $orderID)
    {
        $em     = $this->doctrine->getManager();
        /** @var OrdersRepository $repo */
        $repo   = $em->getRepository('APIMainBundle:Orders');

        $order = $repo->findOrderForRemoval($client->getId(), $token, $orderID);

        try {

            $em->remove($order);
            $em->flush();

            return true;

        } catch(\Exception $e) {
            return false;
        }
    }


} 