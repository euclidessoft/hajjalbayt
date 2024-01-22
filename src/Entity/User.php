<?php
// src/Entity/User.php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(
 * fields ={"email"},
 * message = "Adresse email déjà utilisée"
 *)
 */
class User implements UserInterface
{
    
    const jobs = ['employer' => 'employer' ,'caissier' => 'caissier', 'medecin' => 'medecin' , 'administrateur' => 'administrateur'];
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agence", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agence;

    
    /**
    * @ORM\OneToOne(targetEntity="App\Entity\Image",cascade={"persist","refresh"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $image;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="6", minMessage="Trop court, 6 caractères minimum")
     */
    private $password;
    
    /**
     *
     * @Assert\EqualTo(propertyPath="password", message="Mot de passe non conforme")
     */
    public $confirm;
    
    public $test;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Adresse email invalide")
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     * pattern= "/^((3[03]\s?[89]\d{2}(\s?\d{2}){2})|(7[0768]\s?\d{3}(\s?\d{2}){2}))$/",
     * match = true,
     * message = "Numéro incorrect")
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fonction;
    
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $roles = [];
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="caisse", type="boolean")
     */
    private $caisse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function __construct()
    {
        $this->caisse = false;
        $this->enabled = false;
    }
    
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): self
    {
        $this->password = $password;
        
        return $this;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(string $email): self
    {
        $this->email = $email;
        
        return $this;
    }
    
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }
    
    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;
        
        return $this;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }
    
    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        
        return $this;
    }
    
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        
        return $this;
    }
    
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
    
    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        
        return $this;
    }
    
    public function getFonction(): ?string
    {
        return $this->fonction;
    }
    
    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;
        
        return $this;
    }
    
    public function getsalt(): ?array
    {
        return null;
    }
    
    public function eraseCredentials()
    {
        return null;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getRoles(): ?array
    {
        return $this->roles;
    }
    
    public function setRoles(?array $role): self
    {
        $this->roles = $role;
        
        return $this;
    }
    
    public function getTest(): ?string
    {
        return $this->test;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getCaisse(): ?bool
    {
        return $this->caisse;
    }

    public function setCaisse(bool $caisse): self
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }
    
}