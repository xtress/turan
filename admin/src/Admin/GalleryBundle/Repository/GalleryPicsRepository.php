<?php

namespace Admin\GalleryBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GalleryPicsRepository extends EntityRepository
{    
    
    public function getGalleryPics($galleryID)
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('pics');
        
        $qb->select('pics');
        
        $qb->leftJoin('pics.gallery', 'gallery');
        
        $qb->where("pics.gallery = $galleryID");
        
        $query = $qb->getQuery();
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
    
}
