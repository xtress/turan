<?php

namespace Turan\AdminPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

#use of entities
use Turan\AdminPageBundle\Entity\TuranPage;

class AdminPageController extends Controller
{
    
    public function chooseAction()
    {
        $this->checkPrivilegues();
    }
    
    public function addPageAction(Request $request)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        
        $page = new TuranPage();
        
        $page->setDateCreated(new \DateTime());
        $page->setIsPublished(true);
        $page->setPageName("test");
        $page->setPageSeo("test_page");
        $page->setBody("<div style='text-color:red'>testtexttesttexttesttexttesttexttesttext</div>");
        $em->persist($page);
        $em->flush();
        
    }
    
    private function checkPrivilegues()
    {
        $secContext = $this->get('security.context')->getToken();
        var_dump($secContext->getUser());exit;
    }
    
    public function indexAction($name)
    {
        return $this->render('AdminPageBundle:Default:index.html.twig', array('name' => $name));
    }
}
