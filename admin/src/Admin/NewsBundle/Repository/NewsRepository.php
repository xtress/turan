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
    
    public function getNewsCount()
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('news');
        
        $qb->select('COUNT(news.id)');
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        return $result[0][1];
    }
    
    public function getNewsIterator()
    {
        
        $sql = "SELECT n.id, @i := @i +1 AS iterator FROM news n, (SELECT @i :=0) iterator";
                
        $db = $this->_em->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $queryResult = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $i = 0;
        
        foreach ($queryResult as $res) {
            
            $i++;
            $result[$i]['position'] = $res['iterator'];
            $result[$i]['id'] = $res['id'];
            
        }
        
        return $result;
    }
}
