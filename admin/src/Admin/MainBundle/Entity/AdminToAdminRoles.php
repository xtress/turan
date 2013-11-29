<?php

namespace Admin\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminToAdminRoles
 *
 * @ORM\Table(name="admin_to_admin_roles")
 * @ORM\Entity
 */
class AdminToAdminRoles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Admin
     *
     * @ORM\ManyToOne(targetEntity="Admin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     * })
     */
    private $admin;

    /**
     * @var \AdminRoles
     *
     * @ORM\ManyToOne(targetEntity="AdminRoles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_roles_id", referencedColumnName="id")
     * })
     */
    private $adminRoles;



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
     * Set admin
     *
     * @param \Admin\MainBundle\Entity\Admin $admin
     * @return AdminToAdminRoles
     */
    public function setAdmin(\Admin\MainBundle\Entity\Admin $admin = null)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return \Admin\MainBundle\Entity\Admin 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set adminRoles
     *
     * @param \Admin\MainBundle\Entity\AdminRoles $adminRoles
     * @return AdminToAdminRoles
     */
    public function setAdminRoles(\Admin\MainBundle\Entity\AdminRoles $adminRoles = null)
    {
        $this->adminRoles = $adminRoles;
    
        return $this;
    }

    /**
     * Get adminRoles
     *
     * @return \Admin\MainBundle\Entity\AdminRoles 
     */
    public function getAdminRoles()
    {
        return $this->adminRoles;
    }
}