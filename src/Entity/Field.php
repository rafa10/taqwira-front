<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="field")
 */
class Field
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Please provide an field name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     * @Assert\NotBlank(message="Please provide an field description")
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Please provide an field capacity")
     */
    private $capacity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Center", inversedBy="field")
     * @ORM\JoinColumn(name="center_id", referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $center;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Session", mappedBy="field")
     * @Assert\NotBlank(message="Please provide an field session")
     */
    private $session;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Planning", mappedBy="field")
     */
    private $planning;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="field")
     */
    private $booking;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Price", mappedBy="field")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Program", mappedBy="field")
     */
    private $program;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->session = new ArrayCollection();
        $this->planning = new ArrayCollection();
        $this->booking = new ArrayCollection();
        $this->price = new ArrayCollection();
        $this->program = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Field
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
     * Set description
     *
     * @param string $description
     *
     * @return Field
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
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return Field
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return integer
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set center
     *
     * @param \App\Entity\Center $center
     *
     * @return Field
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
     * Add planning
     *
     * @param \App\Entity\Planning $planning
     *
     * @return Field
     */
    public function addPlanning(\App\Entity\Planning $planning)
    {
        $this->planning[] = $planning;

        return $this;
    }

    /**
     * Remove planning
     *
     * @param \App\Entity\Planning $planning
     */
    public function removePlanning(\App\Entity\Planning $planning)
    {
        $this->planning->removeElement($planning);
    }

    /**
     * Get planning
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanning()
    {
        return $this->planning;
    }

    /**
     * Add booking
     *
     * @param \App\Entity\Booking $booking
     *
     * @return Field
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
     * Add session
     *
     * @param \App\Entity\Session $session
     *
     * @return Field
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
     * Add program
     *
     * @param \App\Entity\Program $program
     *
     * @return Field
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

    /**
     * Add price.
     *
     * @param \App\Entity\Price $price
     *
     * @return Field
     */
    public function addPrice(\App\Entity\Price $price)
    {
        $this->price[] = $price;

        return $this;
    }

    /**
     * Remove price.
     *
     * @param \App\Entity\Price $price
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePrice(\App\Entity\Price $price)
    {
        return $this->price->removeElement($price);
    }

    /**
     * Get price.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrice()
    {
        return $this->price;
    }
}
