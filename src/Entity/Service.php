<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="service")
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $icon;

    // ..........

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Center", inversedBy="service")
     * @ORM\JoinTable(
     *     name="center_service",
     *     joinColumns={@ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="center_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")}
     * )
     */
    private $center;

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
        $this->center = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Service
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
     * Add center
     *
     * @param \App\Entity\Center $center
     *
     * @return Service
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

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Service
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }
}
