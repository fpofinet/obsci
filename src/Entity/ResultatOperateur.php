<?php

namespace App\Entity;

use App\Repository\ResultatOperateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatOperateurRepository::class)]
class ResultatOperateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $etat = null;

    #[ORM\Column]
    private ?int $suffrageExprime = null;

    #[ORM\Column]
    private ?int $suffrageNul = null;

    #[ORM\Column]
    private ?int $votant = null;

    #[ORM\Column]
    private ?int $voteOui = null;

    #[ORM\Column]
    private ?int $voteNon = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePv = null;

    #[ORM\ManyToOne(inversedBy: 'resultatOperateurs')]
    private ?Commune $commune = null;

    #[ORM\Column(length: 255)]
    private ?string $codeBureau = null;

    #[ORM\ManyToOne(inversedBy: 'resultatOperateurs')]
    private ?User $autor = null;

    #[ORM\Column(length: 255)]
    private ?string $agentSaisie = null;

    #[ORM\Column(length: 255)]
    private ?string $submitter = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $submittedOn = null;

    #[ORM\Column]
    private ?int $validateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelleBureauVote = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVotant(): ?int
    {
        return $this->votant;
    }

    public function setVotant(int $votant): static
    {
        $this->votant = $votant;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getCodeBureau(): ?string
    {
        return $this->codeBureau;
    }

    public function setCodeBureau(string $codeBureau): static
    {
        $this->codeBureau = $codeBureau;

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

    public function getAgentSaisie(): ?string
    {
        return $this->agentSaisie;
    }

    public function setAgentSaisie(string $agentSaisie): static
    {
        $this->agentSaisie = $agentSaisie;

        return $this;
    }

    public function getSubmitter(): ?string
    {
        return $this->submitter;
    }

    public function setSubmitter(string $submitter): static
    {
        $this->submitter = $submitter;

        return $this;
    }

    public function getSubmittedOn(): ?\DateTimeImmutable
    {
        return $this->submittedOn;
    }

    public function setSubmittedOn(\DateTimeImmutable $submittedOn): static
    {
        $this->submittedOn = $submittedOn;

        return $this;
    }

    public function getValidateur(): ?int
    {
        return $this->validateur;
    }

    public function setValidateur(int $validateur): static
    {
        $this->validateur = $validateur;

        return $this;
    }

    public function getLibelleBureauVote(): ?string
    {
        return $this->libelleBureauVote;
    }

    public function setLibelleBureauVote(?string $libelleBureauVote): static
    {
        $this->libelleBureauVote = $libelleBureauVote;

        return $this;
    }
}
