<?php

namespace Admin\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Orders
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
     * @ORM\Column(name="date_order", type="datetime", nullable=false)
     */
    private $dateOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="seats_quantity", type="integer", nullable=false)
     */
    private $seatsQuantity;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_name", type="string", length=255, nullable=false)
     */
    private $contactName;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_phone", type="string", length=15, nullable=false)
     */
    private $contactPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_email", type="string", length=65, nullable=false)
     */
    private $contactEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="order_description", type="text", nullable=true)
     */
    private $orderDescription;

    /**
     * @var \RestaurantHalls
     *
     * @ORM\ManyToOne(targetEntity="RestaurantHalls")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hall_id", referencedColumnName="id")
     * })
     */
    private $hall;

    /**
     * @var \OrdersStatus
     *
     * @ORM\ManyToOne(targetEntity="OrdersStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="orders_status", referencedColumnName="id")
     * })
     */
    private $ordersStatus;



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
     * Set dateOrder
     *
     * @param \DateTime $dateOrder
     * @return Orders
     */
    public function setDateOrder($dateOrder)
    {
        $this->dateOrder = $dateOrder;
    
        return $this;
    }

    /**
     * Get dateOrder
     *
     * @return \DateTime 
     */
    public function getDateOrder()
    {
        return $this->dateOrder;
    }

    /**
     * Set seatsQuantity
     *
     * @param integer $seatsQuantity
     * @return Orders
     */
    public function setSeatsQuantity($seatsQuantity)
    {
        $this->seatsQuantity = $seatsQuantity;
    
        return $this;
    }

    /**
     * Get seatsQuantity
     *
     * @return integer 
     */
    public function getSeatsQuantity()
    {
        return $this->seatsQuantity;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return Orders
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    
        return $this;
    }

    /**
     * Get contactName
     *
     * @return string 
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     * @return Orders
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    
        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string 
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     * @return Orders
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    
        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string 
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set orderDescription
     *
     * @param string $orderDescription
     * @return Orders
     */
    public function setOrderDescription($orderDescription)
    {
        $this->orderDescription = $orderDescription;
    
        return $this;
    }

    /**
     * Get orderDescription
     *
     * @return string 
     */
    public function getOrderDescription()
    {
        return $this->orderDescription;
    }

    /**
     * Set hall
     *
     * @param \Admin\OrderBundle\Entity\RestaurantHalls $hall
     * @return Orders
     */
    public function setHall(\Admin\OrderBundle\Entity\RestaurantHalls $hall = null)
    {
        $this->hall = $hall;
    
        return $this;
    }

    /**
     * Get hall
     *
     * @return \Admin\OrderBundle\Entity\RestaurantHalls 
     */
    public function getHall()
    {
        return $this->hall;
    }

    /**
     * Set ordersStatus
     *
     * @param \Admin\OrderBundle\Entity\OrdersStatus $ordersStatus
     * @return Orders
     */
    public function setOrdersStatus(\Admin\OrderBundle\Entity\OrdersStatus $ordersStatus = null)
    {
        $this->ordersStatus = $ordersStatus;
    
        return $this;
    }

    /**
     * Get ordersStatus
     *
     * @return \Admin\OrderBundle\Entity\OrdersStatus 
     */
    public function getOrdersStatus()
    {
        return $this->ordersStatus;
    }
}