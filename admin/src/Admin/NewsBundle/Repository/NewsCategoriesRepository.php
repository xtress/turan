<?php

namespace Admin\NewsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsCategoriesRepository extends EntityRepository
{
    
    public function getCategories()
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('categories');
        
        $qb->select('categories');
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
}
