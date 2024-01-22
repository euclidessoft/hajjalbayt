<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PelerinCourtier
 *
 * @ORM\Table(name="pelerin_courtier")
 * @ORM\Entity(repositoryClass="App\Repository\PelerinCourtierRepository")
 */
class PelerinCourtier
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
     * @ORM\Column(name="datesave", type="datetime")
     */
    private $datesave;
	
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
     * @return PelerinCourtier
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
     * Set pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return PelerinCourtier
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
     * @return PelerinCourtier
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
     * @return PelerinCourtier
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
     * Set payer
     *
     * @param boolean $payer
     *
     * @return PelerinCourtier
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
     * Constructor
     */
    public function __construct()
    {
		$this->payer = false;
		$this->datesave = new \Datetime();
    }

    /**
     * Set datesave
     *
     * @param \DateTime $datesave
     *
     * @return PelerinCourtier
     */
    public function setDatesave($datesave)
    {
        $this->datesave = $datesave;

        return $this;
    }

    /**
     * Get datesave
     *
     * @return \DateTime
     */
    public function getDatesave()
    {
        return $this->datesave;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return PelerinCourtier
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
