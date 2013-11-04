<?php

namespace Turan\AdminPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AdminPageBundle:Default:index.html.twig', array('name' => $name));
    }
}
