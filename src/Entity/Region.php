<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="region")
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotBlank(message="Please provide an center name")
     */
    private $name;

    // ..........

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Center", mappedBy="region")
     */
    private $center;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\City", mappedBy="region")
     */
    private $city;

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->center = new ArrayCollection();
        $this->city = new ArrayCollection();
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
     * @return Region
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
     * Add city
     *
     * @param \App\Entity\City $city
     *
     * @return Region
     */
    public function addCity(\App\Entity\City $city)
    {
        $this->city[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \App\Entity\City $city
     */
    public function removeCity(\App\Entity\City $city)
    {
        $this->city->removeElement($city);
    }

    /**
     * Get city
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add center
     *
     * @param \App\Entity\Center $center
     *
     * @return Region
     */
    public function addCenter(\App\Entity\Center $center)
    {
        $this->center[] = $center;

        return $this;
    }

    /**
     * Remove center
     *
     * @param \App\Entity\Center $center
     */
    public function removeCenter(\App\Entity\Center $center)
    {
        $this->center->removeElement($center);
    }

    /**
     * Get center
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCenter()
    {
        return $this->center;
    }
}
