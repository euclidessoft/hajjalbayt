<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ChambreMecque
 *
 * @ORM\Table(name="chambremecque")
 * @ORM\Entity(repositoryClass="App\Repository\ChambreMecqueRepository")
 */
class ChambreMecque
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\OneToMany(targetEntity="App\Entity\Pelerin", mappedBy="chambremecque")
	*/
	private $pelerins;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Session")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $session;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Hotel")
	* @ORM\JoinColumn(nullable=false)
	* @Assert\NotBlank(message = "Sélectionnez un hôtel")
	* @Assert\Valid()
	*/
	private $hotel;
	
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
     * @var string
     *
     * @ORM\Column(name="Numeroreel", type="string", length=255, nullable=true)
     */
    private $numeroreel;
	
	 /**
     * @var string
     * @Assert\NotBlank(message = "Sélectionnez le type de chambre")
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;
	
	/**
     * @var int
     * @Assert\NotBlank(message = "Sélectionnez le nombre de lits")
     * @ORM\Column(name="place", type="integer", nullable=false)
     */
    private $place;
	
	/**
     * @var int
     *
     * @ORM\Column(name="nombre", type="integer", nullable=true)
     */
    private $nombre;
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pelerins = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set numero
     *
     * @param string $numero
     *
     * @return ChambreMecque
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
     * Set numeroreel
     *
     * @param string $numeroreel
     *
     * @return ChambreMecque
     */
    public function setNumeroreel($numeroreel)
    {
        $this->numeroreel = $numeroreel;

        return $this;
    }

    /**
     * Get numeroreel
     *
     * @return string
     */
    public function getNumeroreel()
    {
        return $this->numeroreel;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ChambreMecque
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
     * Set place
     *
     * @param integer $place
     *
     * @return ChambreMecque
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return integer
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set nombre
     *
     * @param integer $nombre
     *
     * @return ChambreMecque
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return integer
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return ChambreMecque
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
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return ChambreMecque
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
     * Set hotel
     *
     * @param \App\Entity\Hotel $hotel
     *
     * @return ChambreMecque
     */
    public function setHotel(\App\Entity\Hotel $hotel)
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Get hotel
     *
     * @return \App\Entity\Hotel
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return ChambreMecque
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
