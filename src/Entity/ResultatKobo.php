<?php

namespace App\Entity;

use App\Repository\ResultatKoboRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatKoboRepository::class)]
class ResultatKobo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePv = null;

    #[ORM\Column(length: 255)]
    private ?string $codeKobo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $submitter = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSubmit = null;

    #[ORM\Column(nullable: true)]
    private ?int $etat = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCodeKobo(): ?string
    {
        return $this->codeKobo;
    }

    public function setCodeKobo(string $codeKobo): static
    {
        $this->codeKobo = $codeKobo;

        return $this;
    }

    public function getSubmitter(): ?string
    {
        return $this->submitter;
    }

    public function setSubmitter(?string $submitter): static
    {
        $this->submitter = $submitter;

        return $this;
    }

    public function getDateSubmit(): ?\DateTimeInterface
    {
        return $this->dateSubmit;
    }

    public function setDateSubmit(?\DateTimeInterface $dateSubmit): static
    {
        $this->dateSubmit = $dateSubmit;

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
}
