<?php

namespace Turan\AdminPageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TuranPage
 *
 * @ORM\Table(name="turan_page")
 * @ORM\Entity
 */
class TuranPage
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
     * @var string
     *
     * @ORM\Column(name="page_name", type="string", length=127, nullable=false)
     */
    private $pageName;

    /**
     * @var string
     *
     * @ORM\Column(name="page_body", type="text", nullable=false)
     */
    private $pageBody;

    /**
     * @var string
     *
     * @ORM\Column(name="page_seo", type="string", length=15, nullable=false)
     */
    private $pageSeo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    private $dateModified;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean", nullable=false)
     */
    private $isPublished;

    /**
     * @var \TuranAdmin
     *
     * @ORM\ManyToOne(targetEntity="TuranAdmin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_creator_id", referencedColumnName="id")
     * })
     */
    private $adminCreator;

    /**
     * @var \TuranAdmin
     *
     * @ORM\ManyToOne(targetEntity="TuranAdmin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_modifier_id", referencedColumnName="id")
     * })
     */
    private $adminModifier;



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
     * Set pageName
     *
     * @param string $pageName
     * @return TuranPage
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;
    
        return $this;
    }

    /**
     * Get pageName
     *
     * @return string 
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * Set pageBody
     *
     * @param string $pageBody
     * @return TuranPage
     */
    public function setPageBody($pageBody)
    {
        $this->pageBody = $pageBody;
    
        return $this;
    }

    /**
     * Get pageBody
     *
     * @return string 
     */
    public function getPageBody()
    {
        return $this->pageBody;
    }

    /**
     * Set pageSeo
     *
     * @param string $pageSeo
     * @return TuranPage
     */
    public function setPageSeo($pageSeo)
    {
        $this->pageSeo = $pageSeo;
    
        return $this;
    }

    /**
     * Get pageSeo
     *
     * @return string 
     */
    public function getPageSeo()
    {
        return $this->pageSeo;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return TuranPage
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    
        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     * @return TuranPage
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;
    
        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     * @return TuranPage
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
    
        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean 
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set adminCreator
     *
     * @param \Turan\AdminPageBundle\Entity\TuranAdmin $adminCreator
     * @return TuranPage
     */
    public function setAdminCreator(\Turan\AdminPageBundle\Entity\TuranAdmin $adminCreator = null)
    {
        $this->adminCreator = $adminCreator;
    
        return $this;
    }

    /**
     * Get adminCreator
     *
     * @return \Turan\AdminPageBundle\Entity\TuranAdmin 
     */
    public function getAdminCreator()
    {
        return $this->adminCreator;
    }

    /**
     * Set adminModifier
     *
     * @param \Turan\AdminPageBundle\Entity\TuranAdmin $adminModifier
     * @return TuranPage
     */
    public function setAdminModifier(\Turan\AdminPageBundle\Entity\TuranAdmin $adminModifier = null)
    {
        $this->adminModifier = $adminModifier;
    
        return $this;
    }

    /**
     * Get adminModifier
     *
     * @return \Turan\AdminPageBundle\Entity\TuranAdmin 
     */
    public function getAdminModifier()
    {
        return $this->adminModifier;
    }
}