<?php

namespace Admin\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsCategories
 *
 * @ORM\Table(name="news_categories")
 * @ORM\Entity(repositoryClass="Admin\NewsBundle\Repository\NewsCategoriesRepository")
 */
class NewsCategories
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
     * Set name
     *
     * @param string $name
     * @return NewsCategories
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
}