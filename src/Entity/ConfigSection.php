<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="config_section")
 */
class ConfigSection
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Config", mappedBy="configSection")
     * @Assert\NotBlank(message="Please provide a config")
     */
    private $config;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Center", inversedBy="configSection")
     * @ORM\JoinColumn(name="center_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank(message="Please provide a center")
     */
    private $center;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="configSection")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add config
     *
     * @param \App\Entity\Config $config
     *
     * @return ConfigSection
     */
    public function addConfig(\App\Entity\Config $config)
    {
        $this->config[] = $config;

        return $this;
    }

    /**
     * Remove config
     *
     * @param \App\Entity\Config $config
     */
    public function removeConfig(\App\Entity\Config $config)
    {
        $this->config->removeElement($config);
    }

    /**
     * Get config
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set section
     *
     * @param \App\Entity\Section $section
     *
     * @return ConfigSection
     */
    public function setSection(\App\Entity\Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \App\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set center
     *
     * @param \App\Entity\Center $center
     *
     * @return ConfigSection
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
}
