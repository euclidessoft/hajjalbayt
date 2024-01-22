<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visa
 *
 * @ORM\Table(name="visa")
 * @ORM\Entity(repositoryClass="App\Repository\VisaRepository")
 */
class Visa
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Session")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $session;
	
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedepot", type="datetime")
     */
    private $datedepot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datearrivee", type="datetime", nullable=true)
     */
    private $datearrivee;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datedepot
     *
     * @param \DateTime $datedepot
     *
     * @return Visa
     */
    public function setDatedepot($datedepot)
    {
        $this->datedepot = $datedepot;

        return $this;
    }

    /**
     * Get datedepot
     *
     * @return \DateTime
     */
    public function getDatedepot()
    {
        return $this->datedepot;
    }

    /**
     * Set datearrivee
     *
     * @param \DateTime $datearrivee
     *
     * @return Visa
     */
    public function setDatearrivee($datearrivee)
    {
        $this->datearrivee = $datearrivee;

        return $this;
    }

    /**
     * Get datearrivee
     *
     * @return \DateTime
     */
    public function getDatearrivee()
    {
        return $this->datearrivee;
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Visa
     */
    public function setSession(\App\Entity\Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \App\Entity\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Visa
     */
    public function setAgence(\App\Entity\Agence $agence)
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * Get agence
     *
     * @return \App\Entity\Agence
     */
    public function getAgence()
    {
        return $this->agence;
    }
}
