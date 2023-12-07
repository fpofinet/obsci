<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'autor', targetEntity: ResultatOperateur::class)]
    private Collection $resultatOperateurs;

    #[ORM\OneToMany(mappedBy: 'autor', targetEntity: ResultatSuperviseur::class)]
    private Collection $resultatSuperviseurs;

    #[ORM\Column(nullable: true)]
    private ?int $validateur = null;

    #[ORM\OneToMany(mappedBy: 'autor', targetEntity: Resultat::class)]
    private Collection $resultats;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

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
            $resultatOperateur->setAutor($this);
        }

        return $this;
    }

    public function removeResultatOperateur(ResultatOperateur $resultatOperateur): static
    {
        if ($this->resultatOperateurs->removeElement($resultatOperateur)) {
            // set the owning side to null (unless already changed)
            if ($resultatOperateur->getAutor() === $this) {
                $resultatOperateur->setAutor(null);
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
            $resultatSuperviseur->setAutor($this);
        }

        return $this;
    }

    public function removeResultatSuperviseur(ResultatSuperviseur $resultatSuperviseur): static
    {
        if ($this->resultatSuperviseurs->removeElement($resultatSuperviseur)) {
            // set the owning side to null (unless already changed)
            if ($resultatSuperviseur->getAutor() === $this) {
                $resultatSuperviseur->setAutor(null);
            }
        }

        return $this;
    }

    public function getValidateur(): ?int
    {
        return $this->validateur;
    }

    public function setValidateur(?int $validateur): static
    {
        $this->validateur = $validateur;

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
            $resultat->setAutor($this);
        }

        return $this;
    }

    public function removeResultat(Resultat $resultat): static
    {
        if ($this->resultats->removeElement($resultat)) {
            // set the owning side to null (unless already changed)
            if ($resultat->getAutor() === $this) {
                $resultat->setAutor(null);
            }
        }

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }
}
