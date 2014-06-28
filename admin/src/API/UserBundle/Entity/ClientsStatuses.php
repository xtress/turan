<?php

namespace API\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientsStatuses
 *
 * @ORM\Table(name="clients_statuses")
 * @ORM\Entity
 */
class ClientsStatuses
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
     * @var \DateTime
     *
     * @ORM\Column(name="status_date", type="datetime", nullable=false)
     */
    private $statusDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var \Admin
     *
     * @ORM\ManyToOne(targetEntity="Admin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="initiator_id", referencedColumnName="id")
     * })
     */
    private $initiator;

    /**
     * @var \Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;

    /**
     * @var \Initiators
     *
     * @ORM\ManyToOne(targetEntity="Initiators")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_initiator", referencedColumnName="id")
     * })
     */
    private $statusInitiator;

    /**
     * @var \Statuses
     *
     * @ORM\ManyToOne(targetEntity="Statuses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;



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
     * Set statusDate
     *
     * @param \DateTime $statusDate
     * @return ClientsStatuses
     */
    public function setStatusDate($statusDate)
    {
        $this->statusDate = $statusDate;
    
        return $this;
    }

    /**
     * Get statusDate
     *
     * @return \DateTime 
     */
    public function getStatusDate()
    {
        return $this->statusDate;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return ClientsStatuses
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set initiator
     *
     * @param \API\UserBundle\Entity\Admin $initiator
     * @return ClientsStatuses
     */
    public function setInitiator(\API\UserBundle\Entity\Admin $initiator = null)
    {
        $this->initiator = $initiator;
    
        return $this;
    }

    /**
     * Get initiator
     *
     * @return \API\UserBundle\Entity\Admin 
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * Set client
     *
     * @param \API\UserBundle\Entity\Clients $client
     * @return ClientsStatuses
     */
    public function setClient(\API\UserBundle\Entity\Clients $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \API\UserBundle\Entity\Clients 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set statusInitiator
     *
     * @param \API\UserBundle\Entity\Initiators $statusInitiator
     * @return ClientsStatuses
     */
    public function setStatusInitiator(\API\UserBundle\Entity\Initiators $statusInitiator = null)
    {
        $this->statusInitiator = $statusInitiator;
    
        return $this;
    }

    /**
     * Get statusInitiator
     *
     * @return \API\UserBundle\Entity\Initiators 
     */
    public function getStatusInitiator()
    {
        return $this->statusInitiator;
    }

    /**
     * Set status
     *
     * @param \API\UserBundle\Entity\Statuses $status
     * @return ClientsStatuses
     */
    public function setStatus(\API\UserBundle\Entity\Statuses $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \API\UserBundle\Entity\Statuses 
     */
    public function getStatus()
    {
        return $this->status;
    }
}