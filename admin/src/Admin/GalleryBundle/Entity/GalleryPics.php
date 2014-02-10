<?php

namespace Admin\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GalleryPics
 *
 * @ORM\Table(name="gallery_pics")
 * @ORM\Entity(repositoryClass="Admin\GalleryBundle\Repository\GalleryPicsRepository")
 */
class GalleryPics
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
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="frontend_path", type="string", length=255, nullable=false)
     */
    private $frontendPath;

    /**
     * @var string
     *
     * @ORM\Column(name="filepath", type="string", length=255, nullable=false)
     */
    private $filepath;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=true)
     */
    private $title;

    /**
     * @var \Gallery
     *
     * @ORM\ManyToOne(targetEntity="Gallery")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     * })
     */
    private $gallery;



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
     * @return GalleryPics
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
     * Set frontendPath
     *
     * @param string $frontendPath
     * @return GalleryPics
     */
    public function setFrontendPath($frontendPath)
    {
        $this->frontendPath = $frontendPath;
    
        return $this;
    }

    /**
     * Get frontendPath
     *
     * @return string 
     */
    public function getFrontendPath()
    {
        return $this->frontendPath;
    }

    /**
     * Set filepath
     *
     * @param string $filepath
     * @return GalleryPics
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    
        return $this;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return GalleryPics
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
     * Set gallery
     *
     * @param \Admin\GalleryBundle\Entity\Gallery $gallery
     * @return GalleryPics
     */
    public function setGallery(\Admin\GalleryBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;
    
        return $this;
    }

    /**
     * Get gallery
     *
     * @return \Admin\GalleryBundle\Entity\Gallery 
     */
    public function getGallery()
    {
        return $this->gallery;
    }
    
    public function getPicture()
    {
        return $this->filepath."/".$this->name;
    }
    
    public function getThumb()
    {
        return $this->filepath."/thumbs/".$this->name;
    }
}