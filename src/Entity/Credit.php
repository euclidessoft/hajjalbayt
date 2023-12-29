<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Credit
 *
 * @ORM\Table(name="credit")
 * @ORM\Entity(repositoryClass="App\Repository\CreditRepository")
 */
class Credit
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Versement")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $versement;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\VersementAgence")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $versementagence;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Gain")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $gain;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Session")
	* @ORM\JoinColumn(nullable=true)
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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    private $repere;

    private $repere1;
	
	/**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;


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
     * Set versement
     *
     * @param \App\Entity\Versement $versement
     *
     * @return Credit
     */
    public function setVersement(\App\Entity\Versement $versement = null)
    {
        $this->versement = $versement;
        $this->montant = $versement->getMontant();

        return $this;
    }

    /**
     * Get versement
     *
     * @return \App\Entity\Versement
     */
    public function getVersement()
    {
        return $this->versement;
    }

    /**
     * Set gain
     *
     * @param \App\Entity\Gain $gain
     *
     * @return Credit
     */
    public function setGain(\App\Entity\Gain $gain = null)
    {
        $this->gain = $gain;
        $this->montant = $gain->getMontant();

        return $this;
    }

    /**
     * Get gain
     *
     * @return \App\Entity\Gain
     */
    public function getGain()
    {
        return $this->gain;
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Credit
     */
    public function setSession(\App\Entity\Session $session = null)
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Credit
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
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \Datetime();
    }

    /**
     * Set versementagence
     *
     * @param \App\Entity\VersementAgence $versementagence
     *
     * @return Credit
     */
    public function setVersementagence(\App\Entity\VersementAgence $versementagence = null)
    {
        $this->versementagence = $versementagence;
        $this->montant = $versementagence->getMontant();

        return $this;
    }

    /**
     * Get versementagence
     *
     * @return \App\Entity\VersementAgence
     */
    public function getVersementagence()
    {
        return $this->versementagence;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return Credit
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Credit
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
    
    public function setRepere($repere)
    {
        $this->repere = $repere;

        return $this;
    }

    public function getRepere()
    {
        return $this->repere;
    }

    public function setRepere1($repere1)
    {
        $this->repere1 = $repere1;

        return $this;
    }

    public function getRepere1()
    {
        return $this->repere1;
    }
}
