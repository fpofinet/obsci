<?php

namespace App\Entity;

use App\Repository\ResultatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatRepository::class)]
class Resultat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $codeBureau = null;

    #[ORM\Column(nullable: true)]
    private ?int $etat = null;

    #[ORM\Column]
    private ?int $votant = null;

    #[ORM\Column]
    private ?int $suffrageExprime = null;

    #[ORM\Column]
    private ?int $suffrageNul = null;

    #[ORM\Column]
    private ?int $voteOui = null;

    #[ORM\Column]
    private ?int $voteNon = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePv = null;

    #[ORM\ManyToOne(inversedBy: 'resultats')]
    private ?Commune $commune = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $validedOn = null;

    #[ORM\ManyToOne(inversedBy: 'resultats')]
    private ?User $autor = null;

    #[ORM\ManyToOne(inversedBy: 'resultats')]
    private ?User $validator = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getCodeBureau(): ?string
    {
        return $this->codeBureau;
    }

    public function setCodeBureau(string $codeBureau): static
    {
        $this->codeBureau = $codeBureau;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getVotant(): ?int
    {
        return $this->votant;
    }

    public function setVotant(int $votant): static
    {
        $this->votant = $votant;

        return $this;
    }

    public function getSuffrageExprime(): ?int
    {
        return $this->suffrageExprime;
    }

    public function setSuffrageExprime(int $suffrageExprime): static
    {
        $this->suffrageExprime = $suffrageExprime;

        return $this;
    }

    public function getSuffrageNul(): ?int
    {
        return $this->suffrageNul;
    }

    public function setSuffrageNul(int $suffrageNul): static
    {
        $this->suffrageNul = $suffrageNul;

        return $this;
    }

    public function getVoteOui(): ?int
    {
        return $this->voteOui;
    }

    public function setVoteOui(int $voteOui): static
    {
        $this->voteOui = $voteOui;

        return $this;
    }

    public function getVoteNon(): ?int
    {
        return $this->voteNon;
    }

    public function setVoteNon(int $voteNon): static
    {
        $this->voteNon = $voteNon;

        return $this;
    }

    public function getImagePv(): ?string
    {
        return $this->imagePv;
    }

    public function setImagePv(string $imagePv): static
    {
        $this->imagePv = $imagePv;

        return $this;
    }

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): static
    {
        $this->commune = $commune;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getValidedOn(): ?\DateTimeImmutable
    {
        return $this->validedOn;
    }

    public function setValidedOn(\DateTimeImmutable $validedOn): static
    {
        $this->validedOn = $validedOn;

        return $this;
    }

    public function getAutor(): ?User
    {
        return $this->autor;
    }

    public function setAutor(?User $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    public function getValidator(): ?User
    {
        return $this->validator;
    }

    public function setValidator(?User $validator): static
    {
        $this->validator = $validator;

        return $this;
    }
}
