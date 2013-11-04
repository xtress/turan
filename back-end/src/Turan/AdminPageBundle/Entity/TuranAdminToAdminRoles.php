<?php

namespace Turan\AdminPageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TuranAdminToAdminRoles
 *
 * @ORM\Table(name="turan_admin_to_admin_roles")
 * @ORM\Entity
 */
class TuranAdminToAdminRoles
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
     * @var \TuranAdmin
     *
     * @ORM\ManyToOne(targetEntity="TuranAdmin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="turan_admin_id", referencedColumnName="id")
     * })
     */
    private $turanAdmin;

    /**
     * @var \TuranAdminRoles
     *
     * @ORM\ManyToOne(targetEntity="TuranAdminRoles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="turan_admin_roles_id", referencedColumnName="id")
     * })
     */
    private $turanAdminRoles;



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
     * Set turanAdmin
     *
     * @param \Turan\AdminPageBundle\Entity\TuranAdmin $turanAdmin
     * @return TuranAdminToAdminRoles
     */
    public function setTuranAdmin(\Turan\AdminPageBundle\Entity\TuranAdmin $turanAdmin = null)
    {
        $this->turanAdmin = $turanAdmin;
    
        return $this;
    }

    /**
     * Get turanAdmin
     *
     * @return \Turan\AdminPageBundle\Entity\TuranAdmin 
     */
    public function getTuranAdmin()
    {
        return $this->turanAdmin;
    }

    /**
     * Set turanAdminRoles
     *
     * @param \Turan\AdminPageBundle\Entity\TuranAdminRoles $turanAdminRoles
     * @return TuranAdminToAdminRoles
     */
    public function setTuranAdminRoles(\Turan\AdminPageBundle\Entity\TuranAdminRoles $turanAdminRoles = null)
    {
        $this->turanAdminRoles = $turanAdminRoles;
    
        return $this;
    }

    /**
     * Get turanAdminRoles
     *
     * @return \Turan\AdminPageBundle\Entity\TuranAdminRoles 
     */
    public function getTuranAdminRoles()
    {
        return $this->turanAdminRoles;
    }
}