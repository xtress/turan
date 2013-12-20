<?php

namespace Admin\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\DBAL\DBALException as DBALException;

use Admin\NewsBundle\Entity\News;
use Admin\NewsBundle\Form\NewsType;

/**
 * Description of NewsController
 *
 * @author uncle_empty
 */
class NewsController extends Controller {
    
    const newsClass = 'Admin\NewsBundle\Entity\News';
    
    public function indexAction()
    {
        return $this->render('AdminNewsBundle:News:index.html.twig');
    }
    
    public function createAction(Request $request)
    {
        $user       = $this->get('security.context')->getToken()->getUser();
        $form       = $this->createForm(new NewsType());
        $em         = $this->getDoctrine()->getEntityManager();
        $session    = $this->get('session');
        $translator = $this->get('translator');
        
        if ($request->getMethod() === "POST") {
            
            $form->handleRequest($request);
            $data = $form->getData();
            
            if ($form->isValid()) {
                
                try {
                    
                    $newsCategory = new News();

                    $newsCategory->setName($data->getName());

                    $em->persist($newsCategory);
                    $em->flush();
                    
                } catch(DBALException $e) {
                    
                    var_dump($e->getMessage());
                    $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_CREATING'));
                    return $this->redirect($this->generateUrl('admin_news_categories_create'));
                    
                }
                
                $session->getFlashBag()->set('success', $translator->trans('ANB_NEWS_ADDED'));
                return $this->redirect($this->generateUrl('admin_news_categories_index'));
                
            } else {
                
                $session->getFlashBag()->set('error', $translator->trans('ANB_FORM_NOT_VALID'));
                return $this->redirect($this->generateUrl('admin_news_categories_create'));
                
            }
            
        }
        
        return $this->render('AdminNewsBundle:News:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function deleteAction(Request $request, $newsID)
    {
        
    }
    
    public function editAction(Request $request, $newsID)
    {
        
    }
    
    public function saveAction(Request $request, $newsID)
    {
        
    }
    
    public function listAction(Request $request)
    {
        
    }
    
}
