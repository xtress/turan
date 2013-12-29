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
                    
                    $news = new News();

                    $news->setTitle($data->getTitle());
                    $news->setCreator($user);
                    $news->setCreatedAt(new \DateTime());
                    $news->setBody($data->getBody());
                    $news->setIsPublished(true);
                    $news->setNewsCategories($em->getRepository('Admin\NewsBundle\Entity\NewsCategories')->find($data->getNewsCategories()->getId()));

                    $em->persist($news);
                    $em->flush();
                    
                    $this->generateJSON($news);
                    
                } catch(DBALException $e) {
                    
                    var_dump($e->getMessage());exit;
                    $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_CREATING'));
                    return $this->redirect($this->generateUrl('admin_news_create'));
                    
                }
                
                $session->getFlashBag()->set('success', $translator->trans('ANB_NEWS_ADDED'));
                return $this->redirect($this->generateUrl('admin_news_index'));
                
            } else {
                
                $session->getFlashBag()->set('error', $translator->trans('ANB_FORM_NOT_VALID'));
                return $this->redirect($this->generateUrl('admin_news_create'));
                
            }
            
        }
        
        return $this->render('AdminNewsBundle:News:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function deleteAction(Request $request, $newsID)
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepo = $em->getRepository(self::newsClass);
        $session    = $this->get('session');
        $translator = $this->get('translator');
        $news = $newsRepo->find($newsID);
        
        try {
            
            $em->remove($news);
            $em->flush();
            
        } catch (DBALException $e) {
                    
            var_dump($e->getMessage());
            $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_DELETING'));
            return $this->redirect($this->generateUrl('admin_news_list'));
            
        }
        
        $session->getFlashBag()->set('success', $translator->trans('ANB_NEWS_DELETED'));
        return $this->redirect($this->generateUrl('admin_news_list'));
    }
    
    public function editAction(Request $request, $newsID)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(self::newsClass);
        $news = $repo->find($newsID);
        $form       = $this->createForm(new NewsType(), $news);
        
        return $this->render('AdminNewsBundle:News:edit.html.twig', array(
            'form' => $form->createView(),
            'newsID' => $news->getId(),
        ));
    }
    
    public function saveAction(Request $request, $newsID)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $request    = $this->getRequest();
        $form       = $this->createForm(new NewsType());
        $em         = $this->getDoctrine()->getManager();
        $session    = $this->get('session');
        $translator = $this->get('translator');
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            try {
                
                $data = $form->getData();
                
                $news = $em->getRepository(self::newsClass)->find($newsID);

                $news->setBody($data->getBody());
                
                $em->persist($news);
                $em->flush();
                
                $this->generateJSON($news);
                
            } catch(DBALException $e) {
                    
                var_dump($e->getMessage());
                $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_EDITING'));
                return $this->redirect($this->generateUrl('admin_news_edit'));
                
            }
                
            $session->getFlashBag()->set('success', $translator->trans('ANB_NEWS_SAVED'));
            return $this->redirect($this->generateUrl('admin_news_list'));
            
        } else {

            $session->getFlashBag()->set('error', $translator->trans('ANB_FORM_NOT_VALID'));
            return $this->redirect($this->generateUrl('admin_news_edit'));

        }
    }
    
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(self::newsClass);
        
        $newsList = $repo->getNews();
        
        return $this->render('AdminNewsBundle:News:list.html.twig', array(
            'newsList' => $newsList,
        ));
    }
    
    private function generateJSON(\Admin\NewsBundle\Entity\News $news)
    {
        $repo = $this->getDoctrine()->getEntityManager()->getRepository('\Admin\NewsBundle\Entity\News');
        
        $newsArray = $news->__toArray();
        $newsIterator = $repo->getNewsIterator();
        
        for ($i = 1; $i <= count($newsIterator); $i++) {
            
            if ($newsIterator[$i]['id'] == $news->getId()) {
                
                $currentEl = $newsIterator[$i];
                $currentPos = $i;
                
            }
            
        }
        
        if ($currentPos != 1)
            $newsArray["previous"] = "#/news/".$newsIterator[$currentPos - 1]['id'];
        else
            $newsArray["previous"] = "#/news/".$newsIterator[$currentPos]['id'];
        
        if ( $currentPos != count($newsIterator) )
            $newsArray["next"] = "#/news/".$newsIterator[$currentPos + 1]['id'];
        else
            $newsArray["next"] = "#/news/".$newsIterator[$currentPos]['id'];
        
        $newsArray["page"] = $currentPos."/".count($newsIterator);
        
//        var_dump($newsArray);exit;
        file_put_contents(getcwd()."/../../front-end/app/content/news/".$news->getId().".json", json_encode($newsArray, JSON_UNESCAPED_SLASHES), LOCK_EX);
    }
    
}
