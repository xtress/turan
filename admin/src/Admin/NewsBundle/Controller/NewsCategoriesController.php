<?php

namespace Admin\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\DBAL\DBALException as DBALException;

use Admin\NewsBundle\Entity\NewsCategories;
use Admin\NewsBundle\Form\NewsCategoriesType;

/**
 * Description of NewsController
 *
 * @author uncle_empty
 */
class NewsCategoriesController extends Controller {
    
    const newsCategoriesClass = 'Admin\NewsBundle\Entity\NewsCategories';
    
    public function indexAction()
    {
        return $this->render('AdminNewsBundle:NewsCategories:index.html.twig');
    }
    
    public function createAction(Request $request)
    {
        $user       = $this->get('security.context')->getToken()->getUser();
        $form       = $this->createForm(new NewsCategoriesType());
        $em         = $this->getDoctrine()->getEntityManager();
        $session    = $this->get('session');
        $translator = $this->get('translator');
        $localeRepo = $em->getRepository('\Admin\NewsBundle\Entity\Locale');
        
        if ($request->getMethod() === "POST") {
            
            $form->handleRequest($request);
            $data = $form->getData();
            
            if ($form->isValid()) {
                
                try {
                    
                    $newsCategory = new NewsCategories();

                    $newsCategory->setName($data->getName());
                    $newsCategory->setLocale($localeRepo->find($session->get('_locale')));

                    $em->persist($newsCategory);
                    $em->flush();
                    
                } catch(DBALException $e) {
                    
                    var_dump($e->getMessage());
                    $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_CREATING'));
                    return $this->redirect($this->generateUrl('admin_news_categories_create'));
                    
                }
                
                $session->getFlashBag()->set('success', $translator->trans('ANB_NEWSCATEGORY_ADDED'));
                return $this->redirect($this->generateUrl('admin_news_categories_index'));
                
            } else {
                
                $session->getFlashBag()->set('error', $translator->trans('ANB_FORM_NOT_VALID'));
                return $this->redirect($this->generateUrl('admin_news_categories_create'));
                
            }
            
        }
        
        return $this->render('AdminNewsBundle:NewsCategories:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    
    public function deleteAction(Request $request, $categoryID)
    {
        $em = $this->getDoctrine()->getManager();
        $newsCatRepo = $em->getRepository(self::newsCategoriesClass);
        $session    = $this->get('session');
        $translator = $this->get('translator');
        $newsCategory = $newsCatRepo->find($categoryID);
        
        try {
            
            $em->remove($newsCategory);
            $em->flush();
            
        } catch (DBALException $e) {
                    
            var_dump($e->getMessage());
            $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_DELETING'));
            return $this->redirect($this->generateUrl('admin_news_categories_list'));
            
        }
        
        $session->getFlashBag()->set('success', $translator->trans('ANB_NEWSCATEGORY_DELETED'));
        return $this->redirect($this->generateUrl('admin_news_categories_list'));
    }
    
    public function editAction(Request $request, $categoryID)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(self::newsCategoriesClass);
        $category = $repo->find($categoryID);
        $form       = $this->createForm(new NewsCategoriesType(), $category);
        
        return $this->render('AdminNewsBundle:NewsCategories:edit.html.twig', array(
            'form' => $form->createView(),
            'categoryID' => $category->getId(),
        ));
    }
    
    public function saveAction(Request $request, $categoryID)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $request    = $this->getRequest();
        $form       = $this->createForm(new NewsCategoriesType());
        $em         = $this->getDoctrine()->getManager();
        $session    = $this->get('session');
        $translator = $this->get('translator');
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            try {
                
                $data = $form->getData();
                
                $newsCategory = $em->getRepository(self::newsCategoriesClass)->find($categoryID);

                $newsCategory->setName($data->getName());
                
                $em->persist($newsCategory);
                $em->flush();
                
            } catch(DBALException $e) {
                    
                var_dump($e->getMessage());
                $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_EDITING'));
                return $this->redirect($this->generateUrl('admin_news_categories_edit'));
                
            }
                
            $session->getFlashBag()->set('success', $translator->trans('ANB_NEWSCATEGORY_SAVED'));
            return $this->redirect($this->generateUrl('admin_news_categories_list'));
            
        } else {

            $session->getFlashBag()->set('error', $translator->trans('ANB_FORM_NOT_VALID'));
            return $this->redirect($this->generateUrl('admin_news_categories_edit'));

        }
    }
    
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(self::newsCategoriesClass);
        
        $catList = $repo->getCategories();
        
        return $this->render('AdminNewsBundle:NewsCategories:list.html.twig', array(
            'categories' => $catList,
        ));
    }
    
}
