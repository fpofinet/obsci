<?php

namespace App\Entity;

use App\Repository\TempResultatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TempResultatRepository::class)]
class TempResultat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $province = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $departement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commune = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bureauVote = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreVotant = null;

    #[ORM\Column(nullable: true)]
    private ?int $bulletinNuls = null;

    #[ORM\Column(nullable: true)]
    private ?int $suffrageExprime = null;

    #[ORM\Column(nullable: true)]
    private ?int $voteOui = null;

    #[ORM\Column(nullable: true)]
    private ?int $voteNon = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $procesVerbal = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idSubmitter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeKobo = null;

    #[ORM\Column]
    private ?int $etat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(?string $commune): static
    {
        $this->commune = $commune;

        return $this;
    }

    public function getBureauVote(): ?string
    {
        return $this->bureauVote;
    }

    public function setBureauVote(?string $bureauVote): static
    {
        $this->bureauVote = $bureauVote;

        return $this;
    }

    public function getNombreVotant(): ?int
    {
        return $this->nombreVotant;
    }

    public function setNombreVotant(?int $nombreVotant): static
    {
        $this->nombreVotant = $nombreVotant;

        return $this;
    }

    public function getBulletinNuls(): ?int
    {
        return $this->bulletinNuls;
    }

    public function setBulletinNuls(?int $bulletinNuls): static
    {
        $this->bulletinNuls = $bulletinNuls;

        return $this;
    }

    public function getSuffrageExprime(): ?int
    {
        return $this->suffrageExprime;
    }

    public function setSuffrageExprime(?int $suffrageExprime): static
    {
        $this->suffrageExprime = $suffrageExprime;

        return $this;
    }

    public function getVoteOui(): ?int
    {
        return $this->voteOui;
    }

    public function setVoteOui(?int $voteOui): static
    {
        $this->voteOui = $voteOui;

        return $this;
    }

    public function getVoteNon(): ?int
    {
        return $this->voteNon;
    }

    public function setVoteNon(?int $voteNon): static
    {
        $this->voteNon = $voteNon;

        return $this;
    }

    public function getProcesVerbal(): ?string
    {
        return $this->procesVerbal;
    }

    public function setProcesVerbal(?string $procesVerbal): static
    {
        $this->procesVerbal = $procesVerbal;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdSubmitter(): ?string
    {
        return $this->idSubmitter;
    }

    public function setIdSubmitter(?string $idSubmitter): static
    {
        $this->idSubmitter = $idSubmitter;

        return $this;
    }

    public function getCodeKobo(): ?string
    {
        return $this->codeKobo;
    }

    public function setCodeKobo(?string $codeKobo): static
    {
        $this->codeKobo = $codeKobo;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
}
