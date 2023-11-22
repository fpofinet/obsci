<?php

namespace App\Entity;

use App\Repository\TempResultatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TempResultatRepository::class)]
class TempResultat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?int $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $submitter = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $codeBv = null;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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

    public function getSubmitter(): ?string
    {
        return $this->submitter;
    }

    public function setSubmitter(string $submitter): static
    {
        $this->submitter = $submitter;

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

    public function getCodeBv(): ?string
    {
        return $this->codeBv;
    }

    public function setCodeBv(string $codeBv): static
    {
        $this->codeBv = $codeBv;

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
}
