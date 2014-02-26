<?php

namespace Admin\GalleryBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GalleryRepository extends EntityRepository
{    
    
    public function getGalleries($locale = 'ru')
    {
        $em = $this->_em;
        $entity = $this->_entityName;
        
        $qb = $em->getRepository($entity)->createQueryBuilder('gallery');
        
        $qb->select('gallery, mainPic');
        
        $qb->leftJoin('gallery.mainPic', 'mainPic');
        
        $qb->where("gallery.locale = '$locale'");
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
    
    public function getGalleriesWInfoByLocale($locale = 'ru')
    {
        $em = $this->_em;
        $entity = $this->_entityName;
        
        $qb = $em->getRepository($entity)->createQueryBuilder('gallery');
        
        $qb->select('gallery, mainPic, type');
        
        $qb->leftJoin('gallery.mainPic', 'mainPic');
        $qb->leftJoin('gallery.galleryType', 'type');
        
        $qb->where("gallery.locale = '$locale'");
        $qb->andWhere("gallery.isPublished = TRUE");
        
        $query = $qb->getQuery();
        
        $result = $query->getResult();
        
        if (!empty($result))
            return $result;
        else
            return null;
    }
    
}
