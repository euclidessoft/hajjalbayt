<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Debit
 *
 * @ORM\Table(name="debit")
 * @ORM\Entity(repositoryClass="App\Repository\DebitRepository")
 */
class Debit
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Depense")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $depense;
	
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
     * Set depense
     *
     * @param \App\Entity\Depense $depense
     *
     * @return Debit
     */
    public function setDepense(\App\Entity\Depense $depense)
    {
        $this->depense = $depense;
        $this->montant = $depense->getMontant();

        return $this;
    }

    /**
     * Get depense
     *
     * @return \App\Entity\Depense
     */
    public function getDepense()
    {
        return $this->depense;
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Debit
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
     * @return Debit
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
     * Set montant
     *
     * @param integer $montant
     *
     * @return Debit
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
     * @return Debit
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
