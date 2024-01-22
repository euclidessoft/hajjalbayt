<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OldPelerin
 *
 * @ORM\Table(name="old_pelerin")
 * @ORM\Entity(repositoryClass="App\Repository\OldPelerinRepository")
 */
class OldPelerin
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $agence;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\VersementBanque")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $versementbanque;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Visa")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $visa;
	
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
	* @ORM\OneToMany(targetEntity="App\Entity\Versement", mappedBy="pelerin")
	*/
	private $versements;
	
	/**
	* @ORM\OneToOne(targetEntity="App\Entity\Pelerin")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $parrain;
	
	/**
	* @ORM\OneToOne(targetEntity="App\Entity\Reduction", cascade={"persist"})
	* @ORM\JoinColumn(nullable=true)
	* @Assert\Valid()
	*/
	private $reduction;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Pack")
	* @ORM\JoinColumn(nullable=false)
	* @Assert\NotBlank(message = "Sélectionnez un package")
	* @Assert\Valid()
	*/
	private $pack;
	
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
     * @ORM\Column(name="remark", type="text", nullable=true)
     */
    private $remark;

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
     * @var int
     *
     * @ORM\Column(name="somme", type="integer")
     */
    private $somme;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="diabete", type="boolean")
	 */
	private $diabete;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="handicap", type="boolean")
	 */
	private $handicap;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="nonvoyant", type="boolean")
	 */
	private $nonvoyant;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="hypo", type="boolean")
	 */
	private $hypo;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="hyper", type="boolean")
	 */
	private $hyper;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="exonorer", type="boolean")
	 */
	private $exonorer;
	
	 /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=255, nullable=true)
     */
    private $cin;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="visite", type="boolean")
	 */
	private $visite;
	
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="photo", type="boolean")
	 */
	private $photo;
	
	/**
     * @var int
     *
     * @ORM\Column(name="numdossier", type="integer", nullable=true)
     */
    private $numdossier;
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->compte = 0;
		$this->date = new \Datetime();
		$this->courtiers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->versements = new \Doctrine\Common\Collections\ArrayCollection();
		$this->diabete = false;
		$this->handicap = false;
		$this->nonvoyant = false;
		$this->hypo = false;
		$this->hyper = false;
		$this->exonorer = false;
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return OldPelerin
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
     * @return OldPelerin
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
     * @return OldPelerin
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
     * @return OldPelerin
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
     * @return OldPelerin
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
     * @return OldPelerin
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
     * @return OldPelerin
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
     * Set remark
     *
     * @param string $remark
     *
     * @return OldPelerin
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return OldPelerin
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
     * @return OldPelerin
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
     * @return OldPelerin
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
     * @return OldPelerin
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
     * Set somme
     *
     * @param integer $somme
     *
     * @return OldPelerin
     */
    public function setSomme($somme)
    {
        $this->somme = $somme;

        return $this;
    }

    /**
     * Get somme
     *
     * @return integer
     */
    public function getSomme()
    {
        return $this->somme;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return OldPelerin
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
     * Set diabete
     *
     * @param boolean $diabete
     *
     * @return OldPelerin
     */
    public function setDiabete($diabete)
    {
        $this->diabete = $diabete;

        return $this;
    }

    /**
     * Get diabete
     *
     * @return boolean
     */
    public function getDiabete()
    {
        return $this->diabete;
    }

    /**
     * Set handicap
     *
     * @param boolean $handicap
     *
     * @return OldPelerin
     */
    public function setHandicap($handicap)
    {
        $this->handicap = $handicap;

        return $this;
    }

    /**
     * Get handicap
     *
     * @return boolean
     */
    public function getHandicap()
    {
        return $this->handicap;
    }

    /**
     * Set nonvoyant
     *
     * @param boolean $nonvoyant
     *
     * @return OldPelerin
     */
    public function setNonvoyant($nonvoyant)
    {
        $this->nonvoyant = $nonvoyant;

        return $this;
    }

    /**
     * Get nonvoyant
     *
     * @return boolean
     */
    public function getNonvoyant()
    {
        return $this->nonvoyant;
    }

    /**
     * Set hypo
     *
     * @param boolean $hypo
     *
     * @return OldPelerin
     */
    public function setHypo($hypo)
    {
        $this->hypo = $hypo;

        return $this;
    }

    /**
     * Get hypo
     *
     * @return boolean
     */
    public function getHypo()
    {
        return $this->hypo;
    }

    /**
     * Set hyper
     *
     * @param boolean $hyper
     *
     * @return OldPelerin
     */
    public function setHyper($hyper)
    {
        $this->hyper = $hyper;

        return $this;
    }

    /**
     * Get hyper
     *
     * @return boolean
     */
    public function getHyper()
    {
        return $this->hyper;
    }

    /**
     * Set exonorer
     *
     * @param boolean $exonorer
     *
     * @return OldPelerin
     */
    public function setExonorer($exonorer)
    {
        $this->exonorer = $exonorer;

        return $this;
    }

    /**
     * Get exonorer
     *
     * @return boolean
     */
    public function getExonorer()
    {
        return $this->exonorer;
    }

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return OldPelerin
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set visite
     *
     * @param boolean $visite
     *
     * @return OldPelerin
     */
    public function setVisite($visite)
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * Get visite
     *
     * @return boolean
     */
    public function getVisite()
    {
        return $this->visite;
    }

    /**
     * Set photo
     *
     * @param boolean $photo
     *
     * @return OldPelerin
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return boolean
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set numdossier
     *
     * @param integer $numdossier
     *
     * @return OldPelerin
     */
    public function setNumdossier($numdossier)
    {
        $this->numdossier = $numdossier;

        return $this;
    }

    /**
     * Get numdossier
     *
     * @return integer
     */
    public function getNumdossier()
    {
        return str_pad($this->numdossier,4,'0', STR_PAD_LEFT);
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return OldPelerin
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
     * Set versementbanque
     *
     * @param \App\Entity\VersementBanque $versementbanque
     *
     * @return OldPelerin
     */
    public function setVersementbanque(\App\Entity\VersementBanque $versementbanque = null)
    {
        $this->versementbanque = $versementbanque;

        return $this;
    }

    /**
     * Get versementbanque
     *
     * @return \App\Entity\VersementBanque
     */
    public function getVersementbanque()
    {
        return $this->versementbanque;
    }

    /**
     * Set visa
     *
     * @param \App\Entity\Visa $visa
     *
     * @return OldPelerin
     */
    public function setVisa(\App\Entity\Visa $visa = null)
    {
        $this->visa = $visa;

        return $this;
    }

    /**
     * Get visa
     *
     * @return \App\Entity\Visa
     */
    public function getVisa()
    {
        return $this->visa;
    }

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     *
     * @return OldPelerin
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
     * Add versement
     *
     * @param \App\Entity\Versement $versement
     *
     * @return OldPelerin
     */
    public function addVersement(\App\Entity\Versement $versement)
    {
        $this->versements[] = $versement;

        return $this;
    }

    /**
     * Remove versement
     *
     * @param \App\Entity\Versement $versement
     */
    public function removeVersement(\App\Entity\Versement $versement)
    {
        $this->versements->removeElement($versement);
    }

    /**
     * Get versements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVersements()
    {
        return $this->versements;
    }

    /**
     * Set parrain
     *
     * @param \App\Entity\Pelerin $parrain
     *
     * @return OldPelerin
     */
    public function setParrain(\App\Entity\Pelerin $parrain = null)
    {
        $this->parrain = $parrain;

        return $this;
    }

    /**
     * Get parrain
     *
     * @return \App\Entity\Pelerin
     */
    public function getParrain()
    {
        return $this->parrain;
    }

    /**
     * Set reduction
     *
     * @param \App\Entity\Reduction $reduction
     *
     * @return OldPelerin
     */
    public function setReduction(\App\Entity\Reduction $reduction = null)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return \App\Entity\Reduction
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set pack
     *
     * @param \App\Entity\Pack $pack
     *
     * @return OldPelerin
     */
    public function setPack(\App\Entity\Pack $pack)
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * Get pack
     *
     * @return \App\Entity\Pack
     */
    public function getPack()
    {
        return $this->pack;
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return OldPelerin
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
}
