<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Versement
 *
 * @ORM\Table(name="versement")
 * @ORM\Entity(repositoryClass="App\Repository\VersementRepository")
 */
class Versement
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\User")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $encaisser;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\User")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $facturer;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Pelerin")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $pelerin;
	
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;
	
	/**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
	 * @Assert\NotBlank(message = "Sélectionnez le type de versement")
     */
    private $type;
	
	/**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable = true)
	  * @Assert\Length(min = 5, minMessage="5 chiffres au minimum",max = 9, maxMessage="9 chiffres au maximum")
	 * @Assert\Regex(
	 * pattern= "/^\d+$/",
	 * match = true,
	 * message = "Des chiffres uniquement")
     */
    private $numero;
	
	/**
     * @var string
     *
     * @ORM\Column(name="banque", type="string", length=255, nullable = true)
     */
    private $banque;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Versement
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
     * Set montant
     *
     * @param integer $montant
     *
     * @return Versement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return Versement
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
     * Constructor
     */
    public function __construct()
    {
       
		$this->date = new \datetime();
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Versement
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
     * Set encaisser
     *
     * @param \App\Entity\User $encaisser
     *
     * @return Versement
     */
    public function setEncaisser(\App\Entity\User $encaisser = null)
    {
        $this->encaisser = $encaisser;

        return $this;
    }

    /**
     * Get encaisser
     *
     * @return \App\Entity\User
     */
    public function getEncaisser()
    {
        return $this->encaisser;
    }

    /**
     * Set facturer
     *
     * @param \App\Entity\User $facturer
     *
     * @return Versement
     */
    public function setFacturer(\App\Entity\User $facturer)
    {
        $this->facturer = $facturer;

        return $this;
    }

    /**
     * Get facturer
     *
     * @return \App\Entity\User
     */
    public function getFacturer()
    {
        return $this->facturer;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Versement
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Versement
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set banque
     *
     * @param string $banque
     *
     * @return Versement
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Versement
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
