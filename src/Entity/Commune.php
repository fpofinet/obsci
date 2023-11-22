<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommuneRepository::class)]
class Commune
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'communes')]
    private ?Departement $departement = null;

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: BureauVote::class)]
    private Collection $bureauVote;

    public function __construct()
    {
        $this->bureauVote = new ArrayCollection();
    }

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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection<int, BureauVote>
     */
    public function getBureauVote(): Collection
    {
        return $this->bureauVote;
    }

    public function addBureauVote(BureauVote $bureauVote): static
    {
        if (!$this->bureauVote->contains($bureauVote)) {
            $this->bureauVote->add($bureauVote);
            $bureauVote->setCommune($this);
        }

        return $this;
    }

    public function removeBureauVote(BureauVote $bureauVote): static
    {
        if ($this->bureauVote->removeElement($bureauVote)) {
            // set the owning side to null (unless already changed)
            if ($bureauVote->getCommune() === $this) {
                $bureauVote->setCommune(null);
            }
        }

        return $this;
    }
}
