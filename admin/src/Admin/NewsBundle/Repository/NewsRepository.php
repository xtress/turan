<?php

namespace Admin\NewsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
    
    public function getNews()
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('news');
        
        $qb->select('news, creator, modifier, newsCategories');
        
        $qb->leftJoin('news.creator', 'creator');
        $qb->leftJoin('news.modifier', 'modifier');
        $qb->leftJoin('news.newsCategories', 'newsCategories');
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
}
