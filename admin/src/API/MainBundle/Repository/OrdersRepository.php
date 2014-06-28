<?php
/**
 * Created by PhpStorm.
 * User: empty
 * Date: 6/28/14
 * Time: 2:20 PM
 */

namespace API\MainBundle\Repository;


use Doctrine\ORM\EntityRepository;

class OrdersRepository extends EntityRepository {


    public function getOrderListForUser($userId)
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select('o');

        $qb->andWhere('o.clients = :clientId')->setParameter('clientId', $userId);

        $result = $qb->getQuery()->getResult();

        if (!empty($result)) {
            return $result;
        } else {
            return null;
        }
    }

    public function findOrderForRemoval($clientID, $token, $orderID)
    {
        $qb = $this->createQueryBuilder('o');

        $qb->leftJoin('o.clients', 'client');

        $qb->andWhere('client.id = :clientID')->setParameter('clientID', $clientID);
        $qb->andWhere('client.sessionToken = :token')->setParameter('token', $token);
        $qb->andWhere('o.id = :orderID')->setParameter('orderID', $orderID);
        $qb->andWhere('o.ordersStatus = :status')->setParameter('status', 1);

        $q = $qb->getQuery();
        try {
            return $q->getOneOrNullResult();
        } catch(\Exception $e) {
            return null;
        }
    }

} 