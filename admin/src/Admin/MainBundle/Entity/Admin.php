<?php

namespace Admin\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Admin
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity(repositoryClass="Admin\MainBundle\Repository\AdminRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Admin implements AdvancedUserInterface, \Serializable
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
     * @ORM\ManyToMany(targetEntity="AdminRoles")
     * @ORM\JoinTable(name="admin_to_admin_roles",
     *     joinColumns={@ORM\JoinColumn(name="admin_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="admin_roles_id", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection $userRoles
     */
    protected $userRoles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \Admin
     *
     * @ORM\ManyToOne(targetEntity="Admin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;



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
     * @return Admin
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
     * @return Admin
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
     * @return Admin
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Admin
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdBy
     *
     * @param \Admin\MainBundle\Entity\Admin $createdBy
     * @return Admin
     */
    public function setCreatedBy(\Admin\MainBundle\Entity\Admin $createdBy = null)
    {
        $this->createdBy = $createdBy;
    
        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Admin\MainBundle\Entity\Admin 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    
    public function getUserRoles()
    {
        return $this->userRoles;
    }
    
    public function setUserRoles($roles)
    {
        $this->userRoles = ($roles);
    }
    
    public function addRoles($role)
    {
        $this->roles->add($role);
    }

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->login;
    }
    
    //IMPLEMENTATIONS (5 first are to keep AdvancedUserInterface structure)
    public function isEnabled() {
        return true;
    }
    
    public function isAccountNonExpired() {
        return true;
    }
    
    public function isAccountNonLocked() {
        return true;
    }
    
    public function isCredentialsNonExpired() {
        return true;
    }
    
    public function eraseCredentials() {
        return false;
    }
    
    public function getUsername() {
        return $this->login;
    }
    
    public function getRoles()
    { 
       return $this->getUserRoles()->toArray();
    }
    
    public function serialize()
    {
        $data = array(
            'id' => $this->id,
            'login' => $this->login,            
        );
        return serialize($data);
    }

    public function unserialize($data)
    {
        $data = unserialize($data);
        $this->id = $data['id'];
        $this->login = $data['login'];        
    }
}