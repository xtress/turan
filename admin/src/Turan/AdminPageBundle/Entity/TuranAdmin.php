<?php

namespace Turan\AdminPageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TuranAdmin
 *
 * @ORM\Table(name="turan_admin")
 * @ORM\Entity
 */
class TuranAdmin implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=20, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

     /**
     * @ORM\ManyToMany(targetEntity="TuranAdminRoles")
     * @ORM\JoinTable(name="turan_admin_to_admin_roles",
     *     joinColumns={@ORM\JoinColumn(name="turan_admin_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="turan_admin_roles_id", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection $userRoles
     */
    protected $userRoles;


    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return TuranAdmin
     */
    public function setLogin($login)
    {
        $this->login = $login;
    
        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return TuranAdmin
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return TuranAdmin
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }


    public function getUserRoles()
    {
        return $this->userRoles;
    }


    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }

    public function getUsername()
    {
        return $this->login;
    }
    
    public function eraseCredentials()
    {
 
    }

    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }
    
    
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
        ) = unserialize($serialized);
    }
}