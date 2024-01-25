<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pelerin
 *
 * @ORM\Table(name="pelerin")
 * @ORM\Entity(repositoryClass="App\Repository\PelerinRepository")
 */
class Pelerin
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pelerin", mappedBy="imam")
     */
    private $pelerins;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pelerin")
     * @ORM\JoinColumn(nullable=true)
     */
    private $imam;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SessionImam")
     * @ORM\JoinColumn(nullable=true)
     */
    private $residimam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SessionOrganisateur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $residorgan;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agence")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agence;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bus")
     * @ORM\JoinColumn(nullable=true)
     */
    private $bus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vol")
     * @ORM\JoinColumn(nullable=true)
     */
    private $vol;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vol")
     * @ORM\JoinColumn(nullable=true)
     */
    private $retour;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chambre")
     * @ORM\JoinColumn(nullable=true)
     */
    private $chambre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChambreMecque")
     * @ORM\JoinColumn(nullable=true)
     */
    private $chambremecque;

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
     * @ORM\OneToOne(targetEntity="App\Entity\Medical")
     * @ORM\JoinColumn(nullable=true)
     */
    private $medical;

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
     * pattern= "/^[a-zA-Z éèêàâç]+$/",
     * match = true,
     * message = "Verifier les caractéres saisis")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(min = 2, minMessage="2 caractères au minimum", max = 25, maxMessage="25 caractères au maximum")
     * @Assert\NotBlank(message = "Renseignez le nom")
     * @Assert\Regex(
     * pattern= "/^[a-zA-Z éèêàâç]+$/",
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
     * @Assert\Length(min = 3, minMessage="3 caractéres au minimum", max = 30, maxMessage="30 caractères au maximum")
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
     * @Assert\Regex(
     * pattern= "/^([A]\d{8})+$/",
     * match = true,
     * message = "Numéro passeport incorrect")
     */
    private $numpassport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiredate", type="date", nullable=true)
     * @Assert\Date(message = "Format invalide")
     */
    private $expiredate;

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
     * @Assert\NotBlank(message = "Renseignez le numéro de téléphone")
     * @Assert\Regex(
     * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[0768]\s?\d{3}(\s?\d{2}){2}))$/",
     * match = true,
     * message = "Numéro de téléphone incorrect")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=255, nullable=true)
     * @Assert\Regex(
     * pattern= "/^([12]\d{12})+$/",
     * match = true,
     * message = "Numéro carte d'identité incorrect")
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string", length=255, nullable=true)
     * @Assert\Length(min = 3, minMessage="3 caractères au minimum", max = 70, maxMessage="70 caractères au maximum")
     * @Assert\Regex(
     * pattern= "/^[a-zA-Z ]+$/",
     * match = true,
     * message = "Vérifiez les caractères saisis")
     */
    private $profession;

    /**
     * @var string
     *
     * @ORM\Column(name="famillyname", type="string", length=255, nullable=true)
     */
    private $famillyname;

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
     * @ORM\Column(name="famillylink", type="string", length=255, nullable=true)
     */
    private $famillylink;

    /**
     * @var string
     *
     * @ORM\Column(name="famillyphone", type="string", length=255, nullable=true)
     * @Assert\Regex(
     * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[0768]\s?\d{3}(\s?\d{2}){2}))$/",
     * match = true,
     * message = "Numéro de téléphone incorrect")
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
     * @ORM\Column(name="ethnie", type="string", length=255, nullable=true)
     */
    private $ethnie;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="numsaoudiantax", type="string", length=255, nullable=true)
     */
    private $numsaoudiantax;

    /**
     * @var int
     *
     * @ORM\Column(name="compte", type="integer")
     */
    private $compte;

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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="surplus", type="boolean")
     */
    private $surplus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $crcode;


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
        $this->surplus = false;
        $this->pelerins = new ArrayCollection();
    }

    /**
     * Set pack
     *
     * @param \App\Entity\Pack $pack
     *
     * @return Pelerin
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
     * Set compte
     *
     * @param integer $compte
     *
     * @return Pelerin
     */
    public function setCompte($compte)
    {
        $this->compte = $this->compte + $compte;

        return $this;
    }
//
//    public function SupprimerVersement($montant)
//    {
//        $this->compte = $this->compte - $montant;
//    }

    /**
     * Get compte
     *
     * @return integer
     */
    public function getCompte()
    {
        return $this->compte;
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
     * Set reduction
     *
     * @param \App\Entity\Reduction $reduction
     *
     * @return Pelerin
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
     * Add versement
     *
     * @param \App\Entity\Versement $versement
     *
     * @return Pelerin
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
     * @return Pelerin
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

    public function Dossier()
    {
        if ($this->datenaiss == null) {
            return false;
        }
        if ($this->adresse == null) {
            return false;
        }
        if ($this->numpassport == null) {
            return false;
        }
        if ($this->famillyphone == null) {
            return false;
        }
        if ($this->bloodgroup == null) {
            return false;
        }
        if ($this->numsaoudiantax == null) {
            return false;
        }
        if ($this->image == null) {
            return false;
        }
        if ($this->visite == false) {
            return false;
        }
        return true;
    }

    public function getAge()
    {
        if ($this->datenaiss != null) {
            $today = new \Datetime();
            $diff = $today->diff($this->datenaiss);
            $age = $diff->format('%y');
            return $age;
        } else return null;
    }

    /**
     * Set remark
     *
     * @param string $remark
     *
     * @return Pelerin
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
     * Set versementbanque
     *
     * @param \App\Entity\VersementBanque $versementbanque
     *
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * Set diabete
     *
     * @param boolean $diabete
     *
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
     * @return Pelerin
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
        return str_pad($this->numdossier, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Set vol
     *
     * @param \App\Entity\Vol $vol
     *
     * @return Pelerin
     */
    public function setVol(\App\Entity\Vol $vol = null)
    {
        $this->vol = $vol;

        return $this;
    }

    /**
     * Get vol
     *
     * @return \App\Entity\Vol
     */
    public function getVol()
    {
        return $this->vol;
    }

    /**
     * Set retour
     *
     * @param \App\Entity\Vol $retour
     *
     * @return Pelerin
     */
    public function setRetour(\App\Entity\Vol $retour = null)
    {
        $this->retour = $retour;

        return $this;
    }

    /**
     * Get retour
     *
     * @return \App\Entity\Vol
     */
    public function getRetour()
    {
        return $this->retour;
    }

    /**
     * Set bus
     *
     * @param \App\Entity\Bus $bus
     *
     * @return Pelerin
     */
    public function setBus(\App\Entity\Bus $bus = null)
    {
        $this->bus = $bus;

        return $this;
    }

    /**
     * Get bus
     *
     * @return \App\Entity\Bus
     */
    public function getBus()
    {
        return $this->bus;
    }

    /**
     * Set chambre
     *
     * @param \App\Entity\Chambre $chambre
     *
     * @return Pelerin
     */
    public function setChambre(\App\Entity\Chambre $chambre = null)
    {
        $this->chambre = $chambre;

        return $this;
    }

    /**
     * Get chambre
     *
     * @return \App\Entity\Chambre
     */
    public function getChambre()
    {
        return $this->chambre;
    }


    /**
     * Set chambremecque
     *
     * @param \App\Entity\ChambreMecque $chambremecque
     *
     * @return Pelerin
     */
    public function setChambremecque(\App\Entity\ChambreMecque $chambremecque = null)
    {
        $this->chambremecque = $chambremecque;

        return $this;
    }

    /**
     * Get chambremecque
     *
     * @return \App\Entity\ChambreMecque
     */
    public function getChambremecque()
    {
        return $this->chambremecque;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Pelerin
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
     * Set residorgan
     *
     * @param \App\Entity\SessionOrganisateur $residorgan
     *
     * @return Pelerin
     */
    public function setResidorgan(\App\Entity\SessionOrganisateur $residorgan = null)
    {
        $this->residorgan = $residorgan;

        return $this;
    }

    /**
     * Get residorgan
     *
     * @return \App\Entity\SessionOrganisateur
     */
    public function getResidorgan()
    {
        return $this->residorgan;
    }

    /**
     * Set residimam
     *
     * @param \App\Entity\SessionImam $residimam
     *
     * @return Pelerin
     */
    public function setResidimam(\App\Entity\SessionImam $residimam = null)
    {
        $this->residimam = $residimam;

        return $this;
    }

    /**
     * Get residimam
     *
     * @return \App\Entity\SessionImam
     */
    public function getResidimam()
    {
        return $this->residimam;
    }

    /**
     * Add pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return Pelerin
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
     * Set imam
     *
     * @param \App\Entity\Pelerin $imam
     *
     * @return Pelerin
     */
    public function setImam(\App\Entity\Pelerin $imam = null)
    {
        $this->imam = $imam;

        return $this;
    }

    /**
     * Get imam
     *
     * @return \App\Entity\Pelerin
     */
    public function getImam()
    {
        return $this->imam;
    }

    /**
     * Set famillyname
     *
     * @param string $famillyname
     *
     * @return Pelerin
     */
    public function setFamillyname($famillyname)
    {
        $this->famillyname = $famillyname;

        return $this;
    }

    /**
     * Get famillyname
     *
     * @return string
     */
    public function getFamillyname()
    {
        return $this->famillyname;
    }

    /**
     * Set famillylink
     *
     * @param string $famillylink
     *
     * @return Pelerin
     */
    public function setFamillylink($famillylink)
    {
        $this->famillylink = $famillylink;

        return $this;
    }

    /**
     * Get famillylink
     *
     * @return string
     */
    public function getFamillylink()
    {
        return $this->famillylink;
    }

    /**
     * Set expiredate
     *
     * @param \DateTime $expiredate
     *
     * @return Pelerin
     */
    public function setExpiredate($expiredate)
    {
        $this->expiredate = $expiredate;

        return $this;
    }

    /**
     * Get expiredate
     *
     * @return \DateTime
     */
    public function getExpiredate()
    {
        return $this->expiredate;
    }

    /**
     * Set profession
     *
     * @param string $profession
     *
     * @return Pelerin
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set surplus
     *
     * @param boolean $surplus
     *
     * @return Pelerin
     */
    public function setSurplus($surplus)
    {
        $this->surplus = $surplus;

        return $this;
    }

    /**
     * Get surplus
     *
     * @return boolean
     */
    public function getSurplus()
    {
        return $this->surplus;
    }

    public function getSituation()
    {
        $montant = $this->getPack()->getMontant();
        if (!empty($this->getReduction())) {
            $montant = $montant - $this->getReduction()->getMontant();// cas ou la reduction egale au monatnt du pack (exonorer)

        }
        if ($this->getSurplus())
            $montant = $montant + (2000 * $this->getPack()->getTaux());


        return $montant;

    }

    /**
     * Set medical
     *
     * @param \App\Entity\Medical $medical
     *
     * @return Pelerin
     */
    public function setMedical(\App\Entity\Medical $medical = null)
    {
        $this->medical = $medical;

        return $this;
    }

    /**
     * Get medical
     *
     * @return \App\Entity\Medical
     */
    public function getMedical()
    {
        return $this->medical;
    }

    /**
     * Set ethnie
     *
     * @param string $ethnie
     *
     * @return Pelerin
     */
    public function setEthnie($ethnie)
    {
        $this->ethnie = $ethnie;

        return $this;
    }

    /**
     * Get ethnie
     *
     * @return string
     */
    public function getEthnie()
    {
        return $this->ethnie;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Pelerin
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    public function getCrcode(): ?string
    {
        return $this->crcode;
    }

    public function setCrcode(?string $crcode): self
    {
        $this->crcode = $crcode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
