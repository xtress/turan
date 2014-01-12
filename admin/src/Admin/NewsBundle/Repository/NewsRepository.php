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
        
        $sql = "SELECT n.id, n.title, n.body, n.locale @i := @i +1 AS iterator FROM news n, (SELECT @i :=0) iterator";
                
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
    
    
    public function getNewsIteratorWLocale($locale = 'ru')
    {
        
        $sql = "
                SELECT 
                    n0_.id, 
                    n0_.title, 
                    n0_.body, 
                    n0_.locale, 
                    nc_0.name AS news_category,
                    @i := @i +1 AS iterator 
                FROM 
                    news AS n0_
                        LEFT JOIN
                            news_categories AS nc_0 ON nc_0.id = n0_.news_categories_id
                    ,(SELECT @i :=0) iterator 
                WHERE n0_.locale = '$locale' AND n0_.is_published = TRUE";
                
        $db = $this->_em->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $queryResult = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
//        $i = 0;
//        
//        foreach ($queryResult as $res) {
//            
//            $i++;
//            $result[$i]['position'] = $res['iterator'];
//            $result[$i]['id'] = $res['id'];
//            
//        }
        
        return $queryResult;
    }
    
    public function getLastNews($quantity = 5, $locale = 'ru')
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('news');
        
        $qb->select('news');
        
        $qb->where("news.locale = '$locale'");
        $qb->andWhere("news.isPublished = TRUE");
        
        $qb->orderBy('news.createdAt', 'DESC');
        $qb->setMaxResults($quantity);
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
}
