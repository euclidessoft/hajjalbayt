<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Courtier
 *
 * @ORM\Table(name="courtier")
 * @ORM\Entity(repositoryClass="App\Repository\CourtierRepository")
 */
class Courtier
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\OneToMany(targetEntity="App\Entity\PelerinCourtier", mappedBy="courtier")
	*/
	private $pelerins;
	
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
     * @ORM\Column(name="prenom", type="string", length=255)
	 * @Assert\Length(min = 3, minMessage="3 caractères au minimum")
	 * @Assert\NotBlank(message = "Renseignez le prénom")
	 * @Assert\Regex(
	 * pattern= "/^[a-zA-Z ]+$/",
	 * match = true,
	 * message = "Vérifiez les caractères saisis")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
	 * @Assert\Length(min = 2, minMessage="3 caractères au minimum", max = 100, maxMessage="100 caractères au maximum")
	 * @Assert\NotBlank(message = "Renseignez le nom")
	 * @Assert\Regex(
	 * pattern= "/^[a-zA-Z ]+$/",
	 * match = true,
	 * message = "Vérifiez les caractères saisis")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, unique=true)
	 * @Assert\NotBlank(message = "Renseignez le numéro de téléphone")
	 * @Assert\Regex(
	 * pattern= "/^7[0768]\s?\d{3}(\s?\d{2}){2}$/",
	 * match = true,
	 * message = "Numéro incorrect")
     */
    private $phone;
	
	/**
     * @var int
     *
     * @ORM\Column(name="commition", type="integer")
	  * @Assert\Length(min = 5, minMessage="5 chiffres au minimum",max = 6, maxMessage="6 chiffres au maximum")
	  * @Assert\NotBlank(message = "Renseignez le montant de la commission")
	 * @Assert\Regex(
	 * pattern= "/^\d+$/",
	 * match = true,
	 * message = "Attention, ce champ doit contenir uniquement des chiffres")
     */
    private $commition;


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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Courtier
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Courtier
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Courtier
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pelerins = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return Courtier
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
     * Set commition
     *
     * @param integer $commition
     *
     * @return Courtier
     */
    public function setCommition($commition)
    {
        $this->commition = $commition;

        return $this;
    }

    /**
     * Get commition
     *
     * @return integer
     */
    public function getCommition()
    {
        return $this->commition;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Courtier
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
