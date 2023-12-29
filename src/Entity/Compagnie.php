<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Compagnie
 *
 * @ORM\Table(name="compagnie")
 * @ORM\Entity(repositoryClass="App\Repository\CompagnieRepository")
 */
class Compagnie
{
	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
	
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
	 * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=255)
	 * @Assert\NotBlank(message = "Numéro de téléphone obligatoire")
	 * @Assert\Regex(
	 * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[0768]\s?\d{3}(\s?\d{2}){2}))$/",
	 * match = true,
	 * message = "Numéro incorrect")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message = "Sélectionnez le type de compagnie")
     */
    private $type;


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
     * @return Compagnie
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
     * @return Compagnie
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
     * @return Compagnie
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
     * @return Compagnie
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
     * Set session
     *
     * @param \App\Entity\Session $session
     *
     * @return Compagnie
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
     * @return Compagnie
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
