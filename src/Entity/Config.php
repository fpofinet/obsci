<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $iterration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIterration(): ?int
    {
        return $this->iterration;
    }

    public function setIterration(int $iterration): static
    {
        $this->iterration = $iterration;

        return $this;
    }
}
