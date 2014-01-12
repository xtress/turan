<?php

namespace Admin\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="Admin\NewsBundle\Repository\NewsRepository")
 */
class News
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @var \Admin
     *
     * @ORM\ManyToOne(targetEntity="Admin\MainBundle\Entity\Admin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var \Admin
     *
     * @ORM\ManyToOne(targetEntity="Admin\MainBundle\Entity\Admin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modifier", referencedColumnName="id")
     * })
     */
    private $modifier;

    /**
     * @var \NewsCategories
     *
     * @ORM\ManyToOne(targetEntity="NewsCategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_categories_id", referencedColumnName="id")
     * })
     */
    private $newsCategories;

    /**
     * @var Admin\NewsBundle\Entity\Locale
     *
     * @ORM\ManyToOne(targetEntity="Admin\NewsBundle\Entity\Locale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="locale", referencedColumnName="locale")
     * })
     */
    private $locale;



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
     * Set title
     *
     * @param string $title
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return News
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return News
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return News
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     * @return News
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
     * Set creator
     *
     * @param \Admin\MainBundle\Entity\Admin $creator
     * @return News
     */
    public function setCreator(\Admin\MainBundle\Entity\Admin $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return \Admin\MainBundle\Entity\Admin 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set modifier
     *
     * @param \Admin\MainBundle\Entity\Admin $modifier
     * @return News
     */
    public function setModifier(\Admin\MainBundle\Entity\Admin $modifier = null)
    {
        $this->modifier = $modifier;
    
        return $this;
    }

    /**
     * Get modifier
     *
     * @return \Admin\MainBundle\Entity\Admin 
     */
    public function getModifier()
    {
        return $this->modifier;
    }

    /**
     * Set newsCategories
     *
     * @param \Admin\NewsBundle\Entity\NewsCategories $newsCategories
     * @return News
     */
    public function setNewsCategories(\Admin\NewsBundle\Entity\NewsCategories $newsCategories = null)
    {
        $this->newsCategories = $newsCategories;
    
        return $this;
    }

    /**
     * Get newsCategories
     *
     * @return \Admin\NewsBundle\Entity\NewsCategories 
     */
    public function getNewsCategories()
    {
        return $this->newsCategories;
    }

    /**
     * Set locale
     *
     * @param \Admin\NewsBundle\Entity\Locale $locale
     * @return Locale
     */
    public function setLocale(\Admin\NewsBundle\Entity\Locale $locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return \Admin\NewsBundle\Entity\Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }
    
    public function __toArray()
    {
        return array("title" => $this->title, "content" => $this->body);
    }
}