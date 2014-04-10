<?php

namespace Admin\OrderBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OrdersRepository extends EntityRepository
{

    public function getAllOrders($deleted = false)
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select('o');

        $qb->leftJoin('o.ordersStatus', 'os');

        if ($deleted == false) {
            $qb->andWhere('os.id <> :deleted')->setParameter('deleted', 4);
        }

        $result = $qb->getQuery()->getResult();
//        var_dump($deleted, $deleted == false);exit;

        if (!empty($result)) {
            return $result;
        } else {
            return null;
        }
    }

    public function find($id)
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select('o, os, oh');

        $qb->leftJoin('o.ordersStatus', 'os');
        $qb->leftJoin('o.hall', 'oh');

        $qb->andWhere('o.id = :id')->setParameter('id', $id);

        $result = $qb->getQuery()->getResult();
//        var_dump($deleted, $deleted == false);exit;

        if (!empty($result)) {
            return $result[0];
        } else {
            return null;
        }
    }

}
