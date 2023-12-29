<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Session
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=255)
	  * @Assert\Length(min = 3, minMessage="Longueur comprise entre 3 et 30 caractères",max = 30, maxMessage="Longueur comprise entre 3 et 30 caractères")
	 * @Assert\NotBlank(message = "Renseignez le nom de la session")
	 * @Assert\Regex(
	 * pattern= "/^[a-zA-Z0-9 ]+$/",
	 * match = true,
	 * message = "Vérifiez les caractères saisis")
     */
    private $designation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date")
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefin", type="date", nullable=true)
     */
    private $datefin;
	
		/**
		 * @var bool
		 *
		 * @ORM\Column(name="current", type="boolean")
		 */
		private $current;
	
	/**
     * @var int
     *
     * @ORM\Column(name="cotat", type="integer", nullable=true)
	  * @Assert\Length(max = 3, maxMessage="3 chiffres au maximum")
	  * @Assert\NotBlank(message = "Renseignez le quota")
	 * @Assert\Regex(
	 * pattern= "/^\d+$/",
	 * match = true,
	 * message = "Des chiffres uniquement")
     */
    private $cotat;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="groupement", type="boolean")
	 */
	private $groupement;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="chef", type="boolean")
	 */
	private $chef;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="configurer", type="boolean")
	 */
	private $configurer;



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
     * Set designation
     *
     * @param string $designation
     *
     * @return Session
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set datedebut
     *
     * @param \DateTime $datedebut
     *
     * @return Session
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    /**
     * Get datedebut
     *
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * Set datefin
     *
     * @param \DateTime $datefin
     *
     * @return Session
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * Get datefin
     *
     * @return \DateTime
     */
    public function getDatefin()
    {
        return $this->datefin;
    }
	
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->datedebut = new \Datetime();
		$this->current = true;
		$this->cotat = 0;
		$this->groupement = false;
		$this->chef = false;
		$this->configurer = false;
    }


    /**
     * Set current
     *
     * @param boolean $current
     *
     * @return Session
     */
    public function setCurrent($current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Get current
     *
     * @return boolean
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Set cotat
     *
     * @param integer $cotat
     *
     * @return Session
     */
    public function setCotat($cotat)
    {
        $this->cotat = $cotat;

        return $this;
    }

    /**
     * Get cotat
     *
     * @return integer
     */
    public function getCotat()
    {
        return $this->cotat;
    }

    /**
     * Set groupement
     *
     * @param boolean $groupement
     *
     * @return Session
     */
    public function setGroupement($groupement)
    {
        $this->groupement = $groupement;

        return $this;
    }

    /**
     * Get groupement
     *
     * @return boolean
     */
    public function getGroupement()
    {
        return $this->groupement;
    }

    /**
     * Set chef
     *
     * @param boolean $chef
     *
     * @return Session
     */
    public function setChef($chef)
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * Get chef
     *
     * @return boolean
     */
    public function getChef()
    {
        return $this->chef;
    }

    /**
     * Set configurer
     *
     * @param boolean $configurer
     *
     * @return Session
     */
    public function setConfigurer($configurer)
    {
        $this->configurer = $configurer;

        return $this;
    }

    /**
     * Get configurer
     *
     * @return boolean
     */
    public function getConfigurer()
    {
        return $this->configurer;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Session
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
