<?php

namespace API\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Serializable;

/**
 * Clients
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity
 */
class Clients implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=65, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", length=65, nullable=true)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=65, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=13, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    /**
     * @var string
     *
     * @ORM\Column(name="session_token", type="string", length=255, nullable=true)
     */
    private $sessionToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="session_token_lifetime", type="datetime", nullable=true)
     */
    private $sessionTokenLifetime;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="datetime", nullable=true)
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="discount_card", type="string", length=255, nullable=true)
     */
    private $discountCard;

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
     * Set username
     *
     * @param string $username
     * @return Clients
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Clients
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Clients
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Clients
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return Clients
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    
        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Clients
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Clients
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Clients
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set confirmationToken
     *
     * @param string $confirmationToken
     * @return Clients
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    
        return $this;
    }

    /**
     * Get confirmationToken
     *
     * @return string 
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set sessionToken
     *
     * @param string $sessionToken
     * @return Clients
     */
    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
    
        return $this;
    }

    /**
     * Get sessionToken
     *
     * @return string 
     */
    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param string $discountCard
     */
    public function setDiscountCard($discountCard)
    {
        $this->discountCard = $discountCard;
    }

    /**
     * @return string
     */
    public function getDiscountCard()
    {
        return $this->discountCard;
    }


    /**
     * Set sessionTokenLifetime
     *
     * @param \DateTime $sessionTokenLifetime
     * @return Clients
     */
    public function setSessionTokenLifetime($sessionTokenLifetime)
    {
        $this->sessionTokenLifetime = $sessionTokenLifetime;
    
        return $this;
    }

    /**
     * Get sessionTokenLifetime
     *
     * @return \DateTime 
     */
    public function getSessionTokenLifetime()
    {
        return $this->sessionTokenLifetime;
    }

    //IMPLEMENTATIONS (5 first are to keep AdvancedUserInterface structure)
    public function isEnabled() {
        return true;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function eraseCredentials() {
        return false;
    }

    public function getRoles()
    {
        return array('ROLE_CLIENT');
    }

    public function getUserInfo()
    {
        return array(
            'username' => $this->getUsername(),
            'email'    => $this->getEmail(),
            'phone'    => $this->getPhone(),
            'birthDate'=> $this->getBirthDateAsText(),
            'discountCard'=> $this->getDiscountCard(),
            'token' => $this->getSessionToken(),
            );
    }

    public function serialize()
    {
        $data = array(
            'id' => $this->id,
            'username' => $this->username,
        );
        return serialize($data);
    }

    public function unserialize($data)
    {
        $data = unserialize($data);
        $this->id = $data['id'];
        $this->username = $data['username'];
    }

    public function getBirthDateAsText(){
        $result = $this->getBirthDate();

        if($result instanceof \DateTime){
           $result = $result->format('d.m.Y');
        }else{
            $result = '';
        }
    }
}