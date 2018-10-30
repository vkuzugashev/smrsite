<?php

namespace ITSPS\SmRecordBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phones
 *
 * @ORM\Table(name="userphone")
 * @ORM\Entity
 */
class UserPhone
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=30, unique=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;

    /**
     * @var integer
     *
     * @ORM\Column(name="isrec", type="integer")
     */
    private $isrec;

    /**
     * @var integer
     *
     * @ORM\Column(name="hourfrom", type="integer")
     */
    private $hourfrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="hourto", type="integer")
     */
    private $hourto;

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
     * Set phone
     *
     * @param string $phone
     * @return Phones
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
     * Set description
     *
     * @param string $description
     * @return Phones
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set userid
     *
     * @param integer $userid
     * @return UserPhone
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }
    
    /**
     * Set userid
     *
     * @param integer $isrec
     * @return UserPhone
     */
    public function setIsrec($isrec)
    {
        $this->isrec = $isrec;

        return $this;
    }

    /**
     * Get isrec
     *
     * @return integer 
     */
    public function getIsrec()
    {
        return $this->isrec;
    }    

    
    /**
     * Set hourfrom
     *
     * @param integer $hourfrom
     * @return UserPhone
     */
    public function setHourfrom($hourfrom)
    {
        $this->hourfrom = $hourfrom;

        return $this;
    }

    /**
     * Get hourfrom
     *
     * @return integer 
     */
    public function getHourfrom()
    {
        return $this->hourfrom;
    }    
    
 /**
     * Set hourfrom
     *
     * @param integer $hourfrom
     * @return UserPhone
     */
    public function setHourto($hourto)
    {
        $this->hourto = $hourto;

        return $this;
    }

    /**
     * Get hourfrom
     *
     * @return integer 
     */
    public function getHourto()
    {
        return $this->hourto;
    }    
    
    
    
    }
