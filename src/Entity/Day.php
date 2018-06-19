<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="day")
 */
class Day
{
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Price", inversedBy="day")
     * @ORM\JoinTable(
     *     name="price_day",
     *     joinColumns={@ORM\JoinColumn(name="day_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="price_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")}
     * )
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Program", inversedBy="day")
     * @ORM\JoinTable(
     *     name="program_day",
     *     joinColumns={@ORM\JoinColumn(name="day_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="program_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")}
     * )
     */
    private $program;



    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->price =new ArrayCollection();
        $this->program =new ArrayCollection();
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
     * @return Day
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
     * Add price
     *
     * @param \App\Entity\Price $price
     *
     * @return Day
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
     * @return Day
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
