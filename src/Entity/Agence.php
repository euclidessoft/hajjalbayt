<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Agence
 *
 * @ORM\Table(name="agence")
  * @UniqueEntity(
 * fields ={"email"},
 * message = "Adresse email déjà utilisée"
 *)
 * @ORM\Entity(repositoryClass="App\Repository\AgenceRepository")
 */
class Agence
{
    /**
    * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="agence")
    */
    private $users;
	/**
	* @ORM\OneToOne(targetEntity="App\Entity\Image",cascade={"persist","refresh"})
	* @ORM\JoinColumn(nullable=true)
	*/
	private $image;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=true)
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
     * @ORM\Column(name="Nom", type="string", length=255)
	 * @Assert\Length(min = 3, minMessage="3 caractères au minimum", max = 70, maxMessage="70 caractères au maximum")
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
     * @ORM\Column(name="Adresse", type="string", length=255, nullable=true)
	 * @Assert\Length(min = 5, minMessage="5 caractères au minimum", max = 120, maxMessage="120 caractères au maximum")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255, nullable=true)
     * @Assert\Length(min = 5, minMessage="5 caractères au minimum", max = 120, maxMessage="120 caractères au maximum")
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
	 * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email1", type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $email1;
    
    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=255)
	 * @Assert\NotBlank(message = "Numéro de téléphone obligatoire")
	 * @Assert\Regex(
	 * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[056789]\s?\d{3}(\s?\d{2}){2}))$/",
	 * match = true,
	 * message = "Numéro incorrect")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone1", type="string", length=255, nullable=true)
     * @Assert\Regex(
     * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[056789]\s?\d{3}(\s?\d{2}){2}))$/",
     * match = true,
     * message = "Numéro incorrect")
     */
    private $phone1;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone2", type="string", length=255, nullable=true)
     * @Assert\Regex(
     * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[056789]\s?\d{3}(\s?\d{2}){2}))$/",
     * match = true,
     * message = "Numéro incorrect")
     */
    private $phone2;

    /**
     * @var bool
     *
     * @ORM\Column(name="caisse", type="boolean")
     */
    private $caisse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $retenu;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $licence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Agence
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
     * @return Agence
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
     * Set email
     *
     * @param string $email
     *
     * @return Agence
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Agence
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
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Agence
     */
    public function setAgence(\App\Entity\Agence $agence = null)
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

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     *
     * @return Agence
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
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->caisse = false;
        $this->licence = false;
        $this->retenu = false;
    }

    /**
     * Add user
     *
     * @param \App\Entity\User $user
     *
     * @return Agence
     */
    public function addUser(\App\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \App\Entity\User $user
     */
    public function removeUser(\App\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set caisse
     *
     * @param boolean $caisse
     *
     * @return Agence
     */
    public function setCaisse($caisse)
    {
        $this->caisse = $caisse;

        return $this;
    }

    /**
     * Get caisse
     *
     * @return boolean
     */
    public function getCaisse()
    {
        return $this->caisse;
    }

    /**
     * Set email1
     *
     * @param string $email1
     *
     * @return Agence
     */
    public function setEmail1($email1)
    {
        $this->email1 = $email1;

        return $this;
    }

    /**
     * Get email1
     *
     * @return string
     */
    public function getEmail1()
    {
        return $this->email1;
    }

    /**
     * Set phone1
     *
     * @param string $phone1
     *
     * @return Agence
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * Get phone1
     *
     * @return string
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return Agence
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return Agence
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Agence
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    public function getRetenu(): ?bool
    {
        return $this->retenu;
    }

    public function setRetenu(bool $retenu): self
    {
        $this->retenu = $retenu;

        return $this;
    }

    public function getLicence(): ?bool
    {
        return $this->licence;
    }

    public function setLicence(?bool $licence): self
    {
        $this->licence = $licence;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(?string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }
}
