<?php

namespace API\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('APIMainBundle:Default:index.html.twig', array('name' => $name));
    }
}
