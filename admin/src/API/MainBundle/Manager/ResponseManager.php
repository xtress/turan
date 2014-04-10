<?php

namespace Api\MainBundle\Manager;

use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class ResponseManager {

    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function returnDefaultResponse($alert='', $data = null)
    {
        $response = array(
            'status' => true,
            'message' => $alert,
            'content' => $data
        );
        return new Response(json_encode($response));
    }

    public function returnErrorResponse($msg)
    {
        $response = array(
            'status' => false,
            'message' => $msg,
        );
        return new Response(json_encode($response));
    }


}