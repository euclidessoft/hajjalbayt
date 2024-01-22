<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pack
 *
 * @ORM\Table(name="pack")
 * @ORM\Entity(repositoryClass="App\Repository\PackRepository")
 */
class Pack
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
	/**
	* @ORM\OneToMany(targetEntity="App\Entity\Pelerin", mappedBy="pack")
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
     * @ORM\Column(name="designation", type="string", length=255)
	 * @Assert\Length(min = 3, minMessage="Longueur comprise entre 3 et 30 caractères",max = 30, maxMessage="Longueur comprise entre 3 et 30 caractères")
	 * @Assert\NotBlank(message = "Renseignez le nom du package")
	 * @Assert\Regex(
	 * pattern= "/^[0-9a-zA-Z éèêàâç]+$/",
	 * match = true,
	 * message = "Vérifiez les caractères saisis")
     */
    private $designation;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer")
	 * @Assert\Length(min = 6, minMessage="6 chiffres au minimum",max = 9, maxMessage="9 chiffres au maximum")
	 * @Assert\NotBlank(message = "Renseignez le montant")
	 * @Assert\Regex(
	 * pattern= "/^\d+$/",
	 * match = true,
	 * message = "Des chiffres uniquement")
     */
    private $montant;

	
	/**
     * @var int
     *
     * @ORM\Column(name="exploitation", type="integer", nullable=true)
	 * @Assert\Length(min = 6, minMessage="6 chiffres au minimum",max = 9, maxMessage="9 chiffres au maximum")
	 * @Assert\Regex(
	 * pattern= "/^\d+$/",
	 * match = true,
	 * message = "Des chiffres uniquement")
     */
    private $exploitation;

    /**
     * @var float
     *
     * @ORM\Column(name="taux", type="float", nullable=true)
     */
    private $taux;
	
	/**
     * @var string
     *
     * @ORM\Column(name="aerien1", type="string", length=255, nullable=true)
     */
    private $aerien1;
	
	/**
     * @var string
     *
     * @ORM\Column(name="aeriencout1", type="decimal", nullable=true)
     */
    private $eriencout1;
	
	/**
     * @var string
     *
     * @ORM\Column(name="aerien2", type="string", length=255, nullable=true)
     */
    private $aerien2;
	
	/**
     * @var string
     *
     * @ORM\Column(name="aeriencout2", type="decimal", nullable=true)
     */
    private $eriencout2;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="aerien3", type="string", length=255, nullable=true)
     */
    private $aerien3;
	
	/**
     * @var string
     *
     * @ORM\Column(name="aeriencout3", type="decimal", nullable=true)
     */
    private $eriencout3;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="hebergementmecque", type="string", length=255, nullable=true)
     */
    private $hebergementmecque;
	
	/**
     * @var string
     *
     * @ORM\Column(name="hebergementmecquecout", type="decimal", nullable=true)
     */
    private $hebergementmecquecout;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="hebergementmedine", type="string", length=255, nullable=true)
     */
    private $hebergementmedine;
	
	/**
     * @var string
     *
     * @ORM\Column(name="hebergementmedinecout", type="decimal", nullable=true)
     */
    private $hebergementmedinecout;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="pensionmecque", type="string", length=255, nullable=true)
     */
    private $pensionmecque;
	
	/**
     * @var string
     *
     * @ORM\Column(name="pensionmecquecout", type="decimal", nullable=true)
     */
    private $pensionmecquecout;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="pensionmedine", type="string", length=255, nullable=true)
     */
    private $pensionmedine;
	
	/**
     * @var string
     *
     * @ORM\Column(name="pensionmedinecout", type="decimal", nullable=true)
     */
    private $pensionmedinecout;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="terrestreordinaire", type="string", length=255, nullable=true)
     */
    private $terrestreordinaire;
	
	/**
     * @var string
     *
     * @ORM\Column(name="terrestreordinairecout", type="decimal", nullable=true)
     */
    private $terrestreordinairecout;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="terrestrevip", type="string", length=255, nullable=true)
     */
    private $terrestrevip;
	
	/**
     * @var string
     *
     * @ORM\Column(name="terrestrevipcout", type="decimal", nullable=true)
     */
    private $terrestrevipcout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="terrestreautre", type="string", length=255, nullable=true)
     */
    private $terrestreautre;
	
	/**
     * @var string
     *
     * @ORM\Column(name="terrestreautrecout", type="decimal", nullable=true)
     */
    private $terrestreautrecout;

	/**
     * @var string
     *
     * @ORM\Column(name="vipservice1", type="string", length=255, nullable=true)
     */
    private $vipservice1;
	
	/**
     * @var string
     *
     * @ORM\Column(name="vipservice1cout", type="decimal", nullable=true)
     */
    private $vipservice1cout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="vipservice2", type="string", length=255, nullable=true)
     */
    private $vipservice2;
	
	/**
     * @var string
     *
     * @ORM\Column(name="vipservice2cout", type="decimal", nullable=true)
     */
    private $vipservice2cout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="vipservice3", type="string", length=255, nullable=true)
     */
    private $vipservice3;
	
	/**
     * @var string
     *
     * @ORM\Column(name="vipservice3cout", type="decimal", nullable=true)
     */
    private $vipservice3cout;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="taxe", type="string", length=255, nullable=true)
     */
    private $taxe;
	
	/**
     * @var string
     *
     * @ORM\Column(name="taxecout", type="decimal", nullable=true)
     */
    private $taxecout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="mouna", type="string", length=255, nullable=true)
     */
    private $mouna;
	
	/**
     * @var string
     *
     * @ORM\Column(name="mounacout", type="decimal", nullable=true)
     */
    private $mounacout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="restomouna", type="string", length=255, nullable=true)
     */
    private $restomouna;
	
	/**
     * @var string
     *
     * @ORM\Column(name="restomounacout", type="decimal", nullable=true)
     */
    private $restomounacout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="assurance", type="string", length=255, nullable=true)
     */
    private $assurance;
	
	/**
     * @var string
     *
     * @ORM\Column(name="assurancecout", type="decimal", nullable=true)
     */
    private $assurancecout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="vaccin", type="string", length=255, nullable=true)
     */
    private $vaccin;
	
	/**
     * @var string
     *
     * @ORM\Column(name="vaccincout", type="decimal", nullable=true)
     */
    private $vaccincout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="pecule", type="string", length=255, nullable=true)
     */
    private $pecule;
	
	/**
     * @var string
     *
     * @ORM\Column(name="peculecout", type="decimal", nullable=true)
     */
    private $peculecout;
	
	
	/**
     * @var string
     *
     * @ORM\Column(name="zamzam", type="string", length=255, nullable=true)
     */
    private $zamzam;
	
	/**
     * @var string
     *
     * @ORM\Column(name="zamzamcout", type="decimal", nullable=true)
     */
    private $zamzamcout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="guide", type="string", length=255, nullable=true)
     */
    private $guide;
	
	/**
     * @var string
     *
     * @ORM\Column(name="guidecout", type="decimal", nullable=true)
     */
    private $guidecout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="medecin", type="string", length=255, nullable=true)
     */
    private $medecin;
	
	/**
     * @var string
     *
     * @ORM\Column(name="medecincout", type="decimal", nullable=true)
     */
    private $medecincout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="administratif", type="string", length=255, nullable=true)
     */
    private $administratif;
	
	/**
     * @var string
     *
     * @ORM\Column(name="administratifcout", type="decimal", nullable=true)
     */
    private $administratifcout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="autreservice", type="string", length=255, nullable=true)
     */
    private $autreservice;
	
	/**
     * @var string
     *
     * @ORM\Column(name="autreservicecout", type="decimal", nullable=true)
     */
    private $autreservicecout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="promotion1", type="string", length=255, nullable=true)
     */
    private $promotion1;
	
	/**
     * @var string
     *
     * @ORM\Column(name="promotion1cout", type="decimal", nullable=true)
     */
    private $promotion1cout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="promotion2", type="string", length=255, nullable=true)
     */
    private $promotion2;
	
	/**
     * @var string
     *
     * @ORM\Column(name="promotion2cout", type="decimal", nullable=true)
     */
    private $promotion2cout;
	
	/**
     * @var string
     *
     * @ORM\Column(name="promotion3", type="string", length=255, nullable=true)
     */
    private $promotion3;
	
	/**
     * @var string
     *
     * @ORM\Column(name="promotion3cout", type="decimal", nullable=true)
     */
    private $promotion3cout;

    /**
     * @ORM\Column(type="datetime", nullable=true)
	 * @Assert\Date(message = "Format invalide")
     */
    private $limite;


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
     * @return Pack
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
     * Set montant
     *
     * @param integer $montant
     *
     * @return Pack
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->pelerins = new \Doctrine\Common\Collections\ArrayCollection();
		$this->aerien1 = 'Air Sénégal';
		$this->eriencout1 = 1085368;
		$this->taxe = 'Taxe Saoudienne';
		$this->taxecout = 171589.5;
		$this->mouna = 'Tentes Mouna et Arafat + Matelas Mouna';
		$this->mounacout = 199206;
		$this->restomouna = 'Restauration Mouna et Arafat';
		$this->restomounacout = 38250;
		$this->assurance = 'Assurance';
		$this->assurancecout = 4950;
		$this->vaccin = 'Vaccin';
		$this->vaccincout = 4850;
		$this->pecule = 'Pecule';
		$this->peculecout = 45900;
		$this->zamzam = 'Zamzam';
		$this->zamzamcout = 1836;
        $this->taux = 157.67;
    }


    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Pack
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
     * Add pelerin
     *
     * @param \App\Entity\Pelerin $pelerin
     *
     * @return Pack
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
     * Set exploitation
     *
     * @param integer $exploitation
     *
     * @return Pack
     */
    public function setExploitation($exploitation)
    {
        $this->exploitation = $exploitation;

        return $this;
    }

    /**
     * Get exploitation
     *
     * @return integer
     */
    public function getExploitation()
    {
        return $this->exploitation;
    }

    /**
     * Set aerien1
     *
     * @param string $aerien1
     *
     * @return Pack
     */
    public function setAerien1($aerien1)
    {
        $this->aerien1 = $aerien1;

        return $this;
    }

    /**
     * Get aerien1
     *
     * @return string
     */
    public function getAerien1()
    {
        return $this->aerien1;
    }

    /**
     * Set eriencout1
     *
     * @param string $eriencout1
     *
     * @return Pack
     */
    public function setEriencout1($eriencout1)
    {
        $this->eriencout1 = $eriencout1;

        return $this;
    }

    /**
     * Get eriencout1
     *
     * @return string
     */
    public function getEriencout1()
    {
        return $this->eriencout1;
    }

    /**
     * Set aerien2
     *
     * @param string $aerien2
     *
     * @return Pack
     */
    public function setAerien2($aerien2)
    {
        $this->aerien2 = $aerien2;

        return $this;
    }

    /**
     * Get aerien2
     *
     * @return string
     */
    public function getAerien2()
    {
        return $this->aerien2;
    }

    /**
     * Set eriencout2
     *
     * @param string $eriencout2
     *
     * @return Pack
     */
    public function setEriencout2($eriencout2)
    {
        $this->eriencout2 = $eriencout2;

        return $this;
    }

    /**
     * Get eriencout2
     *
     * @return string
     */
    public function getEriencout2()
    {
        return $this->eriencout2;
    }

    /**
     * Set aerien3
     *
     * @param string $aerien3
     *
     * @return Pack
     */
    public function setAerien3($aerien3)
    {
        $this->aerien3 = $aerien3;

        return $this;
    }

    /**
     * Get aerien3
     *
     * @return string
     */
    public function getAerien3()
    {
        return $this->aerien3;
    }

    /**
     * Set eriencout3
     *
     * @param string $eriencout3
     *
     * @return Pack
     */
    public function setEriencout3($eriencout3)
    {
        $this->eriencout3 = $eriencout3;

        return $this;
    }

    /**
     * Get eriencout3
     *
     * @return string
     */
    public function getEriencout3()
    {
        return $this->eriencout3;
    }

    /**
     * Set hebergementmecque
     *
     * @param string $hebergementmecque
     *
     * @return Pack
     */
    public function setHebergementmecque($hebergementmecque)
    {
        $this->hebergementmecque = $hebergementmecque;

        return $this;
    }

    /**
     * Get hebergementmecque
     *
     * @return string
     */
    public function getHebergementmecque()
    {
        return $this->hebergementmecque;
    }

    /**
     * Set hebergementmecquecout
     *
     * @param string $hebergementmecquecout
     *
     * @return Pack
     */
    public function setHebergementmecquecout($hebergementmecquecout)
    {
        $this->hebergementmecquecout = $hebergementmecquecout;

        return $this;
    }

    /**
     * Get hebergementmecquecout
     *
     * @return string
     */
    public function getHebergementmecquecout()
    {
        return $this->hebergementmecquecout;
    }

    /**
     * Set hebergementmedine
     *
     * @param string $hebergementmedine
     *
     * @return Pack
     */
    public function setHebergementmedine($hebergementmedine)
    {
        $this->hebergementmedine = $hebergementmedine;

        return $this;
    }

    /**
     * Get hebergementmedine
     *
     * @return string
     */
    public function getHebergementmedine()
    {
        return $this->hebergementmedine;
    }

    /**
     * Set hebergementmedinecout
     *
     * @param string $hebergementmedinecout
     *
     * @return Pack
     */
    public function setHebergementmedinecout($hebergementmedinecout)
    {
        $this->hebergementmedinecout = $hebergementmedinecout;

        return $this;
    }

    /**
     * Get hebergementmedinecout
     *
     * @return string
     */
    public function getHebergementmedinecout()
    {
        return $this->hebergementmedinecout;
    }

    /**
     * Set pensionmecque
     *
     * @param string $pensionmecque
     *
     * @return Pack
     */
    public function setPensionmecque($pensionmecque)
    {
        $this->pensionmecque = $pensionmecque;

        return $this;
    }

    /**
     * Get pensionmecque
     *
     * @return string
     */
    public function getPensionmecque()
    {
        return $this->pensionmecque;
    }

    /**
     * Set pensionmecquecout
     *
     * @param string $pensionmecquecout
     *
     * @return Pack
     */
    public function setPensionmecquecout($pensionmecquecout)
    {
        $this->pensionmecquecout = $pensionmecquecout;

        return $this;
    }

    /**
     * Get pensionmecquecout
     *
     * @return string
     */
    public function getPensionmecquecout()
    {
        return $this->pensionmecquecout;
    }

    /**
     * Set pensionmedine
     *
     * @param string $pensionmedine
     *
     * @return Pack
     */
    public function setPensionmedine($pensionmedine)
    {
        $this->pensionmedine = $pensionmedine;

        return $this;
    }

    /**
     * Get pensionmedine
     *
     * @return string
     */
    public function getPensionmedine()
    {
        return $this->pensionmedine;
    }

    /**
     * Set pensionmedinecout
     *
     * @param string $pensionmedinecout
     *
     * @return Pack
     */
    public function setPensionmedinecout($pensionmedinecout)
    {
        $this->pensionmedinecout = $pensionmedinecout;

        return $this;
    }

    /**
     * Get pensionmedinecout
     *
     * @return string
     */
    public function getPensionmedinecout()
    {
        return $this->pensionmedinecout;
    }

    /**
     * Set terrestreordinaire
     *
     * @param string $terrestreordinaire
     *
     * @return Pack
     */
    public function setTerrestreordinaire($terrestreordinaire)
    {
        $this->terrestreordinaire = $terrestreordinaire;

        return $this;
    }

    /**
     * Get terrestreordinaire
     *
     * @return string
     */
    public function getTerrestreordinaire()
    {
        return $this->terrestreordinaire;
    }

    /**
     * Set terrestreordinairecout
     *
     * @param string $terrestreordinairecout
     *
     * @return Pack
     */
    public function setTerrestreordinairecout($terrestreordinairecout)
    {
        $this->terrestreordinairecout = $terrestreordinairecout;

        return $this;
    }

    /**
     * Get terrestreordinairecout
     *
     * @return string
     */
    public function getTerrestreordinairecout()
    {
        return $this->terrestreordinairecout;
    }

    /**
     * Set terrestrevip
     *
     * @param string $terrestrevip
     *
     * @return Pack
     */
    public function setTerrestrevip($terrestrevip)
    {
        $this->terrestrevip = $terrestrevip;

        return $this;
    }

    /**
     * Get terrestrevip
     *
     * @return string
     */
    public function getTerrestrevip()
    {
        return $this->terrestrevip;
    }

    /**
     * Set terrestrevipcout
     *
     * @param string $terrestrevipcout
     *
     * @return Pack
     */
    public function setTerrestrevipcout($terrestrevipcout)
    {
        $this->terrestrevipcout = $terrestrevipcout;

        return $this;
    }

    /**
     * Get terrestrevipcout
     *
     * @return string
     */
    public function getTerrestrevipcout()
    {
        return $this->terrestrevipcout;
    }

    /**
     * Set terrestreautre
     *
     * @param string $terrestreautre
     *
     * @return Pack
     */
    public function setTerrestreautre($terrestreautre)
    {
        $this->terrestreautre = $terrestreautre;

        return $this;
    }

    /**
     * Get terrestreautre
     *
     * @return string
     */
    public function getTerrestreautre()
    {
        return $this->terrestreautre;
    }

    /**
     * Set terrestreautrecout
     *
     * @param string $terrestreautrecout
     *
     * @return Pack
     */
    public function setTerrestreautrecout($terrestreautrecout)
    {
        $this->terrestreautrecout = $terrestreautrecout;

        return $this;
    }

    /**
     * Get terrestreautrecout
     *
     * @return string
     */
    public function getTerrestreautrecout()
    {
        return $this->terrestreautrecout;
    }

    /**
     * Set vipservice1
     *
     * @param string $vipservice1
     *
     * @return Pack
     */
    public function setVipservice1($vipservice1)
    {
        $this->vipservice1 = $vipservice1;

        return $this;
    }

    /**
     * Get vipservice1
     *
     * @return string
     */
    public function getVipservice1()
    {
        return $this->vipservice1;
    }

    /**
     * Set vipservice1cout
     *
     * @param string $vipservice1cout
     *
     * @return Pack
     */
    public function setVipservice1cout($vipservice1cout)
    {
        $this->vipservice1cout = $vipservice1cout;

        return $this;
    }

    /**
     * Get vipservice1cout
     *
     * @return string
     */
    public function getVipservice1cout()
    {
        return $this->vipservice1cout;
    }

    /**
     * Set vipservice2
     *
     * @param string $vipservice2
     *
     * @return Pack
     */
    public function setVipservice2($vipservice2)
    {
        $this->vipservice2 = $vipservice2;

        return $this;
    }

    /**
     * Get vipservice2
     *
     * @return string
     */
    public function getVipservice2()
    {
        return $this->vipservice2;
    }

    /**
     * Set vipservice2cout
     *
     * @param string $vipservice2cout
     *
     * @return Pack
     */
    public function setVipservice2cout($vipservice2cout)
    {
        $this->vipservice2cout = $vipservice2cout;

        return $this;
    }

    /**
     * Get vipservice2cout
     *
     * @return string
     */
    public function getVipservice2cout()
    {
        return $this->vipservice2cout;
    }

    /**
     * Set vipservice3
     *
     * @param string $vipservice3
     *
     * @return Pack
     */
    public function setVipservice3($vipservice3)
    {
        $this->vipservice3 = $vipservice3;

        return $this;
    }

    /**
     * Get vipservice3
     *
     * @return string
     */
    public function getVipservice3()
    {
        return $this->vipservice3;
    }

    /**
     * Set vipservice3cout
     *
     * @param string $vipservice3cout
     *
     * @return Pack
     */
    public function setVipservice3cout($vipservice3cout)
    {
        $this->vipservice3cout = $vipservice3cout;

        return $this;
    }

    /**
     * Get vipservice3cout
     *
     * @return string
     */
    public function getVipservice3cout()
    {
        return $this->vipservice3cout;
    }

    /**
     * Set taxe
     *
     * @param string $taxe
     *
     * @return Pack
     */
    public function setTaxe($taxe)
    {
        $this->taxe = $taxe;

        return $this;
    }

    /**
     * Get taxe
     *
     * @return string
     */
    public function getTaxe()
    {
        return $this->taxe;
    }

    /**
     * Set taxecout
     *
     * @param string $taxecout
     *
     * @return Pack
     */
    public function setTaxecout($taxecout)
    {
        $this->taxecout = $taxecout;

        return $this;
    }

    /**
     * Get taxecout
     *
     * @return string
     */
    public function getTaxecout()
    {
        return $this->taxecout;
    }

    /**
     * Set mouna
     *
     * @param string $mouna
     *
     * @return Pack
     */
    public function setMouna($mouna)
    {
        $this->mouna = $mouna;

        return $this;
    }

    /**
     * Get mouna
     *
     * @return string
     */
    public function getMouna()
    {
        return $this->mouna;
    }

    /**
     * Set mounacout
     *
     * @param string $mounacout
     *
     * @return Pack
     */
    public function setMounacout($mounacout)
    {
        $this->mounacout = $mounacout;

        return $this;
    }

    /**
     * Get mounacout
     *
     * @return string
     */
    public function getMounacout()
    {
        return $this->mounacout;
    }

    /**
     * Set restomouna
     *
     * @param string $restomouna
     *
     * @return Pack
     */
    public function setRestomouna($restomouna)
    {
        $this->restomouna = $restomouna;

        return $this;
    }

    /**
     * Get restomouna
     *
     * @return string
     */
    public function getRestomouna()
    {
        return $this->restomouna;
    }

    /**
     * Set restomounacout
     *
     * @param string $restomounacout
     *
     * @return Pack
     */
    public function setRestomounacout($restomounacout)
    {
        $this->restomounacout = $restomounacout;

        return $this;
    }

    /**
     * Get restomounacout
     *
     * @return string
     */
    public function getRestomounacout()
    {
        return $this->restomounacout;
    }

    /**
     * Set assurance
     *
     * @param string $assurance
     *
     * @return Pack
     */
    public function setAssurance($assurance)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return string
     */
    public function getAssurance()
    {
        return $this->assurance;
    }

    /**
     * Set assurancecout
     *
     * @param string $assurancecout
     *
     * @return Pack
     */
    public function setAssurancecout($assurancecout)
    {
        $this->assurancecout = $assurancecout;

        return $this;
    }

    /**
     * Get assurancecout
     *
     * @return string
     */
    public function getAssurancecout()
    {
        return $this->assurancecout;
    }

    /**
     * Set vaccin
     *
     * @param string $vaccin
     *
     * @return Pack
     */
    public function setVaccin($vaccin)
    {
        $this->vaccin = $vaccin;

        return $this;
    }

    /**
     * Get vaccin
     *
     * @return string
     */
    public function getVaccin()
    {
        return $this->vaccin;
    }

    /**
     * Set vaccincout
     *
     * @param string $vaccincout
     *
     * @return Pack
     */
    public function setVaccincout($vaccincout)
    {
        $this->vaccincout = $vaccincout;

        return $this;
    }

    /**
     * Get vaccincout
     *
     * @return string
     */
    public function getVaccincout()
    {
        return $this->vaccincout;
    }

    /**
     * Set pecule
     *
     * @param string $pecule
     *
     * @return Pack
     */
    public function setPecule($pecule)
    {
        $this->pecule = $pecule;

        return $this;
    }

    /**
     * Get pecule
     *
     * @return string
     */
    public function getPecule()
    {
        return $this->pecule;
    }

    /**
     * Set peculecout
     *
     * @param string $peculecout
     *
     * @return Pack
     */
    public function setPeculecout($peculecout)
    {
        $this->peculecout = $peculecout;

        return $this;
    }

    /**
     * Get peculecout
     *
     * @return string
     */
    public function getPeculecout()
    {
        return $this->peculecout;
    }

    /**
     * Set zamzam
     *
     * @param string $zamzam
     *
     * @return Pack
     */
    public function setZamzam($zamzam)
    {
        $this->zamzam = $zamzam;

        return $this;
    }

    /**
     * Get zamzam
     *
     * @return string
     */
    public function getZamzam()
    {
        return $this->zamzam;
    }

    /**
     * Set zamzamcout
     *
     * @param string $zamzamcout
     *
     * @return Pack
     */
    public function setZamzamcout($zamzamcout)
    {
        $this->zamzamcout = $zamzamcout;

        return $this;
    }

    /**
     * Get zamzamcout
     *
     * @return string
     */
    public function getZamzamcout()
    {
        return $this->zamzamcout;
    }

    /**
     * Set guide
     *
     * @param string $guide
     *
     * @return Pack
     */
    public function setGuide($guide)
    {
        $this->guide = $guide;

        return $this;
    }

    /**
     * Get guide
     *
     * @return string
     */
    public function getGuide()
    {
        return $this->guide;
    }

    /**
     * Set guidecout
     *
     * @param string $guidecout
     *
     * @return Pack
     */
    public function setGuidecout($guidecout)
    {
        $this->guidecout = $guidecout;

        return $this;
    }

    /**
     * Get guidecout
     *
     * @return string
     */
    public function getGuidecout()
    {
        return $this->guidecout;
    }

    /**
     * Set medecin
     *
     * @param string $medecin
     *
     * @return Pack
     */
    public function setMedecin($medecin)
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return string
     */
    public function getMedecin()
    {
        return $this->medecin;
    }

    /**
     * Set medecincout
     *
     * @param string $medecincout
     *
     * @return Pack
     */
    public function setMedecincout($medecincout)
    {
        $this->medecincout = $medecincout;

        return $this;
    }

    /**
     * Get medecincout
     *
     * @return string
     */
    public function getMedecincout()
    {
        return $this->medecincout;
    }

    /**
     * Set administratif
     *
     * @param string $administratif
     *
     * @return Pack
     */
    public function setAdministratif($administratif)
    {
        $this->administratif = $administratif;

        return $this;
    }

    /**
     * Get administratif
     *
     * @return string
     */
    public function getAdministratif()
    {
        return $this->administratif;
    }

    /**
     * Set administratifcout
     *
     * @param string $administratifcout
     *
     * @return Pack
     */
    public function setAdministratifcout($administratifcout)
    {
        $this->administratifcout = $administratifcout;

        return $this;
    }

    /**
     * Get administratifcout
     *
     * @return string
     */
    public function getAdministratifcout()
    {
        return $this->administratifcout;
    }

    /**
     * Set autreservice
     *
     * @param string $autreservice
     *
     * @return Pack
     */
    public function setAutreservice($autreservice)
    {
        $this->autreservice = $autreservice;

        return $this;
    }

    /**
     * Get autreservice
     *
     * @return string
     */
    public function getAutreservice()
    {
        return $this->autreservice;
    }

    /**
     * Set autreservicecout
     *
     * @param string $autreservicecout
     *
     * @return Pack
     */
    public function setAutreservicecout($autreservicecout)
    {
        $this->autreservicecout = $autreservicecout;

        return $this;
    }

    /**
     * Get autreservicecout
     *
     * @return string
     */
    public function getAutreservicecout()
    {
        return $this->autreservicecout;
    }

    /**
     * Set promotion1
     *
     * @param string $promotion1
     *
     * @return Pack
     */
    public function setPromotion1($promotion1)
    {
        $this->promotion1 = $promotion1;

        return $this;
    }

    /**
     * Get promotion1
     *
     * @return string
     */
    public function getPromotion1()
    {
        return $this->promotion1;
    }

    /**
     * Set promotion1cout
     *
     * @param string $promotion1cout
     *
     * @return Pack
     */
    public function setPromotion1cout($promotion1cout)
    {
        $this->promotion1cout = $promotion1cout;

        return $this;
    }

    /**
     * Get promotion1cout
     *
     * @return string
     */
    public function getPromotion1cout()
    {
        return $this->promotion1cout;
    }

    /**
     * Set promotion2
     *
     * @param string $promotion2
     *
     * @return Pack
     */
    public function setPromotion2($promotion2)
    {
        $this->promotion2 = $promotion2;

        return $this;
    }

    /**
     * Get promotion2
     *
     * @return string
     */
    public function getPromotion2()
    {
        return $this->promotion2;
    }

    /**
     * Set promotion2cout
     *
     * @param string $promotion2cout
     *
     * @return Pack
     */
    public function setPromotion2cout($promotion2cout)
    {
        $this->promotion2cout = $promotion2cout;

        return $this;
    }

    /**
     * Get promotion2cout
     *
     * @return string
     */
    public function getPromotion2cout()
    {
        return $this->promotion2cout;
    }

    /**
     * Set promotion3
     *
     * @param string $promotion3
     *
     * @return Pack
     */
    public function setPromotion3($promotion3)
    {
        $this->promotion3 = $promotion3;

        return $this;
    }

    /**
     * Get promotion3
     *
     * @return string
     */
    public function getPromotion3()
    {
        return $this->promotion3;
    }

    /**
     * Set promotion3cout
     *
     * @param string $promotion3cout
     *
     * @return Pack
     */
    public function setPromotion3cout($promotion3cout)
    {
        $this->promotion3cout = $promotion3cout;

        return $this;
    }

    /**
     * Get promotion3cout
     *
     * @return string
     */
    public function getPromotion3cout()
    {
        return $this->promotion3cout;
    }

    /**
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Pack
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

    /**
     * Set taux
     *
     * @param float $taux
     *
     * @return Pack
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return float
     */
    public function getTaux()
    {
        return $this->taux;
    }

    public function getLimite(): ?\DateTimeInterface
    {
        return $this->limite;
    }

    public function setLimite(\DateTimeInterface $limite=null): self
    {
        $this->limite = $limite;

        return $this;
    }

}
