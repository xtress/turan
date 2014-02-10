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
    
    const newsClass         = 'Admin\NewsBundle\Entity\News';
    const newsParentDir     = '/../../front-end/app/content/';
    const newsDirName       = 'news';
    const newsDir           = '/../../front-end/app/content/news';
    
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
                    $news->setIsPublished($data->getIsPublished());
                    $news->setNewsCategories($em->getRepository('Admin\NewsBundle\Entity\NewsCategories')->find($data->getNewsCategories()->getId()));
                    $news->setLocale($data->getLocale());

                    $em->persist($news);
                    $em->flush();
                    
                    $this->generateJSON($news);
                    $this->generatePaginationJSON();
                    $this->generateLastNewsJson();
                    
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
            
            $id = $news->getId();
            
            $em->remove($news);
            $em->flush();
            
            $this->generatePaginationJSON();
            $this->removeNewsJson($id.".json");
            
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
                
                $oldTitle = $news->getTitle();
                $oldPublishFlag = $news->getIsPublished();

                $news->setBody($data->getBody());
                $news->setTitle($data->getTitle());
                $news->setIsPublished($data->getIsPublished());
                $news->setNewsCategories($em->getRepository('Admin\NewsBundle\Entity\NewsCategories')->find($data->getNewsCategories()->getId()));
                $news->setLocale($data->getLocale());
                $news->setModifier($user);
                $news->setUpdatedAt(new \DateTime());
                
                $em->persist($news);
                $em->flush();
                
                $this->generateJSON($news);
                if (
                        $data->getIsPublished() !== $oldPublishFlag
                        || $data->getTitle() !== $oldTitle ) {
                    $this->generatePaginationJSON();
                }
                $this->generateLastNewsJson();

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
        
        $newsList = $repo->getNews($this->get('session')->get('_locale'));
        
        return $this->render('AdminNewsBundle:News:list.html.twig', array(
            'newsList' => $newsList,
        ));
    }
    
    /**
     * Is only alias to gain access to regenerateJsons, unlinkJsons, generatePaginationJSON functions
     */
    public function serviceRegenerateAction($modifier = 'id', $locale = 'ru')
    {
        $bool = false;
        while (!$bool) {
            $bool = $this->unlinkJsons($locale);
        }
        
        $this->regenerateJsons($modifier);
        $this->generatePaginationJSON($modifier);
        $this->generateLastNewsJson();
        
        return $this->redirect($this->generateUrl('admin_news_index'));
    }
    
    /**
     * This function is only public alias to generateLastNewsJson method
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function generateLastNewsAction(Request $request)
    {
        $this->generateLastNewsJson();
        
        return $this->redirect($request->server->get('HTTP_REFERER'));
    }
    
    private function generateLastNewsJson()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Admin\NewsBundle\Entity\News');
        $locale = $this->get('session')->get('_locale');
        $lastNewsArr = array();
        
        $lastNews = $repo->getLastNews(5, $locale);
        $quantity = count($lastNews);
        
        if ($quantity > 0) {
            
            foreach ($lastNews as $key => $value) {

                $lastNewsArr["id".$key] = $value->__toArray();
                $lastNewsArr["id".$key]['url'] = "#/news/".$value->getId();
                
                $body = $lastNewsArr["id".$key]['content'];
                $body = html_entity_decode(strip_tags($body));
                
                if ($locale === 'ru')
                    $substring_limited = substr($body, 0, 500);
                else
                    $substring_limited = substr($body, 0, 300);
                $lastNewsArr["id".$key]['content'] = substr($substring_limited, 0, strrpos($substring_limited, ' ' ))."...";

            }
            
        }
        
        file_put_contents(getcwd().self::newsDir."/".$locale."/lastNews.json", json_encode($lastNewsArr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
    
    /**
     * Generates json file for pagination under current locale
     * needs try/catch block
     */
    private function generatePaginationJSON($caseModifier = 'id')
    {
        $repo = $this->getDoctrine()->getEntityManager()->getRepository('\Admin\NewsBundle\Entity\News');
        $locale = $this->get('session')->get('_locale');
        $pagination = array();

        $newsIterator = $repo->getNewsIteratorWLocale($locale);
        $quantity = count($newsIterator);

        if ($quantity > 0) {
            switch ($caseModifier) {

                case 'id':
                    foreach ($newsIterator as $key => $value) {
                        if (
                            ($key != 0) &&
                            ($key != ($quantity - 1))
                        ) {

                            $pagination["id".$value['id']] = array(
                                'previous'  => $newsIterator[$value['iterator']-2]['id'],
                                'next'      => $newsIterator[$value['iterator']]['id'],
                                'page'      => $value["iterator"]."/".count($newsIterator),
                                'alias'     => $value['id'],
                                'category'  => $value['news_category'],
                            );

                        }
                        elseif ( $key != ($quantity - 1) || $quantity === 1 ) {

                            if ($quantity !== 1) {
                                $pagination["id".$value['id']] = array(
                                    'previous'  => $value['id'],
                                    'next'      => $newsIterator[$value['iterator']]['id'],
                                    'page'      => $value["iterator"]."/".count($newsIterator),
                                    'alias'     => $value['id'],
                                    'category'  => $value['news_category'],
                                );
                            } else {
                                $pagination["id".$value['id']] = array(
                                    'previous'  => $value['id'],
                                    'next'      => $value['id'],
                                    'page'      => $value["iterator"]."/".count($newsIterator),
                                    'alias'     => $value['id'],
                                    'category'  => $value['news_category'],
                                );
                            }

                        }
                        else {

                            $pagination["id".$value['id']] = array(
                                'previous'  => $newsIterator[$value['iterator']-2]['id'],
                                'next'      => $value['id'],
                                'page'      => $value["iterator"]."/".count($newsIterator),
                                'alias'     => $value['id'],
                                'category'  => $value['news_category'],
                            );

                        }
                    }
                    break;
                case 'title':
                    foreach ($newsIterator as $key => $value) {
                        if (
                            ($key != 0) &&
                            ($key != ($quantity - 1))
                        ) {

                            $pagination[$value["iterator"]] = array(
                                'previous'  => $newsIterator[$value['iterator']-2]['title'],
                                'next'      => $newsIterator[$value['iterator']]['title'],
                                'page'      => $value["iterator"]."/".count($newsIterator),
                                'alias'     => $value['title'],
                                'category'  => $value['news_category'],
                            );

                        }
                        elseif ( $key != ($quantity - 1) || $quantity === 1 ) {

                            if ($quantity !== 1) {
                                $pagination[$value["iterator"]] = array(
                                    'previous'  => $value['title'],
                                    'next'      => $newsIterator[$value['iterator']]['title'],
                                    'page'      => $value["iterator"]."/".count($newsIterator),
                                    'alias'     => $value['title'],
                                    'category'  => $value['news_category'],
                                );
                            } else {
                                $pagination[$value["iterator"]] = array(
                                    'previous'  => $value['title'],
                                    'next'      => $value['title'],
                                    'page'      => $value["iterator"]."/".count($newsIterator),
                                    'alias'     => $value['title'],
                                    'category'  => $value['news_category'],
                                );
                            }

                        }
                        else {

                            $pagination[$value["iterator"]] = array(
                                'previous'  => $newsIterator[$value['iterator']-2]['title'],
                                'next'      => $value['title'],
                                'page'      => $value["iterator"]."/".count($newsIterator),
                                'alias'     => $value['title'],
                                'category'  => $value['news_category'],
                            );

                        }
                    }
                    break;

            }
        }

        file_put_contents(getcwd().self::newsDir."/".$locale."/pagination.json", json_encode($pagination, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
    
    /**
     * Generates json file for a single news
     * needs try/catch block
     * 
     * @param \Admin\NewsBundle\Entity\News $news
     */
    private function generateJSON(\Admin\NewsBundle\Entity\News $news, $caseModifier = 'id')
    {
        
        $newsArray = $news->__toArray();
        
        if (!file_exists(getcwd().self::newsDir)) {
            if (!$this->generateNewsDirStructure()) {
                var_dump(false);exit;
            }
        }
        
        switch ($caseModifier) {
            case 'id':
                file_put_contents(getcwd().self::newsDir."/".$news->getLocale()->__toLocaleString()."/".$news->getId().".json", json_encode($newsArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
                break;
            case 'title':
                file_put_contents(getcwd().self::newsDir."/".$news->getLocale()->__toLocaleString()."/".$news->getTitle().".json", json_encode($newsArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
                break;
        }
    }
    
    /**
     * Removes json file for deleted news
     * 
     * @param type $fileName
     * @return boolean
     */
    private function removeNewsJson($fileName)
    {
        $locale = $this->get('session')->get('_locale');
        $dir = getcwd().self::newsDir."/".$locale;
        
        if (is_file($dir.DIRECTORY_SEPARATOR.$fileName))
            unlink($dir.DIRECTORY_SEPARATOR.$fileName);
        
        return true;
    }
    
    /**
     * Generates directory structure under AngularJS's content folder for news.
     * needs try/catch block
     * 
     * @return boolean
     */
    private function generateNewsDirStructure()
    {
        $flag = false;
        
        while(!$flag) {
            
            if (!file_exists(getcwd().self::newsDir)) {
                
                mkdir(getcwd().self::newsDir."/en", 0777, true);
                mkdir(getcwd().self::newsDir."/ru", 0777, true);
                
            }
            
            $flag = true;
            
        }
        
        return true;
    }
    
    /**
     * Added for test purposes. Regenerates json files for news
     * needs try/catch block
     * 
     * @return boolean
     */
    private function regenerateJsons($modifier)
    {
        $repo = $this->getDoctrine()->getEntityManager()->getRepository('\Admin\NewsBundle\Entity\News');
        
        $news = $repo->findAll();
        
        foreach ($news as $newsEntity) {
            
            $this->generateJSON($newsEntity, $modifier);
            
        }
        
        return true;
    }
    
    /**
     * Be careful! Removes all files under news/{locale} directory
     * 
     * @param type $locale
     * @return boolean
     */
    private function unlinkJsons($locale)
    {
        $dir = getcwd().self::newsDir.DIRECTORY_SEPARATOR.$locale;
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            unlink($dir.DIRECTORY_SEPARATOR.$item);
        }
        
        return true;
    }
    
}
