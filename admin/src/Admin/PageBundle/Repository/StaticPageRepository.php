<?php

namespace Admin\PageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StaticPageRepository extends EntityRepository
{
    
    public function getPages()
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('page');
        
        $qb->select('page, creator, modifier');
        
        $qb->leftJoin('page.adminCreator', 'creator');
        $qb->leftJoin('page.adminModifier', 'modifier');
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
}
