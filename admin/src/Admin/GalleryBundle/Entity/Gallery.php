<?php

namespace Admin\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="Admin\GalleryBundle\Repository\GalleryRepository")
 */
class Gallery
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_published", type="boolean", nullable=false)
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
     * @var \GalleryPics
     *
     * @ORM\ManyToOne(targetEntity="GalleryPics")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="main_pic", referencedColumnName="id")
     * })
     */
    private $mainPic;

    /**
     * @var \GalleryType
     *
     * @ORM\ManyToOne(targetEntity="GalleryType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gallery_type_id", referencedColumnName="id")
     * })
     */
    private $galleryType;

    /**
     * @var \Locale
     *
     * @ORM\ManyToOne(targetEntity="Locale")
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
     * Set name
     *
     * @param string $name
     * @return Gallery
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     * @return Gallery
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
     * @return Gallery
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
     * Set mainPic
     *
     * @param \Admin\GalleryBundle\Entity\GalleryPics $mainPic
     * @return Gallery
     */
    public function setMainPic(\Admin\GalleryBundle\Entity\GalleryPics $mainPic = null)
    {
        $this->mainPic = $mainPic;
    
        return $this;
    }

    /**
     * Get mainPic
     *
     * @return \Admin\GalleryBundle\Entity\GalleryPics 
     */
    public function getMainPic()
    {
        return $this->mainPic;
    }

    /**
     * Set galleryType
     *
     * @param \Admin\GalleryBundle\Entity\GalleryType $galleryType
     * @return Gallery
     */
    public function setGalleryType(\Admin\GalleryBundle\Entity\GalleryType $galleryType = null)
    {
        $this->galleryType = $galleryType;
    
        return $this;
    }

    /**
     * Get galleryType
     *
     * @return \Admin\GalleryBundle\Entity\GalleryType 
     */
    public function getGalleryType()
    {
        return $this->galleryType;
    }

    /**
     * Set locale
     *
     * @param \Admin\GalleryBundle\Entity\Locale $locale
     * @return Gallery
     */
    public function setLocale(\Admin\GalleryBundle\Entity\Locale $locale = null)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return \Admin\GalleryBundle\Entity\Locale 
     */
    public function getLocale()
    {
        return $this->locale;
    }
}