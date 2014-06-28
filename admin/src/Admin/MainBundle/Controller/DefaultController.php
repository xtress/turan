<?php

namespace Admin\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->getRequest()->setLocale('ru_RU');
//        if (false === $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
//            throw new AccessDeniedException();
//        }

//        if (false !== $this->get('security.context')->isGranted('ROLE_WAITER') || false !== $this->get('security.context')->isGranted('ROLE_RESTAURANT_MANAGER')) {
//            return $this->redirect($this->generateUrl('admin_order_list'));
//        } else {
            return $this->render('AdminMainBundle:Default:index.html.twig');
//        }
    }
    
    public function setLocaleAction(Request $request, $locale = 'ru')
    {
        $session = $this->get('session');
        
        $session->set('_locale', $locale);
        
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
}
