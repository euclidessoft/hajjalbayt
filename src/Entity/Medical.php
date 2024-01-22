<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medical
 *
 * @ORM\Table(name="medical")
 * @ORM\Entity(repositoryClass="App\Repository\MedicalRepository")
 */
class Medical
{
    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Session")
    * @ORM\JoinColumn(nullable=false)
    */
    private $session;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hta", type="boolean")
     */
    private $hta;

     /**
     * @var boolean
     *
     * @ORM\Column(name="charriot", type="boolean")
     */
    private $charriot;

     /**
     * @var boolean
     *
     * @ORM\Column(name="enceinte", type="boolean")
     */
    private $enceinte;

    /**
     * @var boolean
     *
     * @ORM\Column(name="diabete", type="boolean")
     */
    private $diabete;

    /**
     * @var boolean
     *
     * @ORM\Column(name="asthme", type="boolean")
     */
    private $asthme;

    /**
     * @var boolean
     *
     * @ORM\Column(name="drepano", type="boolean")
     */
    private $drepano;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuberculose", type="boolean")
     */
    private $tuberculose;

    /**
     * @var boolean
     *
     * @ORM\Column(name="arthrose", type="boolean")
     */
    private $arthrose;

    /**
     * @var string
     *
     * @ORM\Column(name="chabdo", type="string", length=255, nullable=true)
     */
    private $chabdo;

    /**
     * @var string
     *
     * @ORM\Column(name="chpulmo", type="string", length=255, nullable=true)
     */
    private $chpulmo;

    /**
     * @var string
     *
     * @ORM\Column(name="chortho", type="string", length=255, nullable=true)
     */
    private $chortho;

    /**
     * @var string
     *
     * @ORM\Column(name="cesarienne", type="string", length=255, nullable=true)
     */
    private $cesarienne;

    /**
     * @var string
     *
     * @ORM\Column(name="autres", type="text", nullable=true)
     */
    private $autres;

    /**
     * @var string
     *
     * @ORM\Column(name="obsteg", type="string", length=255, nullable=true)
     */
    private $obsteg;

    /**
     * @var string
     *
     * @ORM\Column(name="obstep", type="string", length=255, nullable=true)
     */
    private $obstep;

    /**
     * @var string
     *
     * @ORM\Column(name="psychiatrique", type="string", length=255, nullable=true)
     */
    private $psychiatrique;

    /**
     * @var string
     *
     * @ORM\Column(name="oms", type="string", length=255, nullable=true)
     */
    private $oms;

    /**
     * @var string
     *
     * @ORM\Column(name="temper", type="string", length=255, nullable=true)
     */
    private $temper;

    /**
     * @var string
     *
     * @ORM\Column(name="tensionone", type="string", length=255, nullable=true)
     */
    private $tensionone;

    /**
     * @var string
     *
     * @ORM\Column(name="tensiontwo", type="string", length=255, nullable=true)
     */
    private $tensiontwo;

    /**
     * @var string
     *
     * @ORM\Column(name="pouls", type="string", length=255, nullable=true)
     */
    private $pouls;

    /**
     * @var string
     *
     * @ORM\Column(name="respir", type="string", length=255, nullable=true)
     */
    private $respir;

    /**
     * @var string
     *
     * @ORM\Column(name="oxygene", type="string", length=255, nullable=true)
     */
    private $oxygene;

    /**
     * @var string
     *
     * @ORM\Column(name="coeuretvais", type="string", length=255, nullable=true)
     */
    private $coeuretvais;

    /**
     * @var string
     *
     * @ORM\Column(name="pulmo", type="string", length=255, nullable=true)
     */
    private $pulmo;

    /**
     * @var string
     *
     * @ORM\Column(name="neuro", type="string", length=255, nullable=true)
     */
    private $neuro;

    /**
     * @var string
     *
     * @ORM\Column(name="autreexam", type="text", nullable=true)
     */
    private $autreexam;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostic", type="text", nullable=true)
     */
    private $diagnostic;

    /**
     * @var string
     *
     * @ORM\Column(name="trait1", type="string", length=255, nullable=true)
     */
    private $trait1;

    /**
     * @var string
     *
     * @ORM\Column(name="trait2", type="string", length=255, nullable=true)
     */
    private $trait2;

    /**
     * @var string
     *
     * @ORM\Column(name="trait3", type="string", length=255, nullable=true)
     */
    private $trait3;

    /**
     * @var string
     *
     * @ORM\Column(name="trait4", type="string", length=255, nullable=true)
     */
    private $trait4;

    /**
     * @var string
     *
     * @ORM\Column(name="trait5", type="string", length=255, nullable=true)
     */
    private $trait5;

    public function __construct()
    {
        $this->hta = false;
        $this->diabete = false;
        $this->asthme = false;
        $this->drepano = false;
        $this->tuberculose = false;
        $this->arthrose = false;
        $this->enceinte = false;
        $this->charriot = false;
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
     * Set hta
     *
     * @param boolean $hta
     *
     * @return Medical
     */
    public function setHta($hta)
    {
        $this->hta = $hta;

        return $this;
    }

    /**
     * Get hta
     *
     * @return boolean
     */
    public function getHta()
    {
        return $this->hta;
    }

    /**
     * Set diabete
     *
     * @param boolean $diabete
     *
     * @return Medical
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
     * Set asthme
     *
     * @param boolean $asthme
     *
     * @return Medical
     */
    public function setAsthme($asthme)
    {
        $this->asthme = $asthme;

        return $this;
    }

    /**
     * Get asthme
     *
     * @return boolean
     */
    public function getAsthme()
    {
        return $this->asthme;
    }

    /**
     * Set drepano
     *
     * @param boolean $drepano
     *
     * @return Medical
     */
    public function setDrepano($drepano)
    {
        $this->drepano = $drepano;

        return $this;
    }

    /**
     * Get drepano
     *
     * @return boolean
     */
    public function getDrepano()
    {
        return $this->drepano;
    }

    /**
     * Set tuberculose
     *
     * @param boolean $tuberculose
     *
     * @return Medical
     */
    public function setTuberculose($tuberculose)
    {
        $this->tuberculose = $tuberculose;

        return $this;
    }

    /**
     * Get tuberculose
     *
     * @return boolean
     */
    public function getTuberculose()
    {
        return $this->tuberculose;
    }

    /**
     * Set arthrose
     *
     * @param boolean $arthrose
     *
     * @return Medical
     */
    public function setArthrose($arthrose)
    {
        $this->arthrose = $arthrose;

        return $this;
    }

    /**
     * Get arthrose
     *
     * @return boolean
     */
    public function getArthrose()
    {
        return $this->arthrose;
    }

    /**
     * Set chabdo
     *
     * @param string $chabdo
     *
     * @return Medical
     */
    public function setChabdo($chabdo)
    {
        $this->chabdo = $chabdo;

        return $this;
    }

    /**
     * Get chabdo
     *
     * @return string
     */
    public function getChabdo()
    {
        return $this->chabdo;
    }

    /**
     * Set chpulmo
     *
     * @param string $chpulmo
     *
     * @return Medical
     */
    public function setChpulmo($chpulmo)
    {
        $this->chpulmo = $chpulmo;

        return $this;
    }

    /**
     * Get chpulmo
     *
     * @return string
     */
    public function getChpulmo()
    {
        return $this->chpulmo;
    }

    /**
     * Set chortho
     *
     * @param string $chortho
     *
     * @return Medical
     */
    public function setChortho($chortho)
    {
        $this->chortho = $chortho;

        return $this;
    }

    /**
     * Get chortho
     *
     * @return string
     */
    public function getChortho()
    {
        return $this->chortho;
    }

    /**
     * Set cesarienne
     *
     * @param string $cesarienne
     *
     * @return Medical
     */
    public function setCesarienne($cesarienne)
    {
        $this->cesarienne = $cesarienne;

        return $this;
    }

    /**
     * Get cesarienne
     *
     * @return string
     */
    public function getCesarienne()
    {
        return $this->cesarienne;
    }

    /**
     * Set autres
     *
     * @param string $autres
     *
     * @return Medical
     */
    public function setAutres($autres)
    {
        $this->autres = $autres;

        return $this;
    }

    /**
     * Get autres
     *
     * @return string
     */
    public function getAutres()
    {
        return $this->autres;
    }

    /**
     * Set obsteg
     *
     * @param string $obsteg
     *
     * @return Medical
     */
    public function setObsteg($obsteg)
    {
        $this->obsteg = $obsteg;

        return $this;
    }

    /**
     * Get obsteg
     *
     * @return string
     */
    public function getObsteg()
    {
        return $this->obsteg;
    }

    /**
     * Set obstep
     *
     * @param string $obstep
     *
     * @return Medical
     */
    public function setObstep($obstep)
    {
        $this->obstep = $obstep;

        return $this;
    }

    /**
     * Get obstep
     *
     * @return string
     */
    public function getObstep()
    {
        return $this->obstep;
    }

    /**
     * Set psychiatrique
     *
     * @param string $psychiatrique
     *
     * @return Medical
     */
    public function setPsychiatrique($psychiatrique)
    {
        $this->psychiatrique = $psychiatrique;

        return $this;
    }

    /**
     * Get psychiatrique
     *
     * @return string
     */
    public function getPsychiatrique()
    {
        return $this->psychiatrique;
    }

    /**
     * Set oms
     *
     * @param string $oms
     *
     * @return Medical
     */
    public function setOms($oms)
    {
        $this->oms = $oms;

        return $this;
    }

    /**
     * Get oms
     *
     * @return string
     */
    public function getOms()
    {
        return $this->oms;
    }

    /**
     * Set temper
     *
     * @param string $temper
     *
     * @return Medical
     */
    public function setTemper($temper)
    {
        $this->temper = $temper;

        return $this;
    }

    /**
     * Get temper
     *
     * @return string
     */
    public function getTemper()
    {
        return $this->temper;
    }

    /**
     * Set tensionone
     *
     * @param string $tensionone
     *
     * @return Medical
     */
    public function setTensionone($tensionone)
    {
        $this->tensionone = $tensionone;

        return $this;
    }

    /**
     * Get tensionone
     *
     * @return string
     */
    public function getTensionone()
    {
        return $this->tensionone;
    }

    /**
     * Set tensiontwo
     *
     * @param string $tensiontwo
     *
     * @return Medical
     */
    public function setTensiontwo($tensiontwo)
    {
        $this->tensiontwo = $tensiontwo;

        return $this;
    }

    /**
     * Get tensiontwo
     *
     * @return string
     */
    public function getTensiontwo()
    {
        return $this->tensiontwo;
    }

    /**
     * Set pouls
     *
     * @param string $pouls
     *
     * @return Medical
     */
    public function setPouls($pouls)
    {
        $this->pouls = $pouls;

        return $this;
    }

    /**
     * Get pouls
     *
     * @return string
     */
    public function getPouls()
    {
        return $this->pouls;
    }

    /**
     * Set respir
     *
     * @param string $respir
     *
     * @return Medical
     */
    public function setRespir($respir)
    {
        $this->respir = $respir;

        return $this;
    }

    /**
     * Get respir
     *
     * @return string
     */
    public function getRespir()
    {
        return $this->respir;
    }

    /**
     * Set oxygene
     *
     * @param string $oxygene
     *
     * @return Medical
     */
    public function setOxygene($oxygene)
    {
        $this->oxygene = $oxygene;

        return $this;
    }

    /**
     * Get oxygene
     *
     * @return string
     */
    public function getOxygene()
    {
        return $this->oxygene;
    }

    /**
     * Set coeuretvais
     *
     * @param string $coeuretvais
     *
     * @return Medical
     */
    public function setCoeuretvais($coeuretvais)
    {
        $this->coeuretvais = $coeuretvais;

        return $this;
    }

    /**
     * Get coeuretvais
     *
     * @return string
     */
    public function getCoeuretvais()
    {
        return $this->coeuretvais;
    }

    /**
     * Set pulmo
     *
     * @param string $pulmo
     *
     * @return Medical
     */
    public function setPulmo($pulmo)
    {
        $this->pulmo = $pulmo;

        return $this;
    }

    /**
     * Get pulmo
     *
     * @return string
     */
    public function getPulmo()
    {
        return $this->pulmo;
    }

    /**
     * Set neuro
     *
     * @param string $neuro
     *
     * @return Medical
     */
    public function setNeuro($neuro)
    {
        $this->neuro = $neuro;

        return $this;
    }

    /**
     * Get neuro
     *
     * @return string
     */
    public function getNeuro()
    {
        return $this->neuro;
    }

    /**
     * Set autreexam
     *
     * @param string $autreexam
     *
     * @return Medical
     */
    public function setAutreexam($autreexam)
    {
        $this->autreexam = $autreexam;

        return $this;
    }

    /**
     * Get autreexam
     *
     * @return string
     */
    public function getAutreexam()
    {
        return $this->autreexam;
    }

    /**
     * Set diagnostic
     *
     * @param string $diagnostic
     *
     * @return Medical
     */
    public function setDiagnostic($diagnostic)
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    /**
     * Get diagnostic
     *
     * @return string
     */
    public function getDiagnostic()
    {
        return $this->diagnostic;
    }

    /**
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Medical
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
     * Set charriot
     *
     * @param boolean $charriot
     *
     * @return Medical
     */
    public function setCharriot($charriot)
    {
        $this->charriot = $charriot;

        return $this;
    }

    /**
     * Get charriot
     *
     * @return boolean
     */
    public function getCharriot()
    {
        return $this->charriot;
    }

    /**
     * Set enceinte
     *
     * @param boolean $enceinte
     *
     * @return Medical
     */
    public function setEnceinte($enceinte)
    {
        $this->enceinte = $enceinte;

        return $this;
    }

    /**
     * Get enceinte
     *
     * @return boolean
     */
    public function getEnceinte()
    {
        return $this->enceinte;
    }

    /**
     * Set trait1
     *
     * @param string $trait1
     *
     * @return Medical
     */
    public function setTrait1($trait1)
    {
        $this->trait1 = $trait1;

        return $this;
    }

    /**
     * Get trait1
     *
     * @return string
     */
    public function getTrait1()
    {
        return $this->trait1;
    }

    /**
     * Set trait2
     *
     * @param string $trait2
     *
     * @return Medical
     */
    public function setTrait2($trait2)
    {
        $this->trait2 = $trait2;

        return $this;
    }

    /**
     * Get trait2
     *
     * @return string
     */
    public function getTrait2()
    {
        return $this->trait2;
    }

    /**
     * Set trait3
     *
     * @param string $trait3
     *
     * @return Medical
     */
    public function setTrait3($trait3)
    {
        $this->trait3 = $trait3;

        return $this;
    }

    /**
     * Get trait3
     *
     * @return string
     */
    public function getTrait3()
    {
        return $this->trait3;
    }

    /**
     * Set trait4
     *
     * @param string $trait4
     *
     * @return Medical
     */
    public function setTrait4($trait4)
    {
        $this->trait4 = $trait4;

        return $this;
    }

    /**
     * Get trait4
     *
     * @return string
     */
    public function getTrait4()
    {
        return $this->trait4;
    }

    /**
     * Set trait5
     *
     * @param string $trait5
     *
     * @return Medical
     */
    public function setTrait5($trait5)
    {
        $this->trait5 = $trait5;

        return $this;
    }

    /**
     * Get trait5
     *
     * @return string
     */
    public function getTrait5()
    {
        return $this->trait5;
    }
}
