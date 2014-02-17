<?php

namespace Admin\MainBundle\Session;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
 
class Storage extends NativeSessionStorage{
    public function __construct($options = array(), ContainerInterface $container)
    {
        if ($container->isScopeActive('request')) { 
            $request = $container->get('request');
 
            if ($request->request->has('sessionId')) {
                $request->cookies->set(session_name(), 1);
                session_id($request->request->get('sessionId'));
            }
        }
 
        return parent::__construct($options);
    }
}
