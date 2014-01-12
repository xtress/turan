<?php

namespace Helpers;

class ServiceBridge {
    
    protected $_serviceContainer;
    protected static $_instance;
    
    protected function __construct($serviceContainer) 
    {
        $this->_serviceContainer = $serviceContainer;        
    }
    
    public static function getInstance($serviceContainer = null) 
    {        
        if (self::$_instance == null || !isset(self::$_instance)) {
            return self::$_instance = new ServiceBridge($serviceContainer);            
        } else {            
            return self::$_instance;            
        }        
    }
    
    public function get($serviceName) 
    {
        return $this->_serviceContainer->get($serviceName);        
    }

    public function getParameter($paramName) 
    {
        return $this->_serviceContainer->getParameter($paramName);        
    }

    public function hasParameter($paramName)
    {
        return $this->_serviceContainer->hasParameter($paramName);
    }

    public function getServiceContainer()
    {
        return $this->_serviceContainer;
    }
    
}
