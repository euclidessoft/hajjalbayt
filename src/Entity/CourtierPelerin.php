<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourtierPelerin
 *
 * @ORM\Table(name="courtierpelerin")
 * @ORM\Entity(repositoryClass="App\Repository\CourtierPelerinRepository")
 */
class CourtierPelerin
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\Id
	* @ORM\ManyToOne(targetEntity="App\Entity\Pelerin")
	*/
	private $pelerin;
	
	/**
	* @ORM\Id
	* @ORM\ManyToOne(targetEntity="App\Entity\Courtier")
	*/
	private $courtier;
	
	/**
	* @ORM\Id
	* @ORM\ManyToOne(targetEntity="App\Entity\Session")
	*/
	private $session;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="payer", type="boolean")
     */
    private $payer;


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return CourtierPelerin
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set payer
     *
     * @param boolean $payer
     *
     * @return CourtierPelerin
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return boolean
     */
    public function getPayer()
    {
        return $this->payer;
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
     * Set pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return CourtierPelerin
     */
    public function setPelerin(\App\Entity\Pelerin $pelerin)
    {
        $this->pelerin = $pelerin;

        return $this;
    }

    /**
     * Get pelerin
     *
     * @return \App\Entity\Pelerin
     */
    public function getPelerin()
    {
        return $this->pelerin;
    }

    /**
     * Set courtier
     *
     * @param \App\Entity\Courtier $courtier
     *
     * @return CourtierPelerin
     */
    public function setCourtier(\App\Entity\Courtier $courtier)
    {
        $this->courtier = $courtier;

        return $this;
    }

    /**
     * Get courtier
     *
     * @return \App\Entity\Courtier
     */
    public function getCourtier()
    {
        return $this->courtier;
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return CourtierPelerin
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
     * @return CourtierPelerin
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
