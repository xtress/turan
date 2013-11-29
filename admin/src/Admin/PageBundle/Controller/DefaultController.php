<?php

namespace Admin\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminPageBundle:Default:index.html.twig');
    }
}
