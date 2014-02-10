<?php

namespace Admin\GalleryBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GalleryVidsRepository extends EntityRepository
{    
    
    public function getGalleryVids($galleryID)
    {
        $em = $this->_em;
        
        $qb = $em->getRepository($this->_entityName)->createQueryBuilder('vids');
        
        $qb->select('vids');
        
        $qb->leftJoin('vids.gallery', 'gallery');
        
        $qb->where("vids.gallery = $galleryID");
        
        $query = $qb->getQuery();
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
    
}
