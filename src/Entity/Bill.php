<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="bill")
 */
class Bill
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Center", inversedBy="bill")
     * @ORM\JoinColumn(name="center_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $center;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="bill")
     */
    private $booking;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->booking = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->number;
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
     * Set number
     *
     * @param string $number
     *
     * @return Bill
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Bill
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set center
     *
     * @param \App\Entity\Center $center
     *
     * @return Bill
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
     * Add booking
     *
     * @param \App\Entity\Booking $booking
     *
     * @return Bill
     */
    public function addBooking(\App\Entity\Booking $booking)
    {
        $this->booking[] = $booking;

        return $this;
    }

    /**
     * Remove booking
     *
     * @param \App\Entity\Booking $booking
     */
    public function removeBooking(\App\Entity\Booking $booking)
    {
        $this->booking->removeElement($booking);
    }

    /**
     * Get booking
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooking()
    {
        return $this->booking;
    }
}
