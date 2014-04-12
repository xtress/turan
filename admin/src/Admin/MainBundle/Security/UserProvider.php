<?php

namespace Admin\MainBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Repository\CoreUserRepository;
use Admin\MainBundle\Entity\Admin;
//use Omlook\ChatBundle\Library\src\Chat;
//use Helpers\ServiceBridge;

class UserProvider extends EntityRepository implements UserProviderInterface {

    public function __construct($doctrine, $class)
    {
        $class_entity = $doctrine->getManager()->getClassMetadata($class);        
        parent::__construct($doctrine->getManager(), $class_entity);

    }
    
    public function loadUserByUsername($username)
    {
        $user    = $this->getEntityManager()->getRepository($this->_entityName)->getUser($username);
        
        if ($user && $user instanceof Admin)
        {
            return $user;
        } else {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

    }
    
    function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Admin) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }
    
    public function supportsClass($class) {

        return $class === 'Admin\MainBundle\Entity\Admin';
    }
    
}