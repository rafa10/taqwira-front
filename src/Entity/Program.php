<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="program")
 */
class Program
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BookingType", inversedBy="program")
     * @ORM\JoinColumn(name="booking_type_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $bookingType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Field", inversedBy="program")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank(message="Please provide field")
     */
    private $field;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Session", mappedBy="program")
     * @Assert\NotBlank(message="Please provide session")
     */
    private $session;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Day", mappedBy="program")
     * @Assert\NotBlank(message="Please provide day")
     */
    private $day;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->session = new \Doctrine\Common\Collections\ArrayCollection();
        $this->day = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
//        return $this->;
    }


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
     * Set bookingType
     *
     * @param \App\Entity\BookingType $bookingType
     *
     * @return Program
     */
    public function setBookingType(\App\Entity\BookingType $bookingType = null)
    {
        $this->bookingType = $bookingType;

        return $this;
    }

    /**
     * Get bookingType
     *
     * @return \App\Entity\BookingType
     */
    public function getBookingType()
    {
        return $this->bookingType;
    }

    /**
     * Add session
     *
     * @param \App\Entity\Session $session
     *
     * @return Program
     */
    public function addSession(\App\Entity\Session $session)
    {
        $this->session[] = $session;

        return $this;
    }

    /**
     * Remove session
     *
     * @param \App\Entity\Session $session
     */
    public function removeSession(\App\Entity\Session $session)
    {
        $this->session->removeElement($session);
    }

    /**
     * Get session
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Add day
     *
     * @param \App\Entity\Day $day
     *
     * @return Program
     */
    public function addDay(\App\Entity\Day $day)
    {
        $this->day[] = $day;

        return $this;
    }

    /**
     * Remove day
     *
     * @param \App\Entity\Day $day
     */
    public function removeDay(\App\Entity\Day $day)
    {
        $this->day->removeElement($day);
    }

    /**
     * Get day
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set field
     *
     * @param \App\Entity\Field $field
     *
     * @return Program
     */
    public function setField(\App\Entity\Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \App\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }
}
