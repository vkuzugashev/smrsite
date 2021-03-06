<?php

namespace ITSPS\SmRecordBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 *
 * @ORM\Table(name="Record")
 * @ORM\Entity
 */
class Record
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdt", type="datetime")
     */
    private $startdt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stopdt", type="datetime", nullable=true)
     */
    private $stopdt;

    /**
     * @var integer
     *
     * @ORM\Column(name="direction", type="integer")
     */
    private $direction;

    /**
     * @var boolean
     *
     * @ORM\Column(name="unanswered", type="integer")
     */
    private $unanswered;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="remotephone", type="string", length=15, nullable=true)
     */
    private $remotephone;

    /**
     * @var string
     *
     * @ORM\Column(name="recordfile", type="string", length=255, nullable=true)
     */
    private $recordfile;

    /**
     * @var integer
     *
     * @ORM\Column(name="filesize", type="integer")
     */
    private $filesize;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer", nullable=true)
     */
    private $userid;

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
     * Set startdt
     *
     * @param \DateTime $startdt
     * @return Record
     */
    public function setStartdt($startdt)
    {
        $this->startdt = $startdt;

        return $this;
    }

    /**
     * Get startdt
     *
     * @return \DateTime 
     */
    public function getStartdt()
    {
        return $this->startdt;
    }

    /**
     * Set stopdt
     *
     * @param \DateTime $stopdt
     * @return Record
     */
    public function setStopdt($stopdt)
    {
        $this->stopdt = $stopdt;

        return $this;
    }

    /**
     * Get stopdt
     *
     * @return \DateTime 
     */
    public function getStopdt()
    {
        return $this->stopdt;
    }

    /**
     * Set direction
     *
     * @param integer $direction
     * @return Record
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return integer 
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set unanswered
     *
     * @param boolean $unanswered
     * @return Record
     */
    public function setUnanswered($unanswered)
    {
        $this->unanswered = $unanswered;

        return $this;
    }

    /**
     * Get unanswered
     *
     * @return boolean 
     */
    public function getUnanswered()
    {
        return $this->unanswered;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Record
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Record
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
     * Set remotephone
     *
     * @param string $remotephone
     * @return Record
     */
    public function setRemotephone($remotephone)
    {
        $this->remotephone = $remotephone;

        return $this;
    }

    /**
     * Get remotephone
     *
     * @return string 
     */
    public function getRemotephone()
    {
        return $this->remotephone;
    }

    /**
     * Set recordfile
     *
     * @param string $recordfile
     * @return Record
     */
    public function setRecordfile($recordfile)
    {
        $this->recordfile = $recordfile;

        return $this;
    }

    /**
     * Get recordfile
     *
     * @return string 
     */
    public function getRecordfile()
    {
        return $this->recordfile;
    }

    /**
     * Set filesize
     *
     * @param integer $filesize
     * @return Record
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get filesize
     *
     * @return integer 
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Record
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
}
