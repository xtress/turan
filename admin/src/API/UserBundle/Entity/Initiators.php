<?php

namespace API\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Initiators
 *
 * @ORM\Table(name="initiators")
 * @ORM\Entity
 */
class Initiators
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
     * @ORM\Column(name="sys_name", type="string", length=45, nullable=false)
     */
    private $sysName;



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
     * Set sysName
     *
     * @param string $sysName
     * @return Initiators
     */
    public function setSysName($sysName)
    {
        $this->sysName = $sysName;
    
        return $this;
    }

    /**
     * Get sysName
     *
     * @return string 
     */
    public function getSysName()
    {
        return $this->sysName;
    }
}