<?php

namespace Admin\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdminRepository extends EntityRepository
{    
    public function getUser($userName)
    {
        
        $user = $this->findOneBy(array('login' => $userName));
        
        return $user;
        
    }
    
    public function getAll()
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('admin');
        
        $qb->select('admin, roles');
        
        $qb->leftJoin('admin.userRoles', 'roles');
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
}
