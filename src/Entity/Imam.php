<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Imam
 *
 * @ORM\Table(name="imam")
 * @ORM\Entity(repositoryClass="App\Repository\ImamRepository")
 */
class Imam
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
     * @var \DateTime
     *
     * @ORM\Column(name="datenaiss", type="date", nullable=true)
	 * @Assert\Date(message = "Format invalide")
     */
    private $datenaiss;

    /**
     * @var string
     *
     * @ORM\Column(name="lieunaiss", type="string", length=255, nullable=true)
	 * @Assert\Length(min = 3, minMessage="3 caractères au minimum", max = 30, maxMessage="30 caractères au maximum")
     */
    private $lieunaiss;

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
     * @ORM\Column(name="numpassport", type="string", length=255, nullable=true)
     */
    private $numpassport;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
	 * @Assert\NotBlank(message = "Numéro de téléphone obligatoire")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="famillyphone", type="string", length=255, nullable=true)
	 * @Assert\Regex(
	 * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[0768]\s?\d{3}(\s?\d{2}){2}))$/",
	 * match = true,
	 * message = "Numéro incorrect")
     */
    private $famillyphone;

    /**
     * @var string
     *
     * @ORM\Column(name="bloodgroup", type="string", length=255, nullable=true)
     */
    private $bloodgroup;

    /**
     * @var string
     *
     * @ORM\Column(name="numsaoudiantax", type="string", length=255, nullable=true)
     */
    private $numsaoudiantax;


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
     * Set datenaiss
     *
     * @param \DateTime $datenaiss
     *
     * @return Pelerin
     */
    public function setDatenaiss($datenaiss)
    {
        $this->datenaiss = $datenaiss;

        return $this;
    }

    /**
     * Get datenaiss
     *
     * @return \DateTime
     */
    public function getDatenaiss()
    {
        return $this->datenaiss;
    }

    /**
     * Set lieunaiss
     *
     * @param string $lieunaiss
     *
     * @return Pelerin
     */
    public function setLieunaiss($lieunaiss)
    {
        $this->lieunaiss = $lieunaiss;

        return $this;
    }

    /**
     * Get lieunaiss
     *
     * @return string
     */
    public function getLieunaiss()
    {
        return $this->lieunaiss;
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
     * Set numpassport
     *
     * @param string $numpassport
     *
     * @return Pelerin
     */
    public function setNumpassport($numpassport)
    {
        $this->numpassport = $numpassport;

        return $this;
    }

    /**
     * Get numpassport
     *
     * @return string
     */
    public function getNumpassport()
    {
        return $this->numpassport;
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
     * Set famillyphone
     *
     * @param string $famillyphone
     *
     * @return Pelerin
     */
    public function setFamillyphone($famillyphone)
    {
        $this->famillyphone = $famillyphone;

        return $this;
    }

    /**
     * Get famillyphone
     *
     * @return string
     */
    public function getFamillyphone()
    {
        return $this->famillyphone;
    }

    /**
     * Set bloodgroup
     *
     * @param string $bloodgroup
     *
     * @return Pelerin
     */
    public function setBloodgroup($bloodgroup)
    {
        $this->bloodgroup = $bloodgroup;

        return $this;
    }

    /**
     * Get bloodgroup
     *
     * @return string
     */
    public function getBloodgroup()
    {
        return $this->bloodgroup;
    }

    /**
     * Set numsaoudiantax
     *
     * @param string $numsaoudiantax
     *
     * @return Pelerin
     */
    public function setNumsaoudiantax($numsaoudiantax)
    {
        $this->numsaoudiantax = $numsaoudiantax;

        return $this;
    }

    /**
     * Get numsaoudiantax
     *
     * @return string
     */
    public function getNumsaoudiantax()
    {
        return $this->numsaoudiantax;
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
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Imam
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
