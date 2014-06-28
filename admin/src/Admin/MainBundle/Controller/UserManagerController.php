<?php

namespace Admin\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Admin\MainBundle\Form\AdminType;
use Admin\MainBundle\Entity\Admin;
use Admin\MainBundle\Helpers\securityHelper;

use Doctrine\DBAL\DBALException as DBALException;

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
        $em         = $this->getDoctrine()->getManager();
        $request    = $this->getRequest();
        $locale     = $request->getLocale();
        $adminRepo  = $em->getRepository(self::adminClass);
        
        $admins     = $adminRepo->getAll();
        
        return $this->render('AdminMainBundle:UserManager:list_administrators.html.twig', array(
            'admins' => $admins,
        ));
    }
    
    public function createAdministratorAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $request    = $this->getRequest();
        $locale     = $request->getLocale();
        $user       = $this->get('security.context')->getToken()->getUser();
        $session    = $this->get('session');
        $translator = $this->get('translator');
        
        $form       = $this->createForm(new AdminType());
        
        if ($request->getMethod() === "POST") {
            
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                
                try {
                    
                    $data       = $form->getData();
                    $factory    = $this->get('security.encoder_factory');
                    $encoder    = $factory->getEncoder(new Admin());
                    $salt       = securityHelper::SaltGenerator();
                    $pass       = $encoder->encodePassword($data->getPassword(), $salt);
                    
                    $admin = new Admin();
                    $admin->setCreatedAt(new \DateTime());
                    $admin->setCreatedBy($user);
                    $admin->setLogin($data->getLogin());
                    $admin->setPassword($pass);
                    $admin->setSalt($salt);
                    $admin->setUserRoles($data->getUserRoles());

                    $em->persist($admin);
                    $em->flush();
                    
                } catch (DBALException $e) {
                    
                    var_dump($e->getMessage());
                    $session->getFlashBag()->set('error', $translator->trans('AMB_ERROR_WHILE_CREATING'));
                    return $this->redirect($this->generateUrl('_admin_administrators_createAdmin'));
                    
                }
                
                $session->getFlashBag()->set('success', $translator->trans('AMB_ADMIN_CREATED'));
                return $this->redirect($this->generateUrl('_admin_user_management'));
                
            } else {
                
                $session->getFlashBag()->set('error', $translator->trans('AMB_FORM_NOT_VALID'));
                return $this->redirect($this->generateUrl('_admin_administrators_createAdmin'));
                
            }
            
        }
        
        return $this->render('AdminMainBundle:UserManager:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function deleteAdministratorAction($id)
    {
        $em         = $this->getDoctrine()->getManager();
        $userRepo   = $em->getRepository(self::adminClass);
        $session    = $this->get('session');
        $translator = $this->get('translator');
        
        $admin = $userRepo->find($id);
        
        try {
            
            $em->remove($admin);
            $em->flush();
            
        } catch(DBALException $e) {
            
            var_dump($e->getMessage());
            $session->getFlashBag()->set('error', $translator->trans('AMB_ERROR_WHILE_DELETING'));
            return $this->redirect($this->generateUrl('_admin_administrators_list'));
            
        }
        
        $session->getFlashBag()->set('success', $translator->trans('AMB_ADMINISTRATOR_DELETED'));
        return $this->redirect($this->generateUrl('_admin_administrators_list'));
    }
    
    public function viewAdministratorInfoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $translator = $this->get('translator');
        $adminRepo = $em->getRepository(self::adminClass);
        
        $admin = $adminRepo->find($id);
        
        $form = $this->createForm(new AdminType(), $admin);
        
        return $this->render('AdminMainBundle:UserManager:edit.html.twig', array(
            'form' => $form->createView(),
            'adminID' => $admin->getId(),
        ));
    }
    
    public function saveAdministratorInfoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $translator = $this->get('translator');
        $adminRepo = $em->getRepository(self::adminClass);
        $request = $this->getRequest();
        
        $admin = $adminRepo->find($id);
        $oldPass = $admin->getPassword();
        
        $form = $this->createForm(new AdminType(), $admin);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            try {
                
                $data = $form->getData();
                $factory    = $this->get('security.encoder_factory');
                $encoder    = $factory->getEncoder(new Admin);
                $salt       = securityHelper::SaltGenerator();
                $pass       = $encoder->encodePassword($data->getPassword(), $salt);
                
                $admin->setUserRoles($data->getUserRoles());
                $admin->setLogin($data->getLogin());
                
                if ($data->getPassword() != null) {
                    $admin->setPassword($pass);
                    $admin->setSalt($salt);
                } else
                    $admin->setPassword($oldPass);
                
                $em->persist($admin);
                $em->flush();
                
            } catch(DBALException $e) {
                
                var_dump($e->getMessage());
                $session->getFlashBag()->set('error', $translator->trans('AMB_ERROR_WHILE_UPDATING'));
                return $this->redirect($this->generateUrl('_admin_administrators_viewAdminInfo'));
                
            }
            
            $session->getFlashBag()->set('success', $translator->trans('AMB_SUCESFULLY_UPDATED'));
            return $this->redirect($this->generateUrl('_admin_administrators_list'));
            
        } else {
            
            $session->getFlashBag()->set('error', $translator->trans('AMB_FORM_NOT_VALID'));
            return $this->redirect($this->generateUrl('_admin_administrators_viewAdminInfo'));
            
        }
    }
    
}
