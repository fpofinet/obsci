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

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: ResultatOperateur::class)]
    private Collection $resultatOperateurs;

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: ResultatSuperviseur::class)]
    private Collection $resultatSuperviseurs;

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: Resultat::class)]
    private Collection $resultats;

    #[ORM\Column(nullable: true)]
    private ?int $nombreBureau = null;


    public function __construct()
    {
        $this->resultatOperateurs = new ArrayCollection();
        $this->resultatSuperviseurs = new ArrayCollection();
        $this->resultats = new ArrayCollection();
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
     * @return Collection<int, ResultatOperateur>
     */
    public function getResultatOperateurs(): Collection
    {
        return $this->resultatOperateurs;
    }

    public function addResultatOperateur(ResultatOperateur $resultatOperateur): static
    {
        if (!$this->resultatOperateurs->contains($resultatOperateur)) {
            $this->resultatOperateurs->add($resultatOperateur);
            $resultatOperateur->setCommune($this);
        }

        return $this;
    }

    public function removeResultatOperateur(ResultatOperateur $resultatOperateur): static
    {
        if ($this->resultatOperateurs->removeElement($resultatOperateur)) {
            // set the owning side to null (unless already changed)
            if ($resultatOperateur->getCommune() === $this) {
                $resultatOperateur->setCommune(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ResultatSuperviseur>
     */
    public function getResultatSuperviseurs(): Collection
    {
        return $this->resultatSuperviseurs;
    }

    public function addResultatSuperviseur(ResultatSuperviseur $resultatSuperviseur): static
    {
        if (!$this->resultatSuperviseurs->contains($resultatSuperviseur)) {
            $this->resultatSuperviseurs->add($resultatSuperviseur);
            $resultatSuperviseur->setCommune($this);
        }

        return $this;
    }

    public function removeResultatSuperviseur(ResultatSuperviseur $resultatSuperviseur): static
    {
        if ($this->resultatSuperviseurs->removeElement($resultatSuperviseur)) {
            // set the owning side to null (unless already changed)
            if ($resultatSuperviseur->getCommune() === $this) {
                $resultatSuperviseur->setCommune(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Resultat>
     */
    public function getResultats(): Collection
    {
        return $this->resultats;
    }

    public function addResultat(Resultat $resultat): static
    {
        if (!$this->resultats->contains($resultat)) {
            $this->resultats->add($resultat);
            $resultat->setCommune($this);
        }

        return $this;
    }

    public function removeResultat(Resultat $resultat): static
    {
        if ($this->resultats->removeElement($resultat)) {
            // set the owning side to null (unless already changed)
            if ($resultat->getCommune() === $this) {
                $resultat->setCommune(null);
            }
        }

        return $this;
    }

    public function getNombreBureau(): ?int
    {
        return $this->nombreBureau;
    }

    public function setNombreBureau(?int $nombreBureau): static
    {
        $this->nombreBureau = $nombreBureau;

        return $this;
    }
}
