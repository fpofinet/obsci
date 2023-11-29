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
