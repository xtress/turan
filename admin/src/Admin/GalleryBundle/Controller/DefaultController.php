<?php

namespace Admin\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminGalleryBundle:Default:index.html.twig');
    }
}
