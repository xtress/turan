<?php

namespace Admin\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of UserManager
 *
 * @author uncle_empty
 */
class UserManagerController extends Controller {
    
    const adminClass = 'Admin\MainBundle\Entity\Admin';
    
    public function indexAction()
    {
        return $this->render('AdminMainBundle:UserManager:index.html.twig');
    }
    
    public function showAdministratorsAction()
    {
        $em         = $this->getDoctrine()->getEntityManager();
        $request    = $this->getRequest();
        $locale     = $request->getLocale();
        $adminRepo  = $em->getRepository(self::adminClass);
        
        $admins     = $adminRepo->getAll();
        
        return $this->render('AdminMainBundle:UserManager:list_administrators.html.twig', array(
            'admins' => $admins,
        ));
    }
    
}
