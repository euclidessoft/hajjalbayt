<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bus
 *
 * @ORM\Table(name="bus")
 * @ORM\Entity(repositoryClass="App\Repository\BusRepository")
 */
class Bus
{
    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Compagnie")
    * @ORM\JoinColumn(nullable=false)
    * @Assert\NotBlank(message = "Sélectionnez une compagnie")
    * @Assert\Valid()
    */
    private $compagnie;
    
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\OneToMany(targetEntity="App\Entity\Pelerin", mappedBy="bus")
	*/
	private $pelerins;
	
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
     * @var string
     *
     * @ORM\Column(name="Numero", type="string", length=255)
     * @Assert\NotBlank(message = "Renseignez le numéro du bus")
     */
    private $numero;
	
	/**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Renseignez le matricule du bus")
     */
    private $matricule;
	
	/**
     * @var int
     *
     * @ORM\Column(name="nbrplace", type="integer")
     * @Assert\NotBlank(message = "Renseignez le nombre de places")
     */
    private $nbrplace;
	
	/**
     * @var string
     *
     * @ORM\Column(name="genre", type="string", length=255)
	 * @Assert\NotBlank(message = "Sélectionnez le genre")
     */
    private $genre;

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
     * @return Bus
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
     * Set matricule
     *
     * @param string $matricule
     *
     * @return Bus
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Add pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return Bus
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
     * @return Bus
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
     * Set nbrplace
     *
     * @param integer $nbrplace
     *
     * @return Bus
     */
    public function setNbrplace($nbrplace)
    {
        $this->nbrplace = $nbrplace;

        return $this;
    }

    /**
     * Get nbrplace
     *
     * @return integer
     */
    public function getNbrplace()
    {
        return $this->nbrplace;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Bus
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Bus
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

    public function getCompagnie(): ?Compagnie
    {
        return $this->compagnie;
    }

    public function setCompagnie(?Compagnie $compagnie): self
    {
        $this->compagnie = $compagnie;

        return $this;
    }
}
