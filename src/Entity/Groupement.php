<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Groupement
 *
 * @ORM\Table(name="groupement")
 * @ORM\Entity(repositoryClass="App\Repository\GroupementRepository")
 */
class Groupement
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	* @Assert\Valid()
	*/
	private $chef;

	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Session")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $session;
	
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	* @Assert\NotBlank(message = "Sélectionnez l'agence")
	* @Assert\Valid()
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
     * @var int
     *
     * @ORM\Column(name="quota", type="integer")
	 * @Assert\Length(min = 2, minMessage="2 chiffres au minimum",max = 3, maxMessage="3 chiffres au maximum")
	  * @Assert\NotBlank(message = "Renseignez le quota")
	 * @Assert\Regex(
	 * pattern= "/^\d+$/",
	 * match = true,
	 * message = "Des Des chiffres uniquement")
     */
    private $quota;


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
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Groupement
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
     * Set agence
     *
     * @param \App\Entity\Agence $agence
     *
     * @return Groupement
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
     * Set quota
     *
     * @param integer $quota
     *
     * @return Groupement
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Get quota
     *
     * @return integer
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Set chef
     *
     * @param \App\Entity\Agence $chef
     *
     * @return Groupement
     */
    public function setChef(\App\Entity\Agence $chef)
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * Get chef
     *
     * @return \App\Entity\Agence
     */
    public function getChef()
    {
        return $this->chef;
    }
}
