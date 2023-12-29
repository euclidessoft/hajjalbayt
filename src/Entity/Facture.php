<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
class Facture
{
    /**
	* @ORM\ManyToOne(targetEntity="App\Entity\User")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $user;
    /**
	* @ORM\ManyToOne(targetEntity="App\Entity\Agence")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $agence;
    
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecom;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->datecom = new \Datetime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

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

    public function getDatecom(): ?\DateTimeInterface
    {
        return $this->datecom;
    }

    public function setDatecom(\DateTimeInterface $datecom): self
    {
        $this->datecom = $datecom;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
