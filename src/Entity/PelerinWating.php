<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pelerin
 *
 * @ORM\Table(name="pelerinwating")
 * @ORM\Entity(repositoryClass="App\Repository\PelerinWatingRepository")
 */
class PelerinWating
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	
	/**
	* @ORM\OneToOne(targetEntity="App\Entity\Image",cascade={"persist","refresh"})
	* @ORM\JoinColumn(nullable=true)
	*/
	private $image;
	
	/**
	* @ORM\OneToMany(targetEntity="App\Entity\PelerinCourtier", mappedBy="pelerin")
	*/
	private $courtiers;
	
	
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
     * @ORM\Column(name="prenom", type="string", length=255)
	 * @Assert\Length(min = 3, minMessage="3 caractères au minimum", max = 70, maxMessage="70 caractères au maximum")
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
	 * @Assert\Length(min = 2, minMessage="2 caractères au minimum", max = 25, maxMessage="25 caractères au maximum")
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
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
	 * @Assert\Length(min = 5, minMessage="5 caractères au minimum", max = 120, maxMessage="120 caractères au maximum")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255)
	 * @Assert\NotBlank(message = "Sélectionnez le genre")
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
	 * @Assert\NotBlank(message = "Numéro de téléphone obligatoire")
	 * @Assert\Regex(
	 * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[0768]\s?\d{3}(\s?\d{2}){2}))$/",
	 * match = true,
	 * message = "Numéro incorrect")
     */
    private $phone;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


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
     * @return Pelerin
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
     * @return Pelerin
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Pelerin
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Pelerin
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Pelerin
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
		$this->date = new \Datetime();
		$this->courtiers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Pelerin
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
     * Add courtier
     *
     * @param \App\Entity\PelerinCourtier $courtier
     *
     * @return Pelerin
     */
    public function addCourtier(\App\Entity\PelerinCourtier $courtier)
    {
        $this->courtiers[] = $courtier;

        return $this;
    }

    /**
     * Remove courtier
     *
     * @param \App\Entity\PelerinCourtier $courtier
     */
    public function removeCourtier(\App\Entity\PelerinCourtier $courtier)
    {
        $this->courtiers->removeElement($courtier);
    }

    /**
     * Get courtiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourtiers()
    {
        return $this->courtiers;
    }

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     *
     * @return Pelerin
     */
    public function setImage(\App\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \App\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
	

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Pelerin
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
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return PelerinWating
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
