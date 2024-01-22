<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vol
 *
 * @ORM\Table(name="vol")
 * @ORM\Entity(repositoryClass="App\Repository\VolRepository")
 */
class Vol
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\OneToMany(targetEntity="App\Entity\Pelerin", mappedBy="retour")
	*/
	private $pelerinretours;
	
	/**
	* @ORM\OneToMany(targetEntity="App\Entity\Pelerin", mappedBy="vol")
	*/
	private $pelerins;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Session")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $session;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Compagnie")
	* @ORM\JoinColumn(nullable=false)
	* @Assert\NotBlank(message = "Selectionnez Compagnie")
	* @Assert\Valid()
	*/
	private $compagnie;
	
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
     * @ORM\Column(name="Numero", type="string", length=255, nullable=true)
     */
    private $numero;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="datedepart", type="date", nullable=true)
     * @Assert\NotBlank(message = "Renseignez la date de repart")
	 * @Assert\Date(message = "Format invalide")
     */
    private $datedepart;
	
	
    /**
     * @ORM\Column(type="time")
     * @Assert\NotBlank(message = "Renseignez l'heure de départ")
     * @Assert\Time(message = "Format invalide")
     */
    private $heuredepart;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=255, nullable=true)
     */
    private $destination;
	
	/**
     * @var string
     *
     * @ORM\Column(name="lieudepart", type="string", length=255, nullable=true)
     */
    private $lieudepart;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="aller", type="boolean")
	 */
	private $aller;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="retour", type="boolean")
	 */
	private $retour;

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
     * Set numero
     *
     * @param string $numero
     *
     * @return Vol
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set datedepart
     *
     * @param \DateTime $datedepart
     *
     * @return Vol
     */
    public function setDatedepart($datedepart)
    {
        $this->datedepart = $datedepart;

        return $this;
    }

    /**
     * Get datedepart
     *
     * @return \DateTime
     */
    public function getDatedepart()
    {
        return $this->datedepart;
    }

    
    public function setHeuredepart(\DateTimeInterface $heuredepart)
    {
        $this->heuredepart = $heuredepart;

        return $this;
    }

    
    public function getHeuredepart()
    {
        return $this->heuredepart;
    }

    /**
     * Set destination
     *
     * @param string $destination
     *
     * @return Vol
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Vol
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
     * Set compagnie
     *
     * @param \App\Entity\Compagnie $compagnie
     *
     * @return Vol
     */
    public function setCompagnie(\App\Entity\Compagnie $compagnie)
    {
        $this->compagnie = $compagnie;

        return $this;
    }

    /**
     * Get compagnie
     *
     * @return \App\Entity\Compagnie
     */
    public function getCompagnie()
    {
        return $this->compagnie;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pelerins = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pelerinretours = new \Doctrine\Common\Collections\ArrayCollection();
		$this->aller = false;
		$this->retour = false;
    }

    /**
     * Add pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return Vol
     */
    public function addPelerin(\App\Entity\Pelerin $pelerin)
    {
        $this->pelerins[] = $pelerin;

        return $this;
    }

    /**
     * Remove pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     */
    public function removePelerin(\App\Entity\Pelerin $pelerin)
    {
        $this->pelerins->removeElement($pelerin);
    }

    /**
     * Get pelerins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPelerins()
    {
        return $this->pelerins;
    }

    /**
     * Set aller
     *
     * @param boolean $aller
     *
     * @return Vol
     */
    public function setAller($aller)
    {
        $this->aller = $aller;

        return $this;
    }

    /**
     * Get aller
     *
     * @return boolean
     */
    public function getAller()
    {
        return $this->aller;
    }

    /**
     * Set retour
     *
     * @param boolean $retour
     *
     * @return Vol
     */
    public function setRetour($retour)
    {
        $this->retour = $retour;

        return $this;
    }

    /**
     * Get retour
     *
     * @return boolean
     */
    public function getRetour()
    {
        return $this->retour;
    }

    /**
     * Set lieudepart
     *
     * @param string $lieudepart
     *
     * @return Vol
     */
    public function setLieudepart($lieudepart)
    {
        $this->lieudepart = $lieudepart;

        return $this;
    }

    /**
     * Get lieudepart
     *
     * @return string
     */
    public function getLieudepart()
    {
        return $this->lieudepart;
    }

    /**
     * Add pelerinretour
     *
     * @param \App\Entity\Pelerin $pelerinretour
     *
     * @return Vol
     */
    public function addPelerinretour(\App\Entity\Pelerin $pelerinretour)
    {
        $this->pelerinretours[] = $pelerinretour;

        return $this;
    }

    /**
     * Remove pelerinretour
     *
     * @param \App\Entity\Pelerin $pelerinretour
     */
    public function removePelerinretour(\App\Entity\Pelerin $pelerinretour)
    {
        $this->pelerinretours->removeElement($pelerinretour);
    }

    /**
     * Get pelerinretours
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPelerinretours()
    {
        return $this->pelerinretours;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Vol
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
