<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="price")
 */
class Price
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="Please provide an amount")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BookingType", inversedBy="price")
     * @ORM\JoinColumn(name="booking_type_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank(message="Please provide booking type")
     */
    private $bookingType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Field", inversedBy="price")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank(message="Please provide field")
     */
    private $field;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Session", mappedBy="price")
     * @Assert\NotBlank(message="Please provide session")
     */
    private $session;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Day", mappedBy="price")
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
        return $this->amount;
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
     * Set amount
     *
     * @param float $amount
     *
     * @return Price
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set bookingType
     *
     * @param \App\Entity\BookingType $bookingType
     *
     * @return Price
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
     * Set center
     *
     * @param \App\Entity\Center $center
     *
     * @return Price
     */
    public function setCenter(\App\Entity\Center $center = null)
    {
        $this->center = $center;

        return $this;
    }

    /**
     * Get center
     *
     * @return \App\Entity\Center
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * Add session
     *
     * @param \App\Entity\Session $session
     *
     * @return Price
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
     * @return Price
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
     * Set field.
     *
     * @param \App\Entity\Field|null $field
     *
     * @return Price
     */
    public function setField(\App\Entity\Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field.
     *
     * @return \App\Entity\Field|null
     */
    public function getField()
    {
        return $this->field;
    }
}
