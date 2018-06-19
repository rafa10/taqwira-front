<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="booking_type")
 */
class BookingType
{
    const MATCH = 1;
    const ABONNEMENT = 2;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank(message="Please provide an booking type description")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="bookingType")
     */
    private $booking;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Price", mappedBy="bookingType")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Program", mappedBy="bookingType")
     */
    private $program;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->booking = new ArrayCollection();
        $this->price =new ArrayCollection();
        $this->program =new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->description;
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
     * Set description
     *
     * @param string $description
     *
     * @return BookingType
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
     * Add booking
     *
     * @param \App\Entity\Booking $booking
     *
     * @return BookingType
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

    /**
     * Add price
     *
     * @param \App\Entity\Price $price
     *
     * @return BookingType
     */
    public function addPrice(\App\Entity\Price $price)
    {
        $this->price[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \App\Entity\Price $price
     */
    public function removePrice(\App\Entity\Price $price)
    {
        $this->price->removeElement($price);
    }

    /**
     * Get price
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrice()
    {
        return $this->price;
    }
    

    /**
     * Add program
     *
     * @param \App\Entity\Program $program
     *
     * @return BookingType
     */
    public function addProgram(\App\Entity\Program $program)
    {
        $this->program[] = $program;

        return $this;
    }

    /**
     * Remove program
     *
     * @param \App\Entity\Program $program
     */
    public function removeProgram(\App\Entity\Program $program)
    {
        $this->program->removeElement($program);
    }

    /**
     * Get program
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgram()
    {
        return $this->program;
    }
}
