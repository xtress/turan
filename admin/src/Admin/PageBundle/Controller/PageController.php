<?php

namespace Admin\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\DBALException as DBALException;

use Admin\PageBundle\Form\StaticPageType;
use Admin\PageBundle\Entity\StaticPage;

/**
 * Description of PageManager
 *
 * @author uncle_empty
 */
class PageController extends Controller {
    
    const pageClass         = 'Admin\PageBundle\Entity\StaticPage';
    const staticParentDir   = '/../../front-end/app/content/';
    const staticDirName     = 'static';
    
    public function indexAction()
    {
        return $this->render('AdminPageBundle:Page:index.html.twig');
    }
    
    public function createAction()
    {
        $user       = $this->get('security.context')->getToken()->getUser();
        $request    = $this->getRequest();
        $form       = $this->createForm(new StaticPageType());
        $em         = $this->getDoctrine()->getEntityManager();
        $session    = $this->get('session');
        $translator = $this->get('translator');
        
        if ($request->getMethod() === "POST") {
            
            $form->handleRequest($request);
            $data = $form->getData();
            
            if ($form->isValid()) {
                
                try {
                    
                    $page = new StaticPage();

                    $page->setAdminCreator($user);
                    $page->setDateCreated(new \DateTime());
                    $page->setIsPublished($data->getIsPublished());
                    $page->setPageBody($data->getPageBody());
                    $page->setPageName($data->getPageName());
                    $page->setPageSeo($data->getPageSeo());
                    $page->setLocale($data->getLocale());

                    $em->persist($page);
                    $em->flush();
                    
                    $this->generateJSON($page);
                    
                } catch(DBALException $e) {
                    
                    var_dump($e->getMessage());
                    $session->getFlashBag()->set('error', $translator->trans('APB_ERROR_WHILE_CREATING'));
                    return $this->redirect($this->generateUrl('_adminpage_staticpage_create'));
                    
                }
                
                $session->getFlashBag()->set('success', $translator->trans('APB_STATICPAGE_ADDED'));
                return $this->redirect($this->generateUrl('_adminpage_index'));
                
            } else {
                
                $session->getFlashBag()->set('error', $translator->trans('APB_FORM_NOT_VALID'));
                return $this->redirect($this->generateUrl('_adminpage_staticpage_create'));
                
            }
            
        }
        
        return $this->render('AdminPageBundle:Page:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(self::pageClass);
        
        $pagesList = $repo->getPages();
        
        return $this->render('AdminPageBundle:Page:list.html.twig', array(
            'pages' => $pagesList,
        ));
    }
    
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(self::pageClass);
        $page = $repo->find($id);
        $form       = $this->createForm(new StaticPageType(), $page);
        
        return $this->render('AdminPageBundle:Page:edit.html.twig', array(
            'form' => $form->createView(),
            'pageID' => $page->getId(),
        ));
    }
    
    public function saveAction($id)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $request    = $this->getRequest();
        $form       = $this->createForm(new StaticPageType());
        $em         = $this->getDoctrine()->getManager();
        $session    = $this->get('session');
        $translator = $this->get('translator');
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            try {
                
                $data = $form->getData();
                
                $page = $em->getRepository(self::pageClass)->find($id);

                $page->setIsPublished($data->getIsPublished());
                $page->setPageBody($data->getPageBody());
                $page->setPageName($data->getPageName());
                $page->setPageSeo($data->getPageSeo());
                
                $page->setAdminModifier($user);
                $page->setDateModified(new \DateTime());
                
                $em->persist($page);
                $em->flush();
                    
                $this->generateJSON($page);
                
            } catch(DBALException $e) {
                    
                var_dump($e->getMessage());
                $session->getFlashBag()->set('error', $translator->trans('APB_ERROR_WHILE_EDITING'));
                return $this->redirect($this->generateUrl('_adminpage_staticpage_edit'));
                
            }
                
            $session->getFlashBag()->set('success', $translator->trans('APB_STATICPAGE_SAVED'));
            return $this->redirect($this->generateUrl('_adminpage_staticpages_list'));
            
        } else {

            $session->getFlashBag()->set('error', $translator->trans('APB_FORM_NOT_VALID'));
            return $this->redirect($this->generateUrl('_adminpage_staticpage_edit'));

        }
        
        
    }
    
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $pageRepo = $em->getRepository(self::pageClass);
        $session    = $this->get('session');
        $translator = $this->get('translator');
        $page = $pageRepo->find($id);
        
        try {
            
            $em->remove($page);
            $em->flush();
            
        } catch (DBALException $e) {
                    
            var_dump($e->getMessage());
            $session->getFlashBag()->set('error', $translator->trans('APB_ERROR_WHILE_DELETING'));
            return $this->redirect($this->generateUrl('_adminpage_staticpages_list'));
            
        }
        
        $session->getFlashBag()->set('success', $translator->trans('APB_STATICPAGE_DELETED'));
        return $this->redirect($this->generateUrl('_adminpage_staticpages_list'));
    }
    
    /**
     * @todo now is not implemented - нужна ли она?
     */
    public function previewAction()
    {
        
    }
    
    private function generateJSON(\Admin\PageBundle\Entity\StaticPage $page)
    {
        $repo = $this->getDoctrine()->getEntityManager()->getRepository('\Admin\PageBundle\Entity\StaticPage');
        
        $pageArray = $page->__toArray();
        
        if (!file_exists(getcwd().self::staticParentDir.self::staticDirName)) {
            if (!$this->generateStaticPageDirStructure()) {
                var_dump(false);exit;
            }
        }
        
//        var_dump($pageArray);exit;
        file_put_contents(getcwd()."/../../front-end/app/content/static/".$page->getLocale()->__toLocaleString()."/".$pageArray["seo"].".json", json_encode($pageArray, JSON_UNESCAPED_SLASHES), LOCK_EX);
    }
    
    private function generateStaticPageDirStructure()
    {
        $flag = false;
        
        while(!$flag) {
            
            if (!file_exists(getcwd().self::staticParentDir.self::staticDirName)) {
                
                mkdir(getcwd().self::staticParentDir.self::staticDirName, 0777);
                mkdir(getcwd().self::staticParentDir.self::staticDirName."/en", 0777);
                mkdir(getcwd().self::staticParentDir.self::staticDirName."/ru", 0777);
                
            }
            
            $flag = true;
            
        }
        
        return true;
    }
    
    private function generatePaginationJSON()
    {
        return true;
    }
    
}
